# WEBSAFE PROJECT
## COMMIT DATE: `28 AUGUST 2023, 22:33pm`
## VERSION: vC2.4

The Websafe Test Application deploys two web applications on the host machine; one vulnerable variant, and one secure variant.

This test application focuses being lightweight, setup-free and easy-to-deploy through the use of Docker. 
The compose file automatically imports a premade sql dump into each database, so no prior setup using PHPmyadmin is needed.


### Websafe Site
The Websafe Site is a shopping site with various function and pages, such as user creation, login/logout, a comments wall, a cart page, and an admin page for privileged accounts. 

### PhpMyAdmin
PhpMyAdmin is a web interface designed for managing databases. In this project, this is provided for users to see what changes are being made in the database as they navigate the site, and also for debugging purposes.  

### External Server
The External Server simulates... an external server, lying on an internal network that the websafe site is in. This external server serves as a target and a demonstration of Server-Side Request Forgery.



## INSTRUCTIONS FOR INSTALLATION
> 1) Clone this entire repository into a folder
> 2) Go to the `php-image/` folder
> 3) Run `docker build -t <image name>`
> 4) Go out to the root folder
> 5) To start, run `docker-compose up -d .`
> 6) To close, run `docker-compose down`

## LOG IN CREDENTIALS
```
WEBSAFE SITE
Username: Admin101
Password: Admin101
(Passwords are the same as the username for every default account)

DATABASE USING PHPMYADMIN
Database: insecure_database / secure_database
Root username: root
Root password: w3bs@fe_ADmin
Username: Lottie
Password: Ad0r@ble
```

## PORT MAPPING CHART
```
INSECURE VARIANT
127.0.0.1:8000 > Websafe Web Application
127.0.0.1:8001 > PhpMyAdmin SQL management page
127.0.0.1:8002 > External Server

SECURE VARIANT
127.0.0.1:9000 > Websafe Web Application
127.0.0.1:9001 > PhpMyAdmin SQL management page
127.0.0.1:9002 > External Server
```

## CONTAINER NETWORKING CHART 
```
INSECURE VARIANT
Network address: 192.168.20.0/24
Websafe Web Application: 192.168.20.69
External Server: 192.168.20.22

SECURE VARIANT
Network address: 192.168.40.0/24
Websafe Web Application: 192.168.40.69
External Server: 192.168.40.22
```


# UPDATES MADE BETWEEN COMMITS
## vC2.3 -> vC2.4
> - Removed the "code" design element from both stylesheets
> - Adjusted the Compose file
> - Updated this README file to not speak nonsense (kind of)
> - Added better descriptions for this README file
> - Added "credentials" section for this README file.

## vC2.2 -> vC2.3
> - Updated `INSECURE_SERVER/welcome.txt` and `SECURE_SERVER/welcome.txt`
> - Cleaned up comments for `design.css` on secure and insecure sites

## vC2.1 -> vC2.2
> - Updated `SECURE/login/reset/secure_update_password.php` and updated designs to go along with it
> - Updated the logo for insecure and secure site

## vC2.0 -> vC2.1
> - Cleaned up the compose file.
> - Changed container name from `webapp_secure` to `secure_webapp`.

## vC1.8 -> vC2.0
> - Replaced product images for both secure and insecure site.
> - Replaced the sql dump for both secure and insecure site.
> - Fixed a bug on the *Insecure* and *Secure* index webpage which prevent modals from showing when a product is clicked.

## vC1.7 -> vC1.8
> - Changed `SECURE/index.php` and `INSECURE/index.php` to not show "add to cart" if the logged in user is an admin.
> - Updated document name for `SECURE_SERVER/index.php` to "Manage Users" instead of "Documnet".
> - Created a new "toggle admin" function for both insecure and secure external servers.
> - Updated `SECURE/index.php` and `INSECURE/index.php` to reflect this change.

## vC1.6 -> vC1.7
> - Fixed a bug where logging in on the *secure* site would not set the user's privilege in cookies.
> - Fixed a bug on the *secure* site where a user cant access the cart page.
> - Fixed a flaw where `SECURE/login` failed to properly encrypt UID. 
> - Fixed a bug where `SECURE/index.php` would fail to add products to cart.
> - Fixed bugs where functions in `SECURE/cart/*` failed to work. 
> - Fixed a bug where `SECURE/logout` failed to flush session storage.

