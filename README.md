Apps Square Backend Task
A Laravel-based leave management system for handling employee leave requests across multiple departments.

## Task analysis 
- employees have different positions in more than one department 
- employee sumbit leave request 
- request sent via email to direct manager 
- if direct manager approve leave request 
    - request status sent via email to HR Manager 
- else if direct manager reject leave request 
    - request rejection sent via email to employee
- filter employee by departmentID 
- return user vacation requests 
- use unit tests

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

## Getting Started
- in UserSeeder change Sales Direct Manager email, HR Manager email, Sales Employee email to real emails 
to recieve notification message 

# Set up database
- php artisan migrate
- php artisan db:seed

## After installation, the system comes with pre-configured users:

- Admin: admin@example.com (password: password)
- HR Manager: hr@example.com (password: password)
- Sales Direct Manager: sales_direct_manager@example.com (password: password)
- Finance Direct Manager: finance_direct_manager@example.com (password: password)
- Sales Employee: sales_employee@example.com (password: password)
- Finance Employee: finance_employee@example.com (password: password)

## API Documentation
- Use the included Postman collection to explore all available endpoints:
    - backend task.postman_collection.json

    API Endpoints Documentation
    Authentication
    Register

    - URL: /api/register
        Method: POST
        Headers:

    Accept: application/json


    Body Parameters:

    - name: User's name (text)
    - email: User's email (text)
    - password: User password (text)
    - password_confirmation: Password confirmation (text)


    Description: Creates a new user account

    Login

    - URL: /api/login
        Method: POST
        Headers:

    Accept: application/json


    Body Parameters:

    - email: User's email (text)
    - password: User password (text)


    Description: Authenticates a user and returns an access token

    Get Current User

    - URL: /api/user
        Method: GET
        Headers:

    Accept: application/json
    Authorization: Bearer {token}


    Description: Retrieves the authenticated user's information

    Leave Requests
    Create Leave Request

    - URL: /api/leave-request
        Method: POST
        Headers:

    Accept: application/json
    Authorization: Bearer {token}


    Body Parameters:

    - leave_type_id: ID of the leave type (text)
    - department_id: ID of the department (text)
    - start_date: Start date of leave in DD-MM-YYYY format (text)
    - end_date: End date of leave in DD-MM-YYYY format (text)
    - reason: Reason for the leave request (text)


    Description: Creates a new leave request

    Update Leave Request Status

    - URL: /api/leave-requests/{id}/status
        Method: PATCH
        Headers:

    Accept: application/json
    Authorization: Bearer {token}


    Body Parameters:

    - status: New status of the leave request (e.g., "rejected") (text)


    Description: Updates the status of a specific leave request

    List Leave Requests

    - URL: /api/leave-requests
        Method: GET
        Headers:

    Accept: application/json
    Authorization: Bearer {token}


    Description: Retrieves a list of leave requests

    Employees
    List Employees

    - URL: /api/employees
        Method: GET
        Headers:

    Accept: application/json
    Authorization: Bearer {token}


    Query Parameters:

    - department_id: Optional filter by department ID


    Description: Retrieves a list of employees, optionally filtered by departmen

## Testing
- Run the test suite to ensure everything is working correctly:
    - php artisan test

## Contact
- If you have any questions or encounter issues, please don't hesitate to contact me:
    - Email: geo.mahmoudtaha@gmail.com