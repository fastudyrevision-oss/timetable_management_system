import pdfplumber
import json
import re
import sys

# Ensure UTF-8 output
sys.stdout.reconfigure(encoding='utf-8')

if len(sys.argv) < 2:
    print(json.dumps({"error": "No PDF file provided"}))
    sys.exit(1)

pdf_path = sys.argv[1]
data = []

# Regex patterns
patterns = {
    'time': r'\(\s*(\d{1,2}:\d{2}\s*(?:-|to)\s*\d{1,2}:\d{2})\s*\)',
    'code': r'#([A-Z0-9\-]+)',
    'section': r'(Regular|Self Support\s*\d*)',
    'semester': r'Semester\s*[#]?\s*(\d+|…|\.{3})', # Matches "Semester#2" or "Semester..."
    'room_header': r'(ROOM|LAB|CR)[\s-]*(\d+)',
}

days_of_week = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]

def parse_blocks(cell_text, day, room):
    """
    Parses a cell that may contain multiple class blocks.
    Splits by the time pattern (Time - Time).
    """
    if not cell_text or not cell_text.strip():
        return []

    # Clean multiple spaces/newlines
    text = re.sub(r'\s+', ' ', cell_text.strip())
    
    # Split by time pattern, capturing the time
    parts = re.split(patterns['time'], text)
    
    parsed_items = []
    
    # Iterate in pairs: Text + Time
    num_parts = len(parts)
    
    for i in range(0, num_parts - 1, 2):
        block_text = parts[i].strip()
        time_slot = parts[i+1].strip()
        
        if not block_text:
            continue

        item = parse_single_block(block_text, time_slot, day, room)
        if item:
            parsed_items.append(item)

    return parsed_items

def parse_single_block(text, time_slot, day, room):
    # Extract details from the text block
    
    # 1. Subject Code
    subject_code = None
    code_match = re.search(patterns['code'], text)
    if code_match:
        subject_code = code_match.group(1)
    
    # 2. Subject Name
    subject_name = "Unknown"
    if code_match:
        subject_name = text[:code_match.start()].strip()
    else:
        subject_name = text.split('#')[0].strip()

    # 3. Section
    section = None
    sec_match = re.search(patterns['section'], text, re.IGNORECASE)
    if sec_match:
        section = sec_match.group(1)

    # 4. Semester
    semester = "0" # Default to string "0"
    sem_match = re.search(patterns['semester'], text, re.IGNORECASE)
    if sem_match:
        raw_sem = sem_match.group(1)
        if raw_sem.isdigit():
            semester = raw_sem
        else:
            semester = "0"

    # 5. Teacher
    teacher = "TBA"
    last_end = 0
    if sem_match:
        last_end = max(last_end, sem_match.end())
    elif sec_match:
        last_end = max(last_end, sec_match.end())
    
    session_match = re.search(r'\(\s*\d{4}\s*-\s*\d{4}\s*\)', text)
    if session_match:
        last_end = max(last_end, session_match.end())

    if last_end < len(text):
        potential_teacher = text[last_end:].strip()
        potential_teacher = re.sub(r'^[\s,\.]+', '', potential_teacher)
        if len(potential_teacher) > 2:
            teacher = potential_teacher

    # Batch
    batch = None
    if code_match and sec_match:
        start = code_match.end()
        end = sec_match.start()
        if end > start:
            batch = text[start:end].strip()
    
    return {
        "day": day,
        "room": room,
        "subject": subject_name,
        "subject_code": subject_code,
        "teacher": teacher,
        "semester": semester, # Always numeric string "0", "1", etc.
        "section": section,
        "batch": batch,
        "time_slot": time_slot,
        "raw_text": f"{text} ({time_slot})"
    }

try:
    with pdfplumber.open(pdf_path) as pdf:
        for page in pdf.pages:
            tables = page.extract_tables()
            
            for table in tables:
                if not table: continue
                
                # Header Processing
                headers = [str(h).strip().replace('\n', ' ') if h else "" for h in table[0]]
                
                col_day_map = {}
                for idx, h in enumerate(headers):
                    for d in days_of_week:
                        if d in h:
                            col_day_map[idx] = d
                            break
                            
                if not col_day_map: continue

                for row in table[1:]:
                    if not row: continue
                    
                    # Room detection
                    current_room = str(row[0]).strip() if row[0] else "TBA"
                    r_match = re.search(patterns['room_header'], current_room, re.IGNORECASE)
                    if r_match:
                        current_room = f"{r_match.group(1)}-{r_match.group(2)}"

                    for col_idx, cell_content in enumerate(row):
                        if col_idx in col_day_map:
                            day = col_day_map[col_idx]
                            items = parse_blocks(cell_content, day, current_room)
                            data.extend(items)

except Exception as e:
    print(json.dumps({"error": str(e), "data": data}))
    sys.exit(0)

print(json.dumps(data, indent=2))
