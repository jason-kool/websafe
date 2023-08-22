# WEBSAFE PROJECT
## COMMIT DATE: `15 AUGUST 2023, 23:33pm`
## VERSION: vC1.2

This commit contains both the **secure** and **insecure** implementations of the site, controlled by a singular docker compose file. \
I haven't done a thorough inspection of every file in both variants of the site, nor have I checked to see if the vulnerabilities we planned have been accounted for.

The compose file automatically imports a premade sql dump into each database, so no prior setup using PHPmyadmin is needed.


### Websafe site
I did the thing where it makes things """""efficient""""" by turning the error reporting, session timeout and SQL connection codes into their own files so they can be imported on the fly. Never tested if it works seamlessly tho lmao\
***SECURE SITE DOES NOT HAVE SSRF SECURITY MEASURES IN PLACE***

### External server
The welcome page is still fucked. Like I kinda need help because I have no idea how to design it lmao

## PORT MAPPING CHART
```
INSECURE VARIANT
127.0.0.1:8000 > Websafe Web Application
127.0.0.1:8001 > MyPHPAdmin SQL management page
127.0.0.1:8002 > External Server

SECURE VARIANT
127.0.0.1:9000 > Websafe Web Application
127.0.0.1:9001 > MyPHPAdmin SQL management page
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


## INSTRUCTIONS FOR INSTALLATION
> 1) Clone this entire repository into a folder
> 2) Go to the `php-image/` folder
> 3) Run `docker build -t <image name>`
> 4) Go out to the root folder
> 5) To start, run `docker-compose up -d .`
> 6) To close, run `docker-compose down`

# UPDATES MADE BETWEEN COMMITS
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