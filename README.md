# Purefeed
---
PHP flat-file social media-like personal feed without Javascript  
Demo at [purefeed.boku.cz](https://purefeed.boku.cz/)
---

### Purpose and principles
 - Minimalistic (with code and design choices)
 - No database system (smaller code base, less dependencies, lower attack vectors)
 - No javascript whatsoever (for privacy and security reasons, JS is so controversial because while it allows for some great fetaure on modern web, it has access to the tremendous sensitive information and is the main reason you can be tracked and made business about)
 - native modern HTML5 & CSS3 fetaures (without JS, a webdev has to get creative, so purefeed uses hidden checkboxes and labels for confirmations and disappeaing parts, anchors and horizontal scrolling for image galleries and so on)
 - No libraries or frameworks (better loading time, smarter upkeeping)

### Features
 - Responsive design that loads very fast
 - You can post text statuses, full articles written in Markdown, photo with description and location or multiple photos bundled into a gallery-type post
 - automatic RSS feed (at your project URL /rss.php, yes, it's a php file returning XML on requests from your reader)

### Installation
Latest stable version of purefeed can always be found here: [Latest](https://github.com/arajnoha/purefeed/releases/latest).  
All releases are here: [Releases](https://github.com/arajnoha/purefeed/releases).  
Simply upload the files on your server or webhosting, give the PHP files 755 rights and login in on your domain with the password `feed` (change it right away in the settings)

### Updating
Currently, make fresh _git pull_ and just copy all files to your server **except** core/data.php (where your settings and password reside, no posts get affected because the post folder is not included in the repository)
I plan on having a built-in update tool later on.

### Free software
I made this to have a place for myself to publish stuff, but also to replace proprietary garbage services in my life with freedom-respecting ones, where I know exactly what happens with my data, the ownership of them is untouched, noone sells me anything nor are my data sold and I don't have to use an interface designed by ex-casino people to keep me in the app to monetize my attention with targeted advertisment. Purefeed is published under GPL, for the markdown parsing of articles, I use a Parsedown PHP code that is licenced with MIT.

### Troubleshooting
- "I have forgotten my password" => Navigate to `core/data.php` on your server, it's stored in this file as a variable. (given the PHP's nature, it's not accessable from the web)
- "How do I change the dominant color of the purefeed?" => There are two colors other than the content, the main background color and a darker, acompaning secondary color (used for buttons, slider arrows etc.) You need to change them on multiple places however, so in your editor of choice, perform a Find&Replace of two color string - `#f3ceb2` being the background color and `#c98b3b` as the darker secondary color.

### Me
To get in touch with me, write me an email at adam@rajnoha.com.  
You can support what i do financially at [Liberapay here](https://liberapay.com/arajnoha/donate).  
Feel free to open merge requsts.  