Apps Square Backend Task
A Laravel-based leave management system for handling employee leave requests across multiple departments.

## Features

- Multi-department employee management

- Role-based permissions (Employee, Manager, HR)

- Complete leave request workflow (Submit → Manager Approval → HR Approval)

- Email notifications for request status changes

- RESTful API with proper validation and authorization

# Clone the repository
- git clone
- cd apps-square-backend-task

# Install dependencies
composer install

# Configure environment
- cp .env.example .env
- php artisan key:generate

# Set up database
- php artisan migrate
- php artisan db:seed

## After installation, the system comes with pre-configured users:

- Admin: admin@example.com (password: password)
- User: hr@example.com (password: password)

## API Documentation
- Use the included Postman collection to explore all available endpoints:
    - backend task.postman_collection.json

## Testing
- Run the test suite to ensure everything is working correctly:
    - php artisan test

## Contact
- If you have any questions or encounter issues, please don't hesitate to contact me:
    - Email: geo.mahmoudtaha@gmail.com
