# Timetable & Room Management System

A comprehensive web-based solution for managing university timetables, room arrangements, and student societies. This system features a robust PDF parser, multi-role access control, and advanced scheduling tools.

## 🚀 Key Features

### 📅 Timetable & Arrangement Engine
- **PDF Parser**: Automatically extract timetable data from complex PDF schedules using `pdfplumber` and intelligent regex pattern matching.
- **Manual Editing**: Administrators can create, edit, and delete class slots directly from the dashboard.
- **Arrangement Tools**: Drag-and-drop slot swapping and class status management (Cancel/Restore).
- **24-Hour Scheduling**: Full support for 24-hour time formats ensuring accurate conflict detection.
- **Conflict Detection**: Real-time checking to prevent room and teacher double-booking.

### 🏛️ Society Portal
- **Society Profiles**: Dedicated public pages for department societies with dynamic content.
- **Enhanced Management**: Society Presidents can manage their own teams, post news updates, and schedule upcoming events.
- **Advanced Logo Tool**: Integrated **Zoom & Crop** feature (via `Cropper.js`) for perfect society branding.
- **Profile Customization**: President-specific dashboards for managing personal profiles and society details.

### 🔐 Administration & Permissions
- **Multi-Role Access**: Tailored experiences for Admins, Class Representatives (CR/GR), and Society Presidents.
- **User Approval Workflow**: Secure signup process requiring administrator verification before account activation.
- **Subject Management**: Dedicated tools for merging duplicate subjects and managing academic data.

### 📊 Advanced Search & Tools
- **Granular Availability**: Search for free rooms and teachers using precise custom time ranges.
- **Export Capabilities**: Download timetable data in JSON and CSV formats for external reporting.

## 🖥️ Key Pages & Functionality

### 🔐 Multi-Role Dashboards
- **Admin Dashboard**: A high-level overview of system usage, user approval notifications, and quick access to data management tools.
- **Society President Dashboard**: Exclusive portal for managing society-specific content—edit profiles, manage team members, and publish news/events.
- **CR & GRS Views**: Specialized interfaces for student representatives to view and manage class-specific academic data.

### 📅 Core Timetable Views
- **Public Timetable**: A responsive, searchable view for students and faculty to check weekly schedules.
- **Admin Timetable Manager**: An interactive grid featuring the **Arrangement Engine**, allowing admins to drag-and-drop slots and edit class details.
- **Faculty Search**: A dedicated page for students to find faculty information and departmental office hours.

### 🏛️ Society Portal
- **Societies Listing**: A community page showcasing all registered department societies.
- **Society Detail Pages**: Dynamic pages featuring society descriptions, logos, current event posters, and news feeds.

## 🛠️ Technical Stack

- **Backend**: PHP 8.x (Custom MVC Architecture)
- **Database**: MySQL / MariaDB
- **Frontend**: Vanilla CSS, Bootstrap 5, Bi-icons, Cropper.js
- **Parsing**: Python 3.8+ with `pdfplumber`
- **Dependency Management**: Composer (PHP)

## 📂 Project Structure

- `public/`: Web entry point and public assets.
  - `uploads/`: Organized storage for society logos, event posters, and user pictures.
- `src/`: Core application logic.
  - `Controllers/`: Business logic handlers (Academic, Arrangement, Society, etc.).
  - `Models/`: Database interaction layers.
  - `Services/`: Heavy-duty logic (ArrangementEngine, PdfParserService).
  - `Views/`: Template files for Admin and Public interfaces.
- `config/`: System and database configurations.
- `python/`: Scripts for advanced PDF data extraction.

## 👥 Roles & Permissions

| Role | Permissions |
| :--- | :--- |
| **Admin** | Full system control: User approval, Timetable parsing/clearing, Global settings. |
| **Society President** | Manage society profile, news, events, and team members. |
| **CR / GR** | Access to specialized academic views and batch-specific timetable management. |
| **Public User** | View timetables, faculty profiles, and society news. |

## 📦 Installation

### 1. Requirements
- PHP 8.0+
- MySQL
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

## 📄 License
This project is licensed under the MIT License.
