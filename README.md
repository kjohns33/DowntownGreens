# Downtown Greens Grant Tracking Database
## Purpose
This project is the result of a semesters' worth of collaboration among UMW students. The goal of the project was to create a
web application Downtwon Greens staff members could utilize to make it easier to apply for grants and keep track of funding
and deadlines. At-a-glance features include a web-based calendar of due dates, grant creation, annual reporting system,
material organization system, search, view, and archive functionality for grants in the system, and a notification and
messaging system.

## Authors
The Downtown Greens Grant Tracking Database is based off a previous semesters ODHS database and web application. This was
fashioned off an open source project titled "Homebase," though very little of this original code remains. The ODHS medicine
tracker in the form we received it in was authored in Fall 2023 by Garrett Moore, Artis Hart, Riley Tugeau, Julia Barnes, Ryan
Warren, and Colin Rugless.

The most recent overhaul took place in the Fall 2024 semester, transforming it into the current grant tracking database. Many
of the existing database tables were reused and many tables were added. Some functionality was reused though other functions
were created to more closely match the needs of the current users of the program. The team that made these modifications
consisted of Connor Hairfield, Janay Jackson, Kaeleen Johnson,Colby Pexton, Colin Ryan, and Cameron Zakreski

## User Types
There are two types of users (also referred to as 'roles') within the ODHS.
* Managers
* Admins

Managers have the ability to manage users, generate reports, create, edit, and view grant information, reset passwords, send
and receive messages and notifications, and manage account information.

Admins have all of the abilities that Managers have, but they cannot modify other users information.

There is also a root admin account with username 'vmsroot'. The default password for this account is 'vmsroot'. This account
has hardcoded Manager privileges but does not have a user profile. It is crucial that this account information remain the same
because it will be used to upkeep the system on the backend. This account should be used for system administration purposes
only.

## Features
Below is an in-depth list of features that were implemented within the system
* User login
* Dashboard
* User Management
  * Change own password
  * Logout
* Grants and Grant Management
  * Calendar with due dates and open dates
  * Calendar day view with due dates and open dates for the day
  * Grant search
  * Grant details page
  * Edit grant details
  * Create new grant
  * Assign grants to projects
  * Create new projects to be funded
* Reports (print-friendly)
  * Financial reports editable by beginning and end date
* Notification system, with notifications generated when
  * A new grant is created
  * A grant deadline is a month away
  * A grant deadline is a week away
  * A grant deadline is a day away
  * A report deadline is a month away
  * A report deadline is week away
  * A report deadline is a day away
  * A grant is open for application
* Grant Management
  * Create Grants
  * Edit Grants
    * Name editable
    * Funder editable
    * Status editable
    * Open Date editable
    * Due Date editable
    * Description editable
    * Type editable
    * Partners editable
    * Amount editable
    * Projects editable
    * Links editable
    * Fields editable
    * Report Dates editable
  * Delete Grants
  * Archive Grants
  * Search Grants by open date, close date, name, or funder


## Design Documentation
Several types of diagrams describing the design of the Downtown Greens Grant Tracking Database, including sequence diagrams and
use case diagrams, are available. Please contact Dr. Polack for access.

## XAMPP and "localhost" Installation
Installation:  All of these databases runs on an Apache/MySQL/PHP server.

Obtain a GitHub Token:
1. Log into GitHub using your account ID and password.
2. In the upper-right corner of any page, click your profile photo, then click Settings.
3. In the "Access" section of the sidebar, click
Emails. (if it does not say unverified then you have already verified your email)
4. Under your email address, click Resend verification email.
5. GitHub will send you an email with a link in it. After you click that link,
you'll be taken to your GitHub dashboard and see a confirmation banner.
6. In the upper-right corner of any page, click your profile photo, then
click Settings.
7. In the left sidebar, click Developer settings.
8. Now in the left sidebar, click Personal access tokens.
9. Click Generate new token.
10. Give your token a descriptive name.
11. Select the Expiration drop-down menu, and click "no expiration date"
12. Select the scopes, or permissions, you'd like to grant this token.
(To use your token to access repositories from the command line, select repo.)
13. Click Generate token.

Install Database:
1. On your phpmyadmin page, create a MySQL database "odhsmd" on your server's
localhost with user = password = "odhsmd" :  Replace the XXXXX with your
database information
2. Select User Accounts --> New User
3. Fill in the boxes as shown (set password = XXXXXXX)
4. Scroll to the bottom and hit Go
5. Your new database "odhsmd" should now appear in the list of databases on
the left of your phpmyadmin page.

