import pdfplumber
import json
import re

# ── regex patterns ────────────────────────────────────────────────────────────
TIME_PATTERN    = re.compile(r'\((\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})\)')
COURSE_PATTERN  = re.compile(r'#([A-Z]{2,6}-\d{3,4}(?:-\d+)?)')
ROOM_PATTERN    = re.compile(r'(?:CR[-\s]?\d+[A-Za-z]?|L-\d+|CyberL-\d+|Smart\s*Lab)', re.IGNORECASE)
DAYS = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]

# ── helpers ───────────────────────────────────────────────────────────────────

def parse_room_label(cell_text: str) -> str:
    """Extract the room/lab identifier from the first column."""
    if not cell_text:
        return ""
    match = ROOM_PATTERN.search(cell_text)
    if match:
        return match.group(0).strip()
    # fallback: last non-empty line of the cell (usually the room id)
    lines = [l.strip() for l in cell_text.splitlines() if l.strip()]
    return lines[-1] if lines else cell_text.strip()


def parse_cell(cell_text: str, room: str, day: str) -> list[dict]:
    """
    A single table cell can contain *multiple* classes stacked on top of each
    other.  Split on the Delete marker (\\uf1f8 Delete) that appears after each
    entry, then parse each block individually.
    """
    if not cell_text or not cell_text.strip():
        return []

    # Split into individual class blocks
    blocks = re.split(r'\uf1f8\s*Delete', cell_text)
    records = []

    for block in blocks:
        block = block.strip()
        if not block:
            continue

        lines = [l.strip() for l in block.splitlines() if l.strip()]
        if not lines:
            continue

        # ── course name & code ────────────────────────────────────────────
        # First line usually: "Course Name #CODE" or "#CODE Course Name"
        subject_line = lines[0]

        # Remove "Combined (nnn)" prefix that sometimes appears
        subject_line = re.sub(r'^Combined\s*\(\d*\)\s*', '', subject_line).strip()

        course_match = COURSE_PATTERN.search(block)
        course_code  = course_match.group(1) if course_match else ""

        # Subject name = everything before the #CODE tag on the first content line
        subject_name = re.split(r'\s*#[A-Z]', subject_line)[0].strip()
        if not subject_name:
            # try second line (sometimes "Combined ()" sits on line 0)
            subject_name = re.split(r'\s*#[A-Z]', lines[1])[0].strip() if len(lines) > 1 else ""

        # ── programme / section ──────────────────────────────────────────
        programme = ""
        for line in lines:
            if re.search(r'BS in|MS |PhD|Semester#', line):
                programme = line
                break

        # ── teacher & time ───────────────────────────────────────────────
        teacher    = ""
        start_time = ""
        end_time   = ""

        for line in lines:
            time_match = TIME_PATTERN.search(line)
            if time_match:
                start_time = time_match.group(1)
                end_time   = time_match.group(2)
                # Teacher name is the text *before* the parenthesised time
                teacher_part = line[:time_match.start()].strip()
                if teacher_part:
                    teacher = teacher_part
                break

        # ── previous (original) time ─────────────────────────────────────
        was_time = ""
        was_match = re.search(r'was:\s*(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})', block)
        if was_match:
            was_time = f"{was_match.group(1)} - {was_match.group(2)}"

        # Only emit if we captured at least a time slot
        if not start_time:
            continue

        records.append({
            "subject":      subject_name,
            "course_code":  course_code,
            "teacher":      teacher,
            "programme":    programme,
            "room":         room,
            "day":          day,
            "start_time":   start_time,
            "end_time":     end_time,
            "was_time":     was_time,
        })

    return records


# ── main ──────────────────────────────────────────────────────────────────────

def parse_timetable(pdf_path: str) -> list[dict]:
    all_records = []

    with pdfplumber.open(pdf_path) as pdf:
        # Skip page 0 (cover / class list page)
        for page_num, page in enumerate(pdf.pages[1:], start=2):
            tables = page.extract_tables()
            for table in tables:
                if not table:
                    continue
                # Identify header row
                header = table[0] if table else []
                # Check it really is a schedule table (has day names)
                if not any(d in str(header) for d in DAYS):
                    continue

                # Map column index → day name
                day_map: dict[int, str] = {}
                for col_idx, cell in enumerate(header):
                    for day in DAYS:
                        if cell and day in cell:
                            day_map[col_idx] = day

                for row in table[1:]:
                    if not row:
                        continue
                    room = parse_room_label(row[0] if row[0] else "")

                    for col_idx, cell in enumerate(row[1:], start=1):
                        day = day_map.get(col_idx, "")
                        records = parse_cell(cell, room, day)
                        all_records.extend(records)

    return all_records


if __name__ == "__main__":
    PDF_PATH = "IT Class Timetable Spring 2026 V4 (Ramadan).pdf"
    OUTPUT   = "timetable.json"

    records = parse_timetable(PDF_PATH)

    with open(OUTPUT, "w", encoding="utf-8") as f:
        json.dump(records, f, indent=4, ensure_ascii=False)

    print(f"Parsed {len(records)} class records -> {OUTPUT}")