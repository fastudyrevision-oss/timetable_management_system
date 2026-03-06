import pdfplumber
import json
import re

# ── regex patterns ────────────────────────────────────────────────────────────
TIME_PATTERN    = re.compile(r'\((\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})\)')
COURSE_PATTERN  = re.compile(r'#([A-Z]{2,6}-\d{3,4}(?:-\d+)?)')
ROOM_PATTERN    = re.compile(r'(?:CR[-\s]?\d+[A-Za-z]?|L-\d+|CyberL-\d+|Smart\s*Lab|(?:[A-Z][a-z]+\s+)?Hall)', re.IGNORECASE)
DAYS = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"]

# ── helpers ───────────────────────────────────────────────────────────────────

def parse_room_label(cell_text: str) -> str:
    """
    Extract the room/lab identifier from the first column.
    Tries to match a known pattern; if none, returns the whole cleaned text.
    """
    if not cell_text:
        return ""
    # Join all lines and search for a room pattern
    full_text = " ".join(line.strip() for line in cell_text.splitlines() if line.strip())
    match = ROOM_PATTERN.search(full_text)
    if match:
        return match.group(0).strip()
    # Fallback: return the whole cell text (cleaned)
    return full_text

def parse_cell(cell_text: str, room: str, day: str) -> list[dict]:
    """
    Parse a single table cell which may contain multiple class entries.
    Each entry is separated by the Delete marker (\uf1f8 Delete).
    Within an entry, multiple course codes (combined classes) are handled.
    """
    if not cell_text or not cell_text.strip():
        return []

    # Split into blocks by the Delete marker
    blocks = re.split(r'\uf1f8\s*Delete', cell_text)
    records = []

    for block in blocks:
        block = block.strip()
        if not block:
            continue

        lines = [l.strip() for l in block.splitlines() if l.strip()]
        buffer = []          # holds lines that belong to the subject prefix (before a code line)
        i = 0
        while i < len(lines):
            line = lines[i]
            codes = COURSE_PATTERN.findall(line)

            if codes:
                # --- this line contains one or more course codes ---
                # Build full subject name: buffer (prefix lines) + part of this line before the first '#'
                subject_prefix = " ".join(buffer).strip()
                code_part = line[:line.find('#')].strip()
                full_subject = (subject_prefix + " " + code_part).strip()

                # Collect details lines that follow (until next code line or end)
                details = []
                j = i + 1
                while j < len(lines) and not COURSE_PATTERN.search(lines[j]):
                    details.append(lines[j])
                    j += 1

                # Extract common information from the details
                teacher = ""
                start_time = ""
                end_time = ""
                for det in details:
                    time_match = TIME_PATTERN.search(det)
                    if time_match:
                        start_time = time_match.group(1)
                        end_time = time_match.group(2)
                        teacher = det[:time_match.start()].strip()
                        break

                programme = ""
                for det in details:
                    if re.search(r'BS in|MS |PhD|Semester#', det):
                        programme = det
                        break

                was_time = ""
                was_match = re.search(r'was:\s*(\d{1,2}:\d{2})\s*-\s*(\d{1,2}:\d{2})', "\n".join(details))
                if was_match:
                    was_time = f"{was_match.group(1)} - {was_match.group(2)}"

                # Create one record per course code
                for code in codes:
                    records.append({
                        "subject":      full_subject,
                        "course_code":  code,
                        "teacher":      teacher,
                        "programme":    programme,
                        "room":         room,
                        "day":          day,
                        "start_time":   start_time,
                        "end_time":     end_time,
                        "was_time":     was_time,
                    })

                # Clear buffer and move index to the line after details
                buffer = []
                i = j
            else:
                # No code in this line – add to buffer (subject prefix)
                buffer.append(line)
                i += 1

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
                # Identify header row (must contain a day name)
                header = table[0] if table else []
                if not any(d in str(header) for d in DAYS):
                    continue

                # Map column index → day name
                day_map = {}
                for col_idx, cell in enumerate(header):
                    if cell:
                        for day in DAYS:
                            if day in cell:
                                day_map[col_idx] = day
                                break

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
    # UPDATED to the new file name
    PDF_PATH = "public/uploads/IT Class Timetable Spring 2026 V5-A (Ramadan).pdf"
    OUTPUT   = "timetable.json"

    records = parse_timetable(PDF_PATH)

    with open(OUTPUT, "w", encoding="utf-8") as f:
        json.dump(records, f, indent=4, ensure_ascii=False)

    print(f"Parsed {len(records)} class records -> {OUTPUT}")