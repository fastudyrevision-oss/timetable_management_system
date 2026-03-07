import pdfplumber
import json
import re
from datetime import datetime

# ── regex patterns ────────────────────────────────────────────────────────────
TIME_PATTERN    = re.compile(r'\((\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})\)')
COURSE_PATTERN  = re.compile(r'#([A-Z]{2,6}-\d{3,4}(?:-\d+)?)')
ROOM_PATTERN    = re.compile(r'(?:CR[-\s]?\d+[A-Za-z]?|L-\d+|CyberL-\d+|Smart\s*Lab|(?:[A-Z][a-z]+\s+)?Hall)', re.IGNORECASE)
DAYS = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]

# ── common helpers ────────────────────────────────────────────────────────────

def normalize_time(time_str: str) -> str:
    """
    Ensures time is in HH:MM format (24h).
    Handles '8:00 AM', '10:00 AM', '2:00 PM', '14:30' etc.
    """
    time_str = time_str.strip().upper()
    if not time_str:
        return ""
    
    # Try 24h format first (e.g. "14:30" or "08:00")
    try:
        dt = datetime.strptime(time_str, "%H:%M")
        return dt.strftime("%H:%M")
    except ValueError:
        pass

    # Try AM/PM formats
    for fmt in ("%I:%M %p", "%I:%M%p", "%H:%M %p", "%H:%M%p"):
        try:
            dt = datetime.strptime(time_str, fmt)
            return dt.strftime("%H:%M")
        except ValueError:
            continue
            
    return time_str

# ── Grid Format Helpers ────────────────────────────────────────────────────────

def parse_room_label(cell_text: str) -> str:
    if not cell_text:
        return ""
    full_text = " ".join(line.strip() for line in cell_text.splitlines() if line.strip())
    match = ROOM_PATTERN.search(full_text)
    if match:
        return match.group(0).strip()
    return full_text

def parse_grid_cell(cell_text: str, room: str, day: str) -> list[dict]:
    if not cell_text or not cell_text.strip():
        return []

    blocks = re.split(r'\uf1f8\s*Delete', cell_text)
    records = []

    for block in blocks:
        block = block.strip()
        if not block:
            continue

        lines = [l.strip() for l in block.splitlines() if l.strip()]
        buffer = []
        i = 0
        while i < len(lines):
            line = lines[i]
            codes = COURSE_PATTERN.findall(line)

            if codes:
                subject_prefix = " ".join(buffer).strip()
                code_part = line[:line.find('#')].strip()
                full_subject = (subject_prefix + " " + code_part).strip()

                details = []
                j = i + 1
                while j < len(lines) and not COURSE_PATTERN.search(lines[j]):
                    details.append(lines[j])
                    j += 1

                teacher = ""
                start_time = ""
                end_time = ""
                for det in details:
                    time_match = TIME_PATTERN.search(det)
                    if time_match:
                        start_time = normalize_time(time_match.group(1))
                        end_time = normalize_time(time_match.group(2))
                        teacher = det[:time_match.start()].strip()
                        break

                programme = ""
                for det in details:
                    if re.search(r'BS in|MS |PhD|Semester#', det):
                        programme = det
                        break

                for code in codes:
                    records.append({
                        "subject":      full_subject,
                        "course_code":  code,
                        "teacher":      teacher,
                        "programme":    programme,
                        "room":         room,
                        "day":          day,
                        "start_time":   start_time,
                        "end_time":     end_time
                    })

                buffer = []
                i = j
            else:
                buffer.append(line)
                i += 1

    return records

# ── Schedule Format Helpers ────────────────────────────────────────────────────

