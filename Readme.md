readme_content = """
# Library Management System

This is a web-based Library Management System project, which allows users to sign up, log in, search for books, download books, and reset their passwords. The system also incorporates secure user authentication using Diffie-Hellman key exchange.

## Folder Structure

\`\`\`
LibrarySystem
|   background.jpg
|   index.html
|   
+---css
|       styles.css
|       
+---db
|       library.sql
|       
\---html
    |   download.php
    |   login.html
    |   login.php
    |   logout.php
    |   register.php
    |   reset_password.html
    |   reset_password.php
    |   reset_password_verify.php
    |   search_books.php
    |   server.php
    |   sign_up.html
    |   user_home.html
    |   
    +---books
    |       1.png
    |       2.jpg
    |       3.jpg
    |       4.jpg
    |       5.jpg
    |       Give and Take_ WHY HELPING OTHERS DRIVES OUR SUCCESS.pdf
    |       How To Win Friends and Influence People.pdf
    |       Living in the Light_ A guide to personal transformation.pdf
    |       Rich Dad Poor Dad.pdf
    |       Think And Grow Rich.pdf
    |       
    \---css
            background.jpg
            styles - Copy - Copy.css
            styles - Copy.css
            styles.css
\`\`\`

## Getting Started

Follow these instructions to set up and run the project on your local machine.

### Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (e.g., Apache, Nginx)

### Installation

1. **Clone the repository:**
   \`\`\`sh
   git clone https://github.com/Ragul2510/LibrarySystem
   cd LibrarySystem
   \`\`\`

2. **Database Setup:**
   - Create a database named "library"
   - Import the \`library.sql\` file into your MySQL "library" database to create the necessary tables.
   - Update the database configuration in \`server.php\` and other relevant PHP files.

3. **Web Server Setup:**
   - Place the project files in your web server's root directory (e.g., \`htdocs\` for XAMPP, \`www\` for WAMP).
   - Start your web server and navigate to the project directory in your browser.

### Usage

1. **Sign Up:**
   - Go to the Sign Up page and create a new account.

2. **Login:**
   - Log in using your credentials.

3. **Search for Books:**
   - Use the search functionality to find and download books.

4. **Reset Password:**
   - Follow the steps to reset your password if you forget it.
"""

# Write the content to README.md file
with open("/mnt/data/README.md", "w") as file:
    file.write(readme_content)

