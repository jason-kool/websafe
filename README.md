# WEBSAFE PROJECT
## COMMIT DATE: `11 AUGUST 2023, 12:05am`
## VERSION: vC1.0
This commit contains both the **secure** and **insecure** implementations of the site, controlled by a singular docker compose file. \
I haven't done a thorough inspection of every file in both variants of the site, nor have I checked to see if the vulnerabilities we planned have been accounted for.


### Websafe site
I originally wanted to make things """""efficient""""" by making some things into files that can be imported using the "include" function but fuck it, I'll do that another time.\
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
This chart is applicable to both variants of the site.
```
Network address: 192.168.42.0/24
Websafe Web Application: 192.168.42.69
External Server: 192.168.42.22
```


## INSTRUCTIONS FOR INSTALLATION
> 1) Clone this entire repository into a folder
> 2) Go to the `php-image/` folder
> 3) Run `docker build -t <image name>`
> 4) Go out to the root folder
> 5) To start, run `docker-compose up -d`
> 6) To close, run `docker-compose down`

# UPDATES MADE BETWEEN COMMITS
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