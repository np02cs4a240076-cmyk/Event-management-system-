# 🏆 College Sports Event Management System

**Workshop 8** - A complete PHP + MySQL web application for managing college sports events with modern MVC architecture.

---

## 📋 Features

### Authentication
- ✅ User Registration with password strength validation
- ✅ Secure Login with password hashing (bcrypt)
- ✅ Logout functionality
- ✅ Session-based authentication
- ✅ CSRF token protection

### Sports Events Management
- ✅ Create, Read, Update, Delete (CRUD) sports events
- ✅ Live AJAX search functionality
- ✅ Filter by sport category
- ✅ Event capacity management
- ✅ Booking status tracking (Open/Full)

### Participant Registration
- ✅ Register for events with team details
- ✅ View registered participants
- ✅ Track attendance
- ✅ Delete registrations

### Security Features
- ✅ PDO Prepared Statements (SQL Injection prevention)
- ✅ CSRF Token Validation
- ✅ XSS Prevention (htmlspecialchars)
- ✅ Password Hashing (password_hash)
- ✅ Protected routes with authentication middleware

---

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| PHP 7.4+ | Backend Language |
| MySQL | Database |
| Plain PHP | Templating (MVC) |
| PDO | Database Abstraction |
| CSS3 | Styling |
| JavaScript (ES6) | Frontend Interactivity |
| Font Awesome | Icons |

---

## 📁 Project Structure

```
event management/
├── app/
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── ParticipantController.php
│   │   └── SportEventController.php
│   ├── models/
│   │   ├── Attendee.php
│   │   ├── Participant.php
│   │   ├── SportEvent.php
│   │   └── User.php
│   └── views/
│       ├── auth/
│       │   ├── dashboard.php
│       │   ├── login.php
│       │   └── register.php
│       ├── layouts/
│       │   └── main.php
│       ├── participants/
│       │   └── register.php
│       └── sports/
│           ├── attend.php
│           ├── create.php
│           ├── edit.php
│           ├── index.php
│           └── show.php
├── public/
│   ├── assets/
│   │   ├── css/
│   │   │   └── style.css
│   │   └── js/
│   │       └── app.js
│   └── index.php            # Front controller
├── src/
│   ├── View.php             # View rendering service
│   ├── Router.php           # Routing dispatcher
│   └── Session.php          # Session management
├── vendor/                   # Composer autoloader
├── composer.json
├── composer.lock
├── db.php                   # Database configuration
└── README.md
```

---

## 🚀 Installation

### Prerequisites
- XAMPP (or any PHP + MySQL environment)
- Composer
- PHP 7.4 or higher

### Step 1: Clone/Copy Project
Copy the project folder to your XAMPP htdocs directory:
```
C:\xampp\htdocs\event management\
```

### Step 2: Install Dependencies
```bash
cd "C:\xampp\htdocs\event management"
composer install
```

### Step 3: Configure Database
1. Start XAMPP (Apache + MySQL)
2. Open phpMyAdmin: http://localhost/phpmyadmin
3. Create a new database named `sports_event_db`

The tables will be created automatically on first run!

### Step 4: Update Database Configuration (if needed)
Edit `db.php` if your MySQL credentials differ:
```php
$host = 'localhost';
$dbname = 'sports_event_db';
$username = 'root';
$password = '';  // Default XAMPP password is empty
```

### Step 5: Run the Application
Open in browser:
```
http://localhost/event%20management/public/
```

---

## 📊 Database Schema

### Users Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary Key |
| name | VARCHAR(255) | Full name |
| email | VARCHAR(255) UNIQUE | Email address |
| password | VARCHAR(255) | Hashed password |
| created_at | TIMESTAMP | Registration date |

### Sport Events Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary Key |
| title | VARCHAR(255) | Event title |
| description | TEXT | Event description |
| sport_type | VARCHAR(100) | Sport category |
| event_date | DATE | Event date |
| event_time | TIME | Event time |
| venue | VARCHAR(255) | Event location |
| max_capacity | INT | Maximum participants |
| user_id | INT | Creator (FK → users.id) |
| created_at | TIMESTAMP | Creation date |

