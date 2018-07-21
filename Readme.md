Modular Business Platform MBP
=====================================

This software is released under the MIT license. This software is intended for educational purposes and it is NOT recommended that you run it without first updating any JavaScript libraries and looking over the PHP code for any current bugs or security errors.

Installing the Software
-------------------
To install the software (after updating the code), first run the following commands:

```bash
composer install
npm install
grunt build
```

Next upload the contents of `/dist` to your site and navigate to `/install.php` . This should walk you through installing the software. 

* If asked for a serial number or license information, enter anything you want. We have removed the license checks so it doesn't matter.

Notes About License Checks and Updates
-------------------
The original version of the system had the capability to perform license, blacklist, and update checks. I have left the cod ein place but commented it out to bypass it's need. I did not remove it so tha tif someone wishes to reinstate updates the code is available.


Version Numbers
-------------------
If this repo contains folders, they will be named using the same verison number as the software in this format:

R.YY.MM.DD.M

R  - Major Revision Number
YY - Year of release
MM - Month of release
DD - Day of release
M  - Minor release number if applicable

This version is also found within includes/constants.php .

About the software
-------------------
This software was originally developed and realeased by me for my company Fast Track Sites ( http://www.fasttracksites.com ). After I closed the business I made the decision to release the code so that others could use it or parts of it. We hope that this software will help new programmers get ideas about reating their own custom systems.