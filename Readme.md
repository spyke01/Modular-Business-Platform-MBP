Modular Business Platform MBP
=====================================
About the software
-------------------
Designed to be an all purpose Admin Dashboard this system is built to be expanded via modules.  

This software was originally developed and realeased by me for my company Fast Track Sites ( http://www.fasttracksites.com ). After I closed the business I made the decision to release the code so that others could use it or parts of it. We hope that this software will help new programmers get ideas about creating their own custom systems.

FAQ
-------------------
**How configurable is this?**  
It starts off with 98,280 color combinations and you can easily change it to go beyond that.

**What can I use this for?**  
The system is mainly designed as a backend administration style system but many of my former clients used it as client facing as well. 

Here are just a few of the types of systems that I built with it:

| Name                                  | Description                                                                                                                                                                                                                                                               | Included? |    Status    |
|---------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|:---------:|:------------:|
| Blog                                  |                                                                                                                                                                                                                                                                           |           |              |
| Client Management System              |                                                                                                                                                                                                                                                                           |     X     | **Complete** |
| Content Management System             |                                                                                                                                                                                                                                                                           |     X     | **Complete** |
| Delivery Management System            | Designed to track orders, delivery driver assignment, invoicing and more.                                                                                                                                                                                                 |           |              |
| Employee Scheduling                   |                                                                                                                                                                                                                                                                           |           |              |
| Impersonation Module                  | Added the ability to impersonate users and clients.                                                                                                                                                                                                                       |     X     | **Complete** |
| SEO System                            | Track SEO Optimization of Websites                                                                                                                                                                                                                                        |     X     | **Complete** |
| Serial Number (Asset) Tracking System |                                                                                                                                                                                                                                                                           |     X     | **Complete** |
| Taggable Report System                | Generate reports based on a tagged HTML block. Could be generated based on a form.                                                                                                                                                                                        |     X     | *Incomplete* |
| Trouble Ticket System                 |                                                                                                                                                                                                                                                                           |     X     | **Complete** |
| Vehicle Inventory System              | Similar to Autotrader.                                                                                                                                                                                                                                                    |           |              |
| Version Tracking System (FTS)         | This version tracking system was used to allow us to track versions of custom modules and allow for automatic updating when combined with the main system. Basically you could run your own custom version of our internal MBP update server.                             |     X     | **Complete** |
| WooCommerce Dashboard and Modifier    | Designed to act as a front end for a WooCommerce site that had become so large that it couldn't be reported or managed from within WordPress any more.                                                                                                                    |     X     |              |
| WordPress Deployment System           | This system allowed us to create website skeletons of tagged WordPress sites that could then be automatically deployed to a cPanel/WHM server by simply filling out a form. The system would also work with deploying additional MBPs or other database driven platforms. |           |              |

**Why are their Models Views and Controllers if you aren't using them?**  
Good catch! I started shifting over to this for a client which required us to create over 80 models alone. Since the MBP works within modules, I needed to map out how I wanted it to work with the main system. I decided to create a structure similar to Laravel and one that allowed us to extend our client MVC classes from a good base. The items you see here are our MVC items for the base MBP.

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


License
-------------------
This software is released under the MIT license. This software is intended for educational purposes and it is NOT recommended that you run it without first updating any JavaScript libraries and looking over the PHP code for any current bugs or security errors.
 
