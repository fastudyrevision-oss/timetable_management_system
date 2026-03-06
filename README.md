# Timetable & Room Management System

A comprehensive web-based solution for managing university timetables, room arrangements, and student societies. This system features a robust PDF parser, multi-role access control, and advanced scheduling tools.

## 🚀 Key Features

### 📅 Timetable Management
- **PDF Parser**: Automatically extract timetable data from complex PDF schedules using `pdfplumber` and regular expressions.
- **24-Hour Scheduling**: Fully supports 24-hour time formats for accurate room and teacher availability filtering.
- **Combined Classes**: Intelligent handling of multi-section and combined classes, ensuring every student sees their schedule.
- **Conflict Detection**: Integrated arrangement engine to prevent room and teacher double-booking.

### 🏛️ Society Portal
- **Society Profiles**: Dedicated pages for department societies (e.g., Event Management Society).
- **Logo Management**: Professional **Zoom & Crop** tool for society presidents to perfectly fit their official logos.
- **Member & Event Tracking**: Manage society teams, schedule events, and post news updates.

### 🔐 Administration & Security
- **Multi-Role Access**: Specific dashboards for Admins, Class Representatives (CR), GRS, and Society Presidents.
- **Admin Approval**: Secure signup workflow requiring administrator approval before account activation.
- **Audit Logs**: Track all critical system actions for transparency and security.

### 📊 Data Tools
- **Export Options**: Export timetable data to JSON and CSV formats for external use.
- **Room Availability**: Real-time search for free rooms based on custom time ranges.

## 🛠️ Technical Stack

- **Backend**: PHP 8.x (MVC Architecture)
- **Database**: MySQL / MariaDB
- **Frontend**: Vanilla CSS, Bootstrap 5, Bi-icons
- **Parsing**: Python 3.x with `pdfplumber`
- **Libraries**: 
  - `smalot/pdfparser` (PHP)
  - `Cropper.js` (Image Manipulation)
  - `Composer` (PHP Dependency Manager)

## 📦 Installation

### 1. Requirements
- PHP 8.0+
- MySQL
- Python 3.8+ (for PDF parsing)
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

## 📂 Project Structure

- `public/`: Web entry point and assets.
- `src/`: Core application logic (Controllers, Models, Views).
- `config/`: Database and system configurations.
- `python/`: Scripts for PDF data extraction.
- `uploads/`: Storage for society logos, event posters, and user pictures.

## 📄 License
This project is licensed under the MIT License.
