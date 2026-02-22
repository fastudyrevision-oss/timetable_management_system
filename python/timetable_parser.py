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
    # Allow optional # prefix, standard course code format like CSC-101 or CSC101
    'code': r'(?:#)?([A-Z]{2,4}[- ]?\d{3,4})', 
    'section': r'(Regular|Self Support\s*\d*|Section\s*[A-Z])',
    'semester': r'Semester\s*[#]?\s*(\d+|…|\.{3})', 
    'room_header': r'(ROOM|LAB|CR)[\s-]*(\d+)',
}

days_of_week = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"]

def parse_single_block(text, day, room, time_slot):
    if not text:
        return None

    # Clean text - Remove "was: ..." notes which are common in Ramadan/Revised schedules
    text = re.sub(r'was:\s*\d{1,2}:\d{2}\s*(-|to)\s*\d{1,2}:\d{2}', '', text, flags=re.IGNORECASE).strip()

    # 1. Subject Code
    subject_code = None
    code_match = re.search(patterns['code'], text)
    if code_match:
        subject_code = code_match.group(1)
    
    # 2. Subject Name
    subject_name = "Unknown"
    if code_match:
        # Everything before the code
        raw_subject = text[:code_match.start()].strip()
        # Remove common noise like leading numbers or "Combined ()"
        raw_subject = re.sub(r'Combined\s*\(\s*\)', '', raw_subject, flags=re.IGNORECASE)
        subject_name = re.sub(r'^\d+\s*', '', raw_subject).strip()
    else:
        # Fallback: everything before the first newline or parenthesis
        subject_name = re.split(r'[\(\n]', text)[0].strip()

    # 3. Section
    section = None
    sec_match = re.search(patterns['section'], text, re.IGNORECASE)
    if sec_match:
        section = sec_match.group(1).strip()

    # 4. Semester
    semester = "0" 
    # Try to find "(Xth Semester Intake)" or "Semester X"
    sem_intake_match = re.search(r'(\d+)(?:st|nd|rd|th)\s*Semester\s*Intake', text, re.IGNORECASE)
    if sem_intake_match:
        semester = sem_intake_match.group(1)
    else:
        sem_match = re.search(patterns['semester'], text, re.IGNORECASE)
        if sem_match:
            raw_sem = sem_match.group(1)
            if raw_sem.isdigit():
                semester = raw_sem

    # 5. Teacher (Improved)
    teacher = "TBA"
    
    # Batch detection (Improved)
    batch = None
    # Look for "BS in [Anything]" or "MSc [Anything]" etc.
    batch_pattern = r'(BS|MSc|MS|BE|BBA|AD)\s+(?:in\s+)?([\w\s]+?)(?=\s+(?:Regular|Self Support|Section|\()|$)'
    batch_match = re.search(batch_pattern, text, re.IGNORECASE)
    if batch_match:
        batch = f"{batch_match.group(1)} {batch_match.group(2)}".strip()
    
    # Text after all known entities to find teacher
    last_end = 0
    if code_match: last_end = max(last_end, code_match.end())
    
    # Usually teacher is at the very end before the time slot (which is already removed from text in parse_blocks)
    # Let's try to split by newline and take the last meaningful line that isn't the batch/semester info
    lines = [l.strip() for l in text.split('\n') if l.strip()]
    if lines:
        for line in reversed(lines):
            # Teacher usually doesn't have "Semester" or "Intake" or "Section" or the subject code
            if not any(word in line for word in ["Semester", "Intake", "Regular", "Support", "202"]) and not re.search(patterns['code'], line):
                teacher = line
                break

    return {
        "day": day,
        "room": room,
        "subject": subject_name if len(subject_name) > 2 else "Subject",
        "subject_code": subject_code if subject_code else "TBA",
        "teacher": teacher,
        "semester": semester,
        "section": section,
        "batch": batch,
        "time_slot": time_slot,
        "raw_text": f"{text} ({time_slot})"
    }

def parse_blocks(cell_text, day, room):
    if not cell_text or not cell_text.strip():
        return []

    # Find all time slots
    time_matches = list(re.finditer(patterns['time'], cell_text))
    if not time_matches:
        return []

    blocks = []
    for i in range(len(time_matches)):
        start_idx = time_matches[i].start()
        end_idx = time_matches[i+1].start() if i + 1 < len(time_matches) else len(cell_text)
        
        time_slot = time_matches[i].group(1)
        content_text = cell_text[time_matches[i].end():end_idx].strip()
        
        item = parse_single_block(content_text, day, room, time_slot)
        if item:
            blocks.append(item)
    
    return blocks

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