def parse_schedule_format(pdf_path: str) -> list[dict]:
    all_records = []
    with pdfplumber.open(pdf_path) as pdf:
        for page in pdf.pages:
            text = page.extract_text() or ""
            # Find the programme name for this schedule
            prog_match = re.search(r'Schedule for:\s*(.*?)(?:\n|$)', text)
            current_programme = prog_match.group(1).strip() if prog_match else "Unknown Programme"
            
            tables = page.extract_tables()
            for table in tables:
                if not table or len(table) < 2:
                    continue
                
                header = [str(h).strip() for h in table[0] if h]
                if not ("Day" in header and "Time" in header and "Subject (Course Code)" in header):
                    continue
                
                # Column indexing
                try:
                    idx_day = header.index("Day")
                    idx_time = header.index("Time")
                    idx_subj = header.index("Subject (Course Code)")
                    idx_teacher = header.index("Teacher")
                    idx_room = header.index("Room")
                except ValueError:
                    continue

                for row in table[1:]:
                    if not row or len(row) < 5:
                        continue
                    
                    day = str(row[idx_day]).strip()
                    time_str = str(row[idx_time]).strip()
                    subj_cell = str(row[idx_subj]).strip()
                    teacher = str(row[idx_teacher]).strip()
                    room_cell = str(row[idx_room]).strip()

                    # Handle missing day (merging cells in PDF)
                    if not day and all_records:
                        day = all_records[-1]["day"]

                    # Extract times: "8:00 AM - 9:30 AM"
                    start_time, end_time = "", ""
                    if "-" in time_str:
                        parts = time_str.split("-")
                        start_time = normalize_time(parts[0])
                        end_time = normalize_time(parts[1])

                    # Extract subject and code: "Ethics-II #URCG-5132"
                    code_match = COURSE_PATTERN.search(subj_cell)
                    course_code = code_match.group(1) if code_match else ""
                    subject = subj_cell[:subj_cell.find('#')].strip() if '#' in subj_cell else subj_cell
                    
                    # Clean up room: often contains "Department of Information..."
                    room = ""
                    room_match = ROOM_PATTERN.search(room_cell)
                    if room_match:
                        room = room_match.group(0).strip()
                    else:
                        # Fallback to last line if pattern fails
                        room = room_cell.splitlines()[-1].strip() if "\n" in room_cell else room_cell
                        room = parse_room_label(room)

                    all_records.append({
                        "subject":      subject,
                        "course_code":  course_code,
                        "teacher":      teacher.replace("\n", " "),
                        "programme":    current_programme,
                        "room":         room,
                        "day":          day,
                        "start_time":   start_time,
                        "end_time":     end_time
                    })
    return all_records

# ── Main Dispatcher ────────────────────────────────────────────────────────────

def parse_timetable(pdf_path: str) -> list[dict]:
    with pdfplumber.open(pdf_path) as pdf:
        first_page_text = pdf.pages[0].extract_text() or ""
        # Check for Schedule format markers
        if "Department Program Schedules" in first_page_text or "Schedule for:" in first_page_text:
            return parse_schedule_format(pdf_path)
        
    # Default to Grid format
    all_records = []
    with pdfplumber.open(pdf_path) as pdf:
        start_page = 1 if len(pdf.pages) > 1 else 0
        for page in pdf.pages[start_page:]:
            tables = page.extract_tables()
            for table in tables:
                if not table: continue
                header = table[0]
                if not any(d in str(header) for d in DAYS): continue

                day_map = {i: d for i, cell in enumerate(header) if cell for d in DAYS if d in cell}
                for row in table[1:]:
                    if not row: continue
                    room = parse_room_label(row[0] if row[0] else "")
                    for col_idx, cell in enumerate(row[1:], start=1):
                        day = day_map.get(col_idx, "")
                        if cell:
                            all_records.extend(parse_grid_cell(cell, room, day))
    return all_records

if __name__ == "__main__":
    import sys
    pdf_to_parse = sys.argv[1] if len(sys.argv) > 1 else "public/uploads/IT-Dept_All-Program-Schedule_07-Mar-2026_18-50-41.pdf"
    output_file = "timetable.json"

    print(f"Parsing: {pdf_to_parse}...")
    records = parse_timetable(pdf_to_parse)

    with open(output_file, "w", encoding="utf-8") as f:
        json.dump(records, f, indent=4, ensure_ascii=False)

    print(f"Successfully parsed {len(records)} records into {output_file}")