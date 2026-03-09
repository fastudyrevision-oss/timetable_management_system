# Timetable & Room Management System

A comprehensive web-based solution for managing university timetables, room arrangements, and student societies. This system features a robust PDF parser, multi-role access control, and advanced scheduling tools.

## Key Features

### Timetable & Arrangement Engine
- **PDF Parser**: Automatically extract timetable data from complex PDF schedules using `pdfplumber` and intelligent regex pattern matching.
- **Manual Editing**: Administrators can create, edit, and delete class slots directly from the dashboard.
- **Arrangement Tools**: Drag-and-drop slot swapping and class status management (Cancel/Restore).
- **24-Hour Scheduling**: Full support for 24-hour time formats ensuring accurate conflict detection.
- **Conflict Detection**: Real-time checking to prevent room and teacher double-booking.

### Advanced Gallery
- **Event Grouping**: Media items are automatically grouped by event title for a structured viewing experience.
- **Media Filtering**: Dedicated filters for Photography (images) and Cinematics (videos).
- **Cinematic Slideshow**: Interactive lightbox with fade animations and dedicated media controls.
- **Responsive Grid**: Modern masonry layout that adapts to all screen sizes.

### Academic & Student Tools
- **Academic Calendar**: Integrated view for university schedules and important dates.
- **CGPA Calculator**: Advanced tool for students to track and manage their academic performance.
- **Faculty Search**: Dedicated search interface to quickly find faculty information and office hours.

### Society Portal
- **Society Profiles**: Dynamic public pages for department societies with integrated news feeds and event posters.
- **Enhanced Management**: Society Presidents can manage their teams, post news updates, and schedule upcoming events.
- **Action Links**: Support for custom registration links and "Coming Soon" event status.
- **Branding Tools**: Integrated Zoom & Crop feature (via `Cropper.js`) for society logos.

## Administration & Permissions
- **Multi-Role Access**: Tailored experiences for Admins, Class Representatives (CR/GR), and Society Presidents.
- **User Approval Workflow**: Secure signup process requiring administrator verification before account activation.
- **Profile Management**: Support for user profile pictures and account customization.
- **Subject Management**: Tools for merging duplicate subjects and managing academic data consistency.

## Technical Stack
- **Backend**: PHP 8.x (Custom MVC Architecture)
- **Database**: MySQL / MariaDB / SQLite
- **Frontend**: Vanilla CSS, Bootstrap 5, Bootstrap Icons, Cropper.js
- **Parsing**: Python 3.8+ with `pdfplumber`
- **Dependency Management**: Composer (PHP)

## Project Structure
- `public/`: Web entry point and public assets (uploads, css, js).
- `src/`: Core application logic.
  - `Controllers/`: Business logic handlers (Academic, Arrangement, Society, Gallery, etc.).
  - `Models/`: Database interaction layers.
  - `Services/`: Core engines (ArrangementEngine, PdfParserService).
  - `Views/`: Application templates for public and administrative interfaces.
- `config/`: System and database configurations.
- `python/`: Scripts for advanced PDF data extraction and processing.

## Roles & Permissions

| Role | Permissions |
| :--- | :--- |
| **Admin** | Full system control: User approval, Timetable parsing, Global settings, Gallery management. |
| **Society President** | Manage society profile, news, events, and team members. |
| **CR / GR** | Access to specialized academic views and batch-specific timetable management. |
| **Public User** | View timetables, faculty profiles, society news, and gallery. |

## Installation

### 1. Requirements
- PHP 8.0+
- MySQL or MariaDB
- Python 3.8+
- Composer

### 2. Setup Database
```sql
-- Import the database schema
mysql -u your_user -p your_db < database.sql
```

### 3. Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Python dependencies
pip install -r requirements.txt
```

### 4. Configuration
Update `config/db.php` with your database credentials.

### 5. Run Parser (Optional)
To update the system with a new PDF timetable:
```bash
python parse_timetable.py
```

## License
This project is licensed under the MIT License.
