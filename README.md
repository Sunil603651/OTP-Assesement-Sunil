# OTP Verification System

This project is a Laravel-based OTP (One-Time Password) Verification System that allows users to request an OTP via email and verify it. The system includes a user-friendly interface for requesting and verifying OTPs, with features like email persistence, loading spinners, and a modern design.
Prerequisites

Before setting up the project, ensure you have the following installed on your system:
PHP (>= 8.1)
Composer (Dependency manager for PHP)
MySQL (or another database supported by Laravel)
XAMPP (or another local server environment, optional for Windows users)
Git (for cloning the repository)

## Installation

[Include step-by-step installation instructions]
Follow these steps to set up the project on your local machine:

Clone the Repository:

Clone the project repository to your local machine:git clone https://github.com/Sunil603651/OTP-Assesement-Sunil.git
cd otp-verification-system

Replace https://github.com/Sunil603651/OTP-Assesement-Sunil.git with the actual repository URL.

Install Dependencies:

Install PHP dependencies using Composer:composer install

Set Up Environment File:

Copy the .env.example file to .env:cp .env.example .env

Open .env in a text editor and configure your database settings:DB_CONNECTION=mysql
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=otp_assessment
DB_USERNAME=root
DB_PASSWORD=

Configure email settings (see "Email Configuration" below).

Generate Application Key:
Generate a new application key for Laravel:php artisan key:generate
Run Migrations:

Run database migrations to create the necessary tables (users, otps, sessions, etc.):php artisan migrate

Note: The otps table has been modified to include an email column and make user_id nullable, as the system no longer requires emails to exist in the users table.

Email Configuration:

Update your .env file to use Gmail:MAIL_MAILER=smtp
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=sunilb1906@gmail.com
MAIL_PASSWORD="nylw iwgh wwzd ayvl"
MAIL_ENCRYPTION=tls
MAIL_FROM_NAME="${APP_NAME}"

Important Notes for Email Sending:
If you are using an office network and Gmail access is blocked, emails will not be sent. In this case, try using your mobile network by connecting your system to your mobile hotspot and then attempt to send the email again.
If the OTP email is not visible in your inbox, check your spam folder.

Compile Frontend Assets (Optional):
If using Vite for frontend assets (e.g., CSS, JavaScript), compile them:npm run dev # For development

Request an OTP:

Open your browser and go to http://127.0.0.1:8000/otp/request.
Enter a valid email address (e.g., sunil123@gmail.com) and click "Send OTP".
Check the spam or inbox folder of your email provider to view the email containing the OTP.

Verify the OTP:

You’ll be redirected to the verification page (/otp/verify).
Enter the 6-digit OTP from the email into the input fields.
If the OTP is correct, you’ll be redirected to the success page (/otp/success).
If the OTP is incorrect, an error message ("Invalid OTP") will be displayed, and the email field will remain populated.

# For production

Start the Development Server:
Start Laravel’s development server:php artisan serve
Access the application at http://127.0.0.1:8000.

## Testing

Expired OTP: Wait 15 minutes (OTP expiry time) and try verifying the OTP. You should see an error: "No valid OTP found or OTP has expired."
Invalid Email: Enter an invalid email format (e.g., invalid-email) on the request page. You should see a validation error.
Paste OTP: Copy the 6-digit OTP from the email and paste it into the first OTP input field on the verification page. The system will automatically split the digits across the input fields and submit the form.

## Assumptions

The following assumptions were made during development:

Development Environment:

The project is set up on a local machine using XAMPP (Windows) with PHP 8.1 or higher.
MySQL is used as the database, as specified in the .env file (DB_CONNECTION=mysql).

## Additional Features

Email Sending:
For local development, MailHog is used to capture emails, as external SMTP servers (e.g., Gmail) may encounter SSL certificate verification issues.
In production, a transactional email service (e.g., SendGrid, Postmark, or AWS SES) should be used instead of Gmail SMTP.

User Base:
The system does not require users to be registered in the users table to receive an OTP. Any valid email address can be used to request an OTP.

OTP Expiry:
OTPs expire after 15 minutes, as configured in OTPController.php.

Network Access:
The system assumes the user has internet access to send emails. If Gmail access is blocked (e.g., on an office network), the user must switch to a mobile network.

Additional Features
The following features were implemented to enhance the user experience:

Modern UI Design:
The OTP request, verification, and success pages have a modern design with a light peach background (#FFE4E1), rounded corners, and a card-style layout.
The "Send OTP" and "Request Another OTP" buttons include a + icon and a green color (#38A169) with a hover effect.

Loading Spinner:
A loading spinner is displayed on the request page when the form is submitted, improving the user experience by indicating that the system is processing the request.

Email Persistence:
On the verification page, the email field remains populated even after an incorrect OTP submission, preventing the user from having to re-enter their email.

OTP Auto-Submit:
The verification page automatically submits the form when all 6 OTP digits are entered, either by typing or pasting the OTP.

Paste Support:
Users can paste the 6-digit OTP into the first input field on the verification page, and the system will automatically distribute the digits across the input fields and submit the form.

Error Handling:
Clear error messages are displayed for invalid emails, incorrect OTPs, and expired OTPs.
Alerts are dismissible using Bootstrap’s alert-dismissible class.

Responsive Design:
The UI is responsive, using Bootstrap’s grid system (col-md-6 col-sm-10), ensuring the forms look good on both desktop and mobile devices.

## Technical Decisions

The following technical decisions were made during development:

Laravel Framework:
Laravel was chosen for its robust ecosystem, built-in features (e.g., Blade templating, Eloquent ORM, and mailing system), and ease of development.

Database Schema:
The otps table was modified to include an email column and make user_id nullable, allowing OTPs to be sent to any valid email address, not just those in the users table.
OTPs are stored with a hashed code (using Hash::make) and an expiry time (expired_at), ensuring security and automatic cleanup of expired OTPs.

Email Sending:
Initially, Gmail SMTP was used for email sending, but due to persistent SSL certificate verification issues (error:0A000086:SSL routines::certificate verify failed), the project switched to MailHog for local testing.
In production, a transactional email service (e.g., SendGrid) is recommended for reliability and deliverability.

Frontend Design:
Bootstrap was used for styling and responsiveness, with custom CSS for rounded corners, colors, and hover effects.
The design was inspired by a provided image, featuring a light peach background, a card-style form, and a green button with a + icon.

OTP Verification:
The OTP is a 6-digit code, generated using str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT).
The verification page uses 6 separate input fields for the OTP digits, with JavaScript for auto-focusing the next field, paste support, and auto-submission.

Rate Limiting:
Rate limiting was applied to the OTP verification route (otp.verify) in routes/web.php using Laravel’s throttle middleware (throttle:5,1), limiting users to 5 attempts per minute to prevent abuse.

Security:
CSRF protection is enabled using Laravel’s @csrf directive in forms.
OTP codes are hashed in the database using Laravel’s Hash::make to prevent unauthorized access.

Contributing
Contributions are welcome! Please fork the repository, create a new branch, and submit a pull request with your changes.
License
This project is licensed under the MIT License. See the LICENSE file for details.
