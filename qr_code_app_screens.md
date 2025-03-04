# QR Code Generator Application Screens

This document outlines the 7 main screens for a QR code generator application that will be built using Laravel 12, Filament, Blueprint, and Tailwind CSS. A key feature of this system is that while QR codes themselves won't change, the content associated with them can be modified.

## 1. Login Screen

- **Purpose**: Authentication entry point for the system
- **URL**: `/login`
- **Elements**:
  - Logo: Dynamic QRCode logo at the top
  - Input fields:
    - Username (text field with user icon)
    - Password (password field with lock icon)
  - Checkbox: "Remember Me" option
  - Button: "Sign In" (primary color, full width)
  - Message display area for errors/notifications
- **Database Requirements**:
  - Users table with fields for: id, username, password (hashed), remember_token, user_type
  - User types: super admin, admin
- **Behavior**:
  - Validate credentials
  - Redirect to Dashboard upon successful login
  - Show error messages for failed attempts
  - Support for "Remember Me" functionality

## 2. Dashboard Screen

- **Purpose**: Central hub showing QR code statistics and activity
- **URL**: `/dashboard`
- **Elements**:
  - Navigation sidebar with links to other sections
  - Header with user account info and logout option
  - Statistics cards:
    - Total QR codes (number with QR icon)
    - Dynamic QR codes (number with QR icon)
    - Static QR codes (number with QR icon)
    - Total Scans (number with graph icon)
  - Charts/Graphs:
    - QR codes created in the last week (line graph showing dynamic vs static)
    - Scan statistics (line graph showing scan frequency)
  - Recent activity section
- **Database Requirements**:
  - QR code tables with counters for total, dynamic, static
  - Scan logs with timestamps
- **Behavior**:
  - Auto-refresh statistics periodically
  - Interactive graphs with hover details
  - Click on any statistic to go to detailed view

## 3. Dynamic QR Codes List Screen

- **Purpose**: Display and manage all dynamic QR codes
- **URL**: `/dynamic-qr-codes`
- **Elements**:
  - Search bar for filtering QR codes
  - Dropdown filters for ID and Description
  - "Add new" button (green, positioned top-right)
  - Table with columns:
    - Checkbox for bulk selection
    - ID
    - Owner
    - Filename
    - Unique redirect identifier
    - URL
    - QR code (image)
    - Scan count
    - Status (enable/disable)
    - Operations (edit/delete/download buttons)
  - Pagination controls
- **Database Requirements**:
  - Dynamic QR codes table with: id, user_id, filename, redirect_identifier, url, scan_count, status
  - Relationships to user table
- **Behavior**:
  - Sortable columns
  - Quick status toggle
  - Download QR as image
  - Bulk actions for selected items
  - Edit and delete functionality

## 4. Add Dynamic QR Code Screen

- **Purpose**: Interface for creating new dynamic QR codes
- **URL**: `/dynamic-qr-codes/create`
- **Elements**:
  - Section header: "Enter the requested data"
  - Form fields:
    - Foreground color picker (with hex value input)
    - Background color picker (with hex value input)
    - Precision dropdown (L - Smallest, etc.)
    - Size dropdown (100px, etc.)
    - URL input field (required)
    - Redirect identifier field (auto-generated, read-only)
    - Filename input (required)
    - Format dropdown (PNG, etc.)
  - Submit button
- **Database Requirements**:
  - Same as Dynamic QR codes table
  - Additional fields for QR appearance: foreground_color, background_color, precision, size
- **Behavior**:
  - Validate URL format
  - Auto-generate unique redirect identifier
  - Real-time QR preview as options change
  - Save and redirect to list on success

## 5. Add Static QR Code Screen

- **Purpose**: Interface for creating new static QR codes
- **URL**: `/static-qr-codes/create`
- **Elements**:
  - Tab navigation for content types:
    - Text
    - Email
    - Phone
    - SMS
    - WhatsApp
    - Skype
    - Location
    - Vcard
    - Event
    - Bookmark
    - Wifi
    - Paypal
    - Bitcoin
    - 2FA
  - Form fields (varying based on selected tab):
    - Foreground color picker
    - Background color picker
    - Precision dropdown
    - Size dropdown
    - Filename input (required)
    - Format dropdown
    - Content-specific fields (Text field for plain text, etc.)
  - Submit button
- **Database Requirements**:
  - Static QR codes table with: id, user_id, filename, content_type, content, foreground_color, background_color, precision, size, format
- **Behavior**:
  - Dynamic form that changes based on selected content type
  - Validate inputs based on content type
  - Real-time QR preview
  - Save and redirect to list on success

## 6. Users Management Screen

- **Purpose**: List and manage system users
- **URL**: `/users`
- **Elements**:
  - Search bar for filtering users
  - Dropdown filters for ID and Description
  - "Add new" button (green, positioned top-right)
  - Table with columns:
    - ID
    - Username
    - Type (admin/super admin)
    - Actions (edit/delete buttons)
  - Pagination controls
- **Database Requirements**:
  - Users table as specified in Login screen
- **Behavior**:
  - Filter and search functionality
  - Edit user details
  - Delete users (with confirmation)
  - Restrict access to super admin only

## 7. Add User Screen

- **Purpose**: Interface for creating new system users
- **URL**: `/users/create`
- **Elements**:
  - Section header: "Enter the requested data"
  - Form fields:
    - Username input with user icon (required)
    - Password input with lock icon (required)
    - User type radio buttons:
      - Super admin
      - Admin
  - Submit button
- **Database Requirements**:
  - Users table as specified previously
- **Behavior**:
  - Validate username uniqueness
  - Password strength validation
  - Create user and redirect to list on success
  - Accessible only to super admin users

## Technical Notes

1. **QR Code Functionality**:
   - QR codes themselves won't change, but their associated content can be modified
   - For dynamic QR codes, redirect system should use the unique identifier to point to current URL
   - Static QR codes encode their content directly

2. **Security Considerations**:
   - Role-based access control (super admin vs admin)
   - Password hashing and secure authentication
   - Validation of all inputs
   - Protection against SQL injection and XSS

3. **Database Structure**:
   - Users: id, username, password, remember_token, user_type, created_at, updated_at
   - DynamicQRCodes: id, user_id, filename, redirect_identifier, url, foreground_color, background_color, precision, size, scan_count, status, created_at, updated_at
   - StaticQRCodes: id, user_id, filename, content_type, content, foreground_color, background_color, precision, size, format, created_at, updated_at
   - ScanLogs: id, qr_code_id, qr_code_type, timestamp, ip_address, user_agent

4. **Implementation Details**:
   - Use Laravel 12 for backend
   - Filament for admin panel
   - Blueprint for database structure
   - Tailwind CSS for styling
   - Consider using a QR code generation library compatible with Laravel
