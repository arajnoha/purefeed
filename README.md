# Purefeed
---
PHP flat-file social media-like personal feed without Javascript
---

### Purpose and principles
 - Minimalistic (with code and design choices)
 - No database system (smaller code base, less dependencies, lower attack vectors)
 - No javascript whatsoever (for privacy and security reasons, JS is so controversial because while it allows for some great fetaure on modern web, it has access to the tremendous sensitive information and is the main reason you can be tracked and made business about)
 - native modern HTML5 & CSS3 fetaures (without JS, a webdev has to get creative, so purefeed uses hidden checkboxes and labels for confirmations and disappeaing parts, anchors and horizontal scrolling for image galleries and so on)
 - No libraries or frameworks (better loading time, smarter upkeeping)

### Features
 - Responsive design that loads very fast
 - You can post text statuses, full articles written in Markdown, photo with description or multiple photos bundled into a gallery-type post
 - Changable dominant color of the web
 - automatic RSS feed (at your project URL /rss.php, yes, it's a php file returning XML on requests from your reader)

### Installation
Simply upload the files on your server or webhosting, give the PHP files 755 rights and login in on your domain with the password `feed` (change it right away in the settings)

### Free software
I made this to have a place for myself to publish stuff, but also to replace proprietary garbage services in my life with freedom-respecting ones, where I know exactly what happens with my data, the ownership of them is untouched, noone sells me anything nor are my data sold and I don't have to use an interface designed by ex-casino people to keep me in the app to monetize my attention with targeted advertisment. Purefeed is published under GPL, for the markdown parsing of articles, I use a Parsedown PHP code that is licenced with MIT.

### Me
To get in touch with me, write me an email at adam@rajnoha.com.  
You can support what i do financially at [Liberapay here](https://liberapay.com/arajnoha/donate).  
Feel free to open merge requsts.  

### Upcoming
I get so much ideas and so little time, so I implement features one by one, whenever I have time. These I work on right now:
 - Posts filters (if you post random stuff on your purefeed but also publish articles, it would be nice if readers could filter the post types)
 - Audio posts (for little clips and well as an alternative way of doing a self-hostad free podcast)
 - Video posts
 - Edits (editing the posts)
 - Download original files (every post gets a new folder named by the current timestamp, inside it, there is an original photo uploaded as well as the scaled-down one, so graphical posts will have the ability to download the full image)
 - other image types than JPG
 - pagination