### Participants Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary Key |
| sport_event_id | INT | Event (FK → sport_events.id) |
| name | VARCHAR(255) | Participant name |
| email | VARCHAR(255) | Contact email |
| phone | VARCHAR(20) | Phone number |
| team_name | VARCHAR(255) | Team name (optional) |
| created_at | TIMESTAMP | Registration date |

### Attendees Table
| Column | Type | Description |
|--------|------|-------------|
| id | INT AUTO_INCREMENT | Primary Key |
| event_id | INT | Event (FK → sport_events.id) |
| user_id | INT | User (FK → users.id) |
| created_at | TIMESTAMP | Attendance date |

---

## 🔐 Routes

### Public Routes
| Route | Method | Description |
|-------|--------|-------------|
| `?route=auth.login` | GET/POST | Login page |
| `?route=auth.register` | GET/POST | Registration page |
| `?route=auth.logout` | GET | Logout |

### Protected Routes (Requires Login)
| Route | Method | Description |
|-------|--------|-------------|
| `?route=auth.dashboard` | GET | User dashboard |
| `?route=sports.index` | GET | List all events |
| `?route=sports.create` | GET/POST | Create new event |
| `?route=sports.show&id=X` | GET | View event details |
| `?route=sports.edit&id=X` | GET/POST | Edit event |
| `?route=sports.delete&id=X` | POST | Delete event |
| `?route=sports.search` | GET | AJAX search |
| `?route=sports.attend` | GET | Attend section |
| `?route=participants.register&event_id=X` | GET/POST | Register for event |
| `?route=participants.delete&id=X` | POST | Remove registration |

---

## 🎨 UI Features

- **Modern Design**: Clean, professional interface with CSS variables
- **Responsive**: Mobile-first design with breakpoints
- **Dark Mode Ready**: CSS variables for easy theming
- **Animations**: Smooth transitions and hover effects
- **Toast Notifications**: User feedback system
- **Modal Dialogs**: Confirm actions (delete, attend)
- **Live Search**: AJAX-powered event search

---

## 🔒 Security Measures

1. **SQL Injection Prevention**: All queries use PDO prepared statements
2. **XSS Prevention**: All output escaped with `htmlspecialchars()`
3. **CSRF Protection**: Token validation on all POST requests
4. **Password Security**: bcrypt hashing with `password_hash()`
5. **Session Security**: Secure session configuration
6. **Input Validation**: Server-side validation for all inputs

---

## 📝 Usage Guide

### Creating an Account
1. Click "Sign Up" on the homepage
2. Fill in your name, email, and password
3. Password must be at least 6 characters
4. Click "Create Account"

### Creating a Sports Event
1. Login to your account
2. Click "Create Event" on dashboard or navigation
3. Fill in event details (title, sport type, date, venue, capacity)
4. Click "Create Event"

### Registering for an Event
1. Browse events on the "All Events" page
2. Click on an event to view details
3. Click "Register Now"
4. Fill in participant details
5. Submit registration

### Searching Events
1. Go to "All Events" page
2. Use the search box for live search
3. Filter by sport type using the dropdown

---

## 🐛 Troubleshooting

### "Class not found" Error
Run composer install:
```bash
composer install
```

### Database Connection Error
1. Make sure XAMPP MySQL is running
2. Verify database credentials in `db.php`
3. Create the database `sports_event_db` if it doesn't exist

### Blank Page
1. Check PHP error logs
2. Ensure cache folder has write permissions
3. Verify PHP version is 7.4+

### CSS/JS Not Loading
Make sure you're accessing via:
```
http://localhost/event%20management/public/
```

---

## 📄 License

This project is for educational purposes (Workshop 8 - College Assignment).

---

## 👨‍💻 Author

Created for Workshop 8 - PHP MVC Project

---

**Happy Coding! 🎉**
