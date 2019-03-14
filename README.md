# FileUploader, Simple & Securised (against php deface).
I made this FileUploader in php and using bootstrap for frontend. It take's me few hours, I'll maybe made updates soon, make an issue if you want me to add something. You just need to clone the repo and you can use it, he's fully responsive so if your on phone you can also use it. Don't hesitate to star this repo !

## How it looks like :

![image](https://user-images.githubusercontent.com/45340378/54077342-2e9cff00-42b7-11e9-85c0-96c795de592f.png)

## Encryption AES256 :

The website is encrypting the directory file using a **unique key** generate when your loading the index page for the first time. **Do NEVER** change your key you will break the system, or if you want to reset your key, delete the key between the **" "** in [key.ini](upld/encryption/key.ini) and **let it empty**, it will generate a new key. If your changing your encryption key, the old download links will not work : ( **the old links encrypted using the old key, the new key will not be able to decrypt it** ).

## How did i securised the website against php deface :

1 - I'm scanning mime type of files using php functions and if the mime type is blacklisted, the upload will not happen.
```
Blacklisted mime type :
            'application/javascript'
            'application/x-javascript'
            'application/json'
            'application/ld+json'
            'application/x-php'
            'text/html'
            'text/javascript'
            'application/xhtml+xml'
            'text/x-component'
```
2 - I'm scanning file extension of files using pathinfo().
```
Blacklisted extensions :
            'php'
            'js'
            'json'
            'html'
            'xhtml
```
3 - The users can't access uploaded files, there is an htacces that disallow access to them. If they want download the file, they use download.php, who is force downloading the file. If by magic a php file is uploaded on upld the file will not be executed, the file will be force downloaded. That block "hackers" against php defacing.