Import Database:
1. On your phpmyadmin page, import your downloaded database "odhsmd.sql" into your
newly created database with the same name.
2. Select "odhsmd" on the list of databases on the left.
3. Select Import from the top menu, and choose the file odhsmd.sql from your
Downloads
4. Scroll to the bottom and hit Go


To set up for code sharing with your team, one member should mirror the github
repository into your team's own github repository, yourgithubaccount/yourteamsrepo.
1. Log into yourgithubaccount
2. On github, create or access the blank repository yourteamsrepo
3. In a terminal window, execute the following commands:
cd /Applications/XAMPP/htdocs (on Windows, it's c:\XAMPP\htdocs)
git clone https://github.com/jappolack/ODHSLinks to an external site.
cd ODHS or EmpowerHouse
git push --mirror https://github.com/yourgithubaccount/yourteamsrepoLinks
to an external site.
4. You will need to enter your GitHub account ID and token here, not your password.


Each team member should then clone this mirror into their own local directory.
1. In a terminal window, execute the following commands:
cd /Applications/XAMPP/htdocs (on Windows, it's c:\XAMPP\htdocs)
git clone https://github.com/yourgithubaccount/yourteamsrepoLinks to an external site.
2. Each team member can then point their browser to
http://localhost/yourteamsrepo/index.phpLinks to an external site..
You should see the login screen.

Login and Password are vmsroot

## Platform
Dr. Polack chose SiteGround as the platform on which to host the project. Below are some guides on how to manage the live
project.

### SiteGround Dashboard
Access to the SiteGround Dashboard requires a SiteGround account with access. Access is managed by Dr. Polack.

### Localhost to Siteground
Follow these steps to transfter your localhost version of the ODHS Medicine Tracker code to Siteground. For a video tutorial on
how to complete these steps, contact Dr. Polack.
1. Create an FTP Account on Siteground, giving you the necessary FTP credentials. (Hostname, Username, Password, Port)
2. Use FTP File Transfer Software (Filezilla, etc.) to transfer the files from your localhost folders to your siteground
folders using the FTP credentials from step 1.
3. Create the following database-related credentials on Siteground under the MySQL tab:
  - Database - Create the database for the siteground version under the Databases tab in the MySQL Manager by selecting the
  'Create Database' button. Database name is auto-generated and can be changed if you like.
  - User - Create a user for the database by either selecting the 'Create User' button under the Users tab, or by selecting the
  'Add New User' button from the newly created database under the Databases tab. User name is auto-generated and can be
  changed  if you like.
  - Password - Created when user is created. Password is auto generated and can be changed if you like.
4. Access the newly created database by navigating to the PHPMyAdmin tab and selecting the 'Access PHPMyAdmin' button. This
will redirect you to the PHPMyAdmin page for the database you just created. Navigate to the new database by selecting it from
the database list on the left side of the page.
5. Select the 'Import' option from the database options at the top of the page. Select the 'Choose File' button and import the
"vms.sql" file from your software files.
  - Ensure that you're keeping your .sql file up to date in order to reduce errors in your Siteground code. Keep in mind that
  Siteground is case-sensitive, and your database names in the Siteground files must be identical to the database names in the
  database.
6. Navigate to the 'dbInfo.php' page in your Siteground files. Inside the connect() function, you will see a series of PHP
variables. ($host, $database, $user, $pass) Change the server name in the 'if' statement to the name of your server, and change
the $database, $user, and $pass variables to the database name, user name, and password that you created in step 3.

### Clearing the SiteGround cache
There may occasionally be a hiccup if the caching system provided by SiteGround decides to cache one of the application's pages
in an erroneous way. The cache can be cleared via the Dashboard by navigating to Speed -> Caching on the lefthand side of the
control panel, choosing the DYNAMIC CACHE option in the center of the screen, and then clicking the Flush Cache option with a
small broom icon under Actions.

## External Libraries and APIs
The only outside library utilized by the Downtown Greens Grant Tracking Database is the jQuery library. The version of jQuery
used by the system is stored locally within the repo, within the lib folder. jQuery was used to implement form validation and
the hiding/showing of certain page elements.

## Potential Improvements
Below is a list of improvements that could be made to the system in subsequent semesters.
* Links and Fields cannot at the present moment be edited once the grant is created. This is something that could be reworked
in later semesters

## License
The project remains under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl.txt).
