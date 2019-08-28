# Simple FileUploader.
I made this FileUploader in php and using bootstrap for frontend. It take's me few hours, I'll maybe made updates soon, make an issue if you want me to add something. You just need to clone the repo and you can use it, he's fully responsive so if your on phone you can also use it. Don't hesitate to star this repo !

## How it looks like :

![image](https://user-images.githubusercontent.com/45340378/54475564-eae45100-47f2-11e9-8069-3edbf40eedc6.png)

## Upload logs :

You can see your last uploaded files in the bottom of the page, the logs are in your web session, so only your computer will see your last uploads. If you want to clean your upload logs just clean your cookies from the website.

![image](https://user-images.githubusercontent.com/45340378/54475597-3139b000-47f3-11e9-8721-dad86e10ed14.png)

As you can see, there is your last uploaded files, if you click on it it will redirect you to the download link. It's really practical.

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
