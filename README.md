# CDW Admin Bolt

A comprehensive WordPress-based Back Office (BO) Admin system for managing web hosting services, domains, plugins, themes, and customer relationships.

## 📋 Table of Contents

- [Overview](#overview)
- [Features](#features)
- [System Requirements](#system-requirements)
- [Installation](#installation)
- [Project Structure](#project-structure)
- [Core Modules](#core-modules)
- [Configuration](#configuration)
- [Usage](#usage)
- [API Documentation](#api-documentation)
- [Development](#development)
- [Contributing](#contributing)
- [License](#license)

## 🎯 Overview

CDW Admin Bolt is a custom-built administrative system built on WordPress core, designed specifically for managing web hosting services, domain management, customer billing, and support ticketing. The system provides a complete solution for hosting providers to manage their business operations efficiently.

## ✨ Features

### 🏢 Business Management
- **Customer Management**: Complete customer lifecycle management
- **Billing & Payments**: Integrated billing system with multiple payment methods (MoMo, Bank Transfer, Cash)
- **Domain Management**: Domain registration, DNS management, and record updates
- **Hosting Services**: Web hosting package management and provisioning
- **VPS Management**: Virtual Private Server administration

### 🛠️ Technical Features
- **Plugin Management**: WordPress plugin distribution and licensing
- **Theme Management**: Custom theme deployment and management
- **Email Services**: Email hosting and management
- **Ticket System**: Customer support ticketing system
- **Notification System**: Real-time notifications and alerts
- **Financial Reporting**: Comprehensive financial analytics and reporting

### 🔒 Security & Authentication
- **User Authentication**: Secure login system with role-based access
- **Permission Management**: Granular permission control
- **License Management**: Software licensing and validation
- **Data Encryption**: Secure data handling and storage

## 🖥️ System Requirements

- **PHP**: 7.4 or higher
- **WordPress**: 5.0 or higher
- **MySQL**: 5.7 or higher
- **Web Server**: Apache/Nginx
- **Extensions**: 
  - GD Library (for QR code generation)
  - cURL
  - JSON
  - MySQLi/PDO

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone [repository-url] cdw_admin_bolt
   cd cdw_admin_bolt
   ```

2. **Configure WordPress**
   - Ensure WordPress core is properly installed
   - Place the CDW Admin Bolt files in your WordPress installation

3. **Database Setup**
   - Import required database tables
   - Configure database connection in `core/SQLServerConnection.php`

4. **Configuration Files**
   - Update `module.json` with your module configurations
   - Configure `menu.json` for navigation structure
   - Set up `permission.json` for user roles and permissions

5. **File Permissions**
   ```bash
   chmod 755 uploads/
   chmod 644 *.json
   ```

## 📁 Project Structure

```
cdw_admin_bolt/
├── assets/                     # Static assets (CSS, JS, images)
├── component/                  # Reusable UI components
├── core/                      # Core system files
│   ├── ajax/                  # AJAX handlers
│   ├── qrcode/               # QR code generation library
│   └── uuid/                 # UUID generation utilities
├── header/                    # Header components and navigation
├── modules/                   # Main application modules
│   ├── client/               # Client-facing modules
│   ├── dashboard/            # Dashboard and analytics
│   ├── finance/              # Financial management
│   ├── manage/               # Administrative management
│   ├── notification/         # Notification system
│   └── ticket/               # Support ticket system
├── templates/                 # Email and UI templates
└── uploads/                   # File upload directory
```

## 🔧 Core Modules

### Client Management (`modules/client/`)
- **Billing**: Invoice generation and payment processing
- **Cart**: Shopping cart functionality
- **Domain**: Domain registration and management
- **Email**: Email service provisioning
- **Hosting**: Web hosting service management
- **Plugins/Themes**: Software distribution

### Financial Management (`modules/finance/`)
- **Payment Processing**: Multiple payment gateway integration
- **Receipt Management**: Automated receipt generation
- **Financial Reporting**: Revenue and expense tracking
- **Finance Categories**: Transaction categorization

### Administrative Management (`modules/manage/`)
- **Customer Management**: Customer database and profiles
- **Service Management**: Hosting, domain, email service administration
- **Plugin/Theme Management**: Software catalog management
- **Reporting**: Comprehensive business reporting

### Support System (`modules/ticket/`)
- **Ticket Management**: Customer support ticket system
- **Priority Handling**: Ticket prioritization and routing
- **Status Tracking**: Real-time ticket status updates

## ⚙️ Configuration

### Menu Configuration (`menu.json`)
Define the administrative menu structure and navigation hierarchy.

### Module Configuration (`module.json`)
Configure available modules and their settings.

### Permission System (`permission.json`)
Set up user roles and access permissions for different system areas.

### Core Functions
- `core/function-constant.php`: System constants and configuration
- `core/function-api-*.php`: External API integrations
- `core/encryption.php`: Data encryption utilities

## 📖 Usage

### Admin Dashboard
Access the admin dashboard at `/wp-admin/` with appropriate credentials.

### Client Portal
Customers can access their services through the client portal interface.

### API Integration
The system provides AJAX endpoints for:
- Customer management
- Billing operations
- Service provisioning
- Notification handling

### Payment Processing
Integrated payment methods:
- **MoMo**: Vietnamese mobile payment
- **Bank Transfer**: Direct bank transfers
- **Cash**: Manual cash payments

## 🔌 API Documentation

### AJAX Endpoints

#### Client Operations
- `core/ajax/client/billing.php`: Billing operations
- `core/ajax/client/cart.php`: Shopping cart management
- `core/ajax/client/domain.php`: Domain operations
- `core/ajax/client/hosting.php`: Hosting service management

#### Management Operations
- `core/ajax/manage/customer.php`: Customer management
- `core/ajax/manage/manage-domain.php`: Domain administration
- `core/ajax/manage/report.php`: Report generation

#### Financial Operations
- `core/ajax/finance/payment.php`: Payment processing
- `core/ajax/finance/receipt.php`: Receipt management

## 🛠️ Development

### Adding New Modules
1. Create module directory in `modules/`
2. Add `init.php` for module initialization
3. Update `module.json` configuration
4. Add necessary AJAX handlers in `core/ajax/`

### Custom Post Types
Register new post types in `core/register-post-type.php`

### Email Templates
Create custom email templates in `templates/email/`

### UI Components
Add reusable components in `component/` directory

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/new-feature`)
3. Commit your changes (`git commit -am 'Add new feature'`)
4. Push to the branch (`git push origin feature/new-feature`)
5. Create a Pull Request

### Coding Standards
- Follow WordPress coding standards
- Use proper PHP documentation
- Maintain consistent file structure
- Test all AJAX endpoints

## 📄 License

This project is proprietary software developed for CDW Admin Bolt system. All rights reserved.

## 🆘 Support

For technical support and documentation:
- Check the inline code documentation
- Review module-specific `init.php` files
- Consult the permission and configuration JSON files

## 🔄 Version History

- **Current Version**: Based on WordPress core integration
- **Features**: Complete hosting management solution
- **Modules**: 20+ integrated modules for business operations

---

**Note**: This system is specifically designed for web hosting service providers and requires proper WordPress installation and configuration. Ensure all dependencies are met before deployment.