## vC1.5 -> vC1.6
> - Edited `INSECURE/profile` to authenticate and store passwords in base64 encoding instead of plaintext.
> - Edited the login function in `SECURE/login` to encrypt the user privilege
> - Edited `SECURE/admin/manage` to decrypt the user privilege from `SECURE/login` and cross check the privilege cookie with the privilege object in the PHP sessions array
> - Edited `SECURE/logout` and `SECURE/init_timeout` to clear the "privilege" cookie upon logout.
> - Added comments to all pages in *INSECURE* site to indicate examples of CWE vulnerabilities (MISSING COMMENTS FOR `A09:*` AND `CWE-20: Improper Input Validation`)
> - Added comments to pages in *SECURE* site to indicate examples of CWE mitigations (MISSING COMMENTS FOR `A09:*` AND `CWE-20: Improper Input Validation`)
> - Fixed a bug where `SECURE/comment/post_comment.php` didn't submit a comment, and instead redirected users to the home page.

## vC1.4 -> vC1.5
> - Fixed a bug where Secured webpage wouldn't log out properly.
> - Edited `INSECURE/register`, `INSECURE/login` and `INSECURE/login/reset` to store passwords in base64 encoding instead of plaintext.

## vC1.3 -> vC1.4
> - Fixed database connection for `INSECURE_SERVER/*` and `SECURE_SERVER/*`.
> - Installed functional SSRF-mitigation function for `SECURE/admin/main/index.php`.
> - Updated sessionTimeout function to clear session storage in secure site.
> - Deleted `SECURE/register/deleteUser.php`.
> - Fixed database import routing for `INSECURE/admin/logs/index.php`

## vC1.2 -> vC1.3
> - Changed input field for the email in `INSECURE/register` from "email" to "text" type.
> - Non-admin kickout check has been removed in `INSECURE/admin/main/index.php` and `INSECURE/admin/manage/index.php`.
> - Updated pages that hardcoded the SQL connection instead of importing it.
> - Changed "manage users" to "manage products" in the admin navbar.
> - Cleaned up unnecessary comments in all pages.
> - Added a function in `SECURE/admin/main/index.php` to mitigate SSRF, but haven't tested if it's working.

## vC1.2 -> vC1.2
> - Made the **SQL con**, **error reporting** and **session timeout** functions into their own PHP pages, and replaced all the existing code on (almost) all pages to `include()` them. (Haven't testing if anything is broken tho)
> - Changed Document Titles for all webpages so they actually reflect what the page is about instead of just "helpme" or "Document"
> - Renamed the stylesheet file to `design.css`, and updated all pages accordingly  
> - Removed non-logged-in-kickout functions on `INSECURE/cart/cart.php` and `INSECURE/cart/index.php`
> - Changed the modals on the main page so that when a user clicks on the "Add to cart" button while not logged in, it will create an alert and redirect the user to the login page

## v2.2C1.0 -> vC1.1
> - Made it so that sql dumps for secure and insecure are automatically imported into the database upon container start
> - Changed "lottie" to "Lottie" so nothing else would break because of one stupid inconsistency
> - Disabled database consistency by commenting out the "mysql" volumn in the compose files (both secure and insecure variants)

## v2.2* -> v2.2C1.0
> - I know this version convention is confusing but basically \
> 2.2I1.0 + 2.2S1.0 = 2.2C1.0 \
> right? Like you get it, no?
> - I basically just combined the insecure and secure versions together, like that's it
> - Updated port mapping to prevent clash
> - Updated container names to prevent clash


## v2.2 -> v2.2I1.0
> - It's just the normal site but basically no security, idk what else to say

## v2.2 -> v2.2S1.0
> - Security features installed on all* Websafe pages
> - Security features installed on Server1 pages
> ###### *at least the ones that we remember 

### v2.1 -> v2.2
> - Small tweaks to some pages. Was too stupid to note which ones.

### NULL -> v2.1
> - Deleted file backups
> - Deleted code snippet comments for all files
> - That's it lmao