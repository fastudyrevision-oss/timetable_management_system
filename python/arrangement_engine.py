import json
import sys
from collections import defaultdict

# Ensure UTF-8 output
sys.stdout.reconfigure(encoding='utf-8')

if len(sys.argv) < 2:
    print(json.dumps([]))
    sys.exit(1)

json_path = sys.argv[1]

try:
    with open(json_path, "r", encoding='utf-8') as f:
        timetable = json.load(f)
except Exception as e:
    print(json.dumps({"error": f"Failed to load JSON: {str(e)}"}))
    sys.exit(1)

room_schedule = defaultdict(list)
teacher_schedule = defaultdict(list)
batch_schedule = defaultdict(list)

results = []

# First pass: Build schedules to detect overlaps
for cls in timetable:
    day = cls.get("day")
    time = cls.get("time_slot")
    room = cls.get("room")
    teacher = cls.get("teacher")
    
    # We need a proper key for batch if available, usually section+semester+batch
    # Using 'semester' + 'section' as batch key for now
    batch_key = f"{cls.get('semester')}-{cls.get('section')}"

    if day and time:
        if room: room_schedule[room].append((day, time))
        if teacher: teacher_schedule[teacher].append((day, time))
        batch_schedule[batch_key].append((day, time))

# Second pass: Check conflicts
for cls in timetable:
    day = cls.get("day")
    time = cls.get("time_slot")
    room = cls.get("room")
    teacher = cls.get("teacher")
    batch_key = f"{cls.get('semester')}-{cls.get('section')}"

    status = "arranged"
    conflicts = []

    if not day or not time:
        cls["status"] = "pending"
        cls["conflicts"] = ["Missing Day/Time"]
        results.append(cls)
        continue

    # Room conflict (count occurrences of this slot in schedule)
    # If > 1, it implies multiple classes in same room at same time
    if room and room_schedule[room].count((day, time)) > 1:
         # Note: This logic marks ALL classes in that slot as conflict. 
         # Improvements: Mark only if *another* class shares it.
         status = "conflict"
         conflicts.append("Room Clash")

    # Teacher conflict
    if teacher and teacher != "TBA" and teacher_schedule[teacher].count((day, time)) > 1:
        status = "conflict"
        conflicts.append("Teacher Clash")

    cls["status"] = status
    cls["conflicts"] = conflicts

    # Suggest alternative if conflict
    if status == "conflict":
        # Mock suggestions for now - real logic needs to search `room_schedule` gaps
        cls["suggested_slots"] = [
            {"day": "Friday", "time": "11:30 - 01:00"},
            {"day": "Saturday", "time": "09:00 - 10:30"}
        ]

    results.append(cls)

print(json.dumps(results, indent=2))
