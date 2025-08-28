# Adsindia - PHP CRUD Operations

A complete PHP CRUD (Create, Read, Update, Delete) application with a modern web interface and REST API.

## Features

✅ **Complete CRUD Operations**
- Create (Add) new users
- Read (View) all users and individual users
- Update (Edit) existing users
- Delete users with confirmation

✅ **Modern Web Interface**
- Responsive design for all devices
- Beautiful gradient UI with smooth animations
- Form validation and user feedback
- Search functionality
- Real-time form enhancements

✅ **REST API**
- JSON-based API endpoints
- Support for all CRUD operations
- Proper HTTP status codes
- CORS enabled for cross-origin requests

✅ **Security Features**
- SQL injection prevention using PDO prepared statements
- Input validation and sanitization
- Email uniqueness validation
- Protected configuration files

## Project Structure

```
/workspace/
├── config/
│   └── database.php          # Database configuration
├── classes/
│   └── User.php             # Main CRUD class
├── assets/
│   ├── style.css            # Modern CSS styling
│   └── script.js            # JavaScript enhancements
├── index.php                # Main web interface
├── api.php                  # REST API endpoints
├── database.sql             # Database structure and sample data
├── .htaccess               # Apache configuration
└── README.md               # This file
```

## Setup Instructions

### 1. Database Setup
```sql
-- Import the database structure
mysql -u root -p < database.sql
```

### 2. Configuration
Update database credentials in `config/database.php`:
```php
private $host = 'localhost';
private $dbname = 'adsindia_db';
private $username = 'root';
private $password = '';
```

### 3. Web Server
- Place files in your web server directory
- Ensure mod_rewrite is enabled for Apache
- Make sure PHP has PDO MySQL extension enabled

## Usage

### Web Interface
Access `index.php` in your browser to use the complete CRUD interface:
- Add new users with the form
- View all users in a responsive table
- Edit users by clicking the edit button
- Delete users with confirmation
- Search users by name or email

### REST API
The API supports standard HTTP methods:

**Get all users:**
```
GET /api.php?action=read
```

**Get user by ID:**
```
GET /api.php?action=read&id=1
```

**Search users:**
```
GET /api.php?action=search&q=john
```

**Create user:**
```
POST /api.php?action=create
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "1234567890",
    "address": "123 Main St"
}
```

**Update user:**
```
PUT /api.php?action=update&id=1
Content-Type: application/json

{
    "name": "John Smith",
    "email": "johnsmith@example.com",
    "phone": "0987654321",
    "address": "456 Oak Ave"
}
```

**Delete user:**
```
DELETE /api.php?action=delete&id=1
```

## Features Breakdown

### CRUD Operations
1. **Create (Add)** - Add new users with validation
2. **Read (View)** - Display users in a table with search
3. **Update (Edit)** - Modify existing user data
4. **Delete** - Remove users with confirmation

### Security
- PDO prepared statements prevent SQL injection
- Input validation and sanitization
- Email uniqueness checking
- Protected directories via .htaccess

### User Experience
- Responsive design works on all devices
- Real-time form validation
- Auto-hiding success/error messages
- Smooth animations and transitions
- Search with debouncing
- Loading states for better feedback

### API Features
- RESTful design following HTTP standards
- JSON request/response format
- Proper HTTP status codes
- Error handling with descriptive messages
- CORS support for cross-origin requests

## Requirements

- PHP 7.0 or higher
- MySQL 5.6 or higher
- Apache web server with mod_rewrite
- PDO MySQL extension

## Browser Support

- Chrome/Chromium (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Internet Explorer 11+

## License

This project is open source and available under the MIT License.