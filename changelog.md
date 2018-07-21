# Changelog #  
  
## 5.0.18 ##  
**FIX** Removed serial number tracking, blacklisting, and call home features. Released app to public under MIT license.  


## 5.0.17 ##  
**ADD** Cron can now trigger individual jobs in modules. To do this you must format your cron task as cron.php?action=runJob&job=JOBNAME in module. It is up to you to make your modules look at the job argument that is passed to your module's cronTasks method. We recommend removing the extract($arguments) that is present in early versions of the module template.  
**ADD** get_config_value nw accepts a default vlue for cases where a config item does not exist.    
**ADD** Added ability to turn off cron job flood control timer.    
**ADD** Added ability to show the log results of cron when they are run.  

  
## 5.0.16 ##  
**FIX** Added form-control class to toggles and textareas  
  
## 5.0.15 ##  
**ADD** The forms system now autoescapes the attributes and values for form fields.   
**FIX** Made an optimization change in `user_access`.  
**FIX** pageclass variables are now public to allow for end users to fully access them for their own customizations.  
**FIX** Converted `FTS_MapRewriteMatches` to full PHP5+.  
**FIX** Updated FontAwesome to load from CDN.  
**FIX** KSES and sanitization updates.
**ADD** New Functions:
* function redirect($url = null, $data = [])  
* function sanitize_text_field( $str )  
* function sanitize_textarea_field( $str )
* function _sanitize_text_fields( $str, $keep_newlines = false )  

**ADD** New Filters:
* `urls_login_log_in_success`
* `urls_login_logged_in`
  
## 5.0.14 ##  
**ADD** Added MVC view helpers.  
**ADD** Added Parsedown.  
**FIX** Fixed Mark as read button on notifications.  
**FIX** Fixed TinyMCE CSS error on Email users page.  
**FIX** changelog.txt changed to changelog.md.  
**FIX** changelog.md formatting fixed.  
**FIX** Added a memory_limit increase during start.  
**FIX** Added a memory_limit increase during updates.  
**FIX** Switched memory_limit increase during zip extraction to a function.  
**FIX** Upgraded PclZip.  
**FIX** Switched from bower to yarn.  
**FIX** Moved class file locations.  
**FIX** Fixed issues in installer.  
**FIX** Fixed composer autoload to solve issue in the file manager plugin.  
**FIX** Fixed paths to use BASEPATH.  
**FIX** Changed Slimdown rendering to use Parsedown.  
**REMOVE** Removed Slimdown.  
**ADD** New Functions:
* view($name = null, $data = [])
* returnView($name = null, $data = [])
* convert_hr_to_bytes( $value )
* is_ini_value_changeable( $setting )
* increase_memory_limit( $toTheMax = false )
  
## 5.0.13 ##  
**FIX** Updated knockoutJS.  
**ADD** Major Update: Added MVC functionality to system and added models and controllers for built in systems. If you would like to build a MVC app, please get with a representative today.  
  
## 4.16.12.20 ##  
**FIX** Updated the perform_login call to store additional session variables. These will be used for the impersonation module.  
**ADD** New Actions:
* perform_login_items_for_user

**ADD** New Filters:
* additional_top_right_nav_items
  
## 4.16.12.16.01 ##  
**FIX** Updated get_timestamp_from_string() to pull based on a text string and not a timestamp string.  
  
## 4.16.12.16 ##  
**FIX** Updated mysqldatetime() to now take the current timezone settings into account. The original function used date() instead of gmdate() so this functionality has been kept intact. In many cases using the current_time() function would be a better choice.  
**ADD** New Functions:
* get_timezone_string()
* get_timestamp_from_string($timestamp)
  
## 4.16.12.13 ##  
**FIX** Updated login page to use alert messages for errors and to clarify link anchor text.  
  
## 4.16.11.22 ##  
**ADD** Added permissions to the file manager plugin.  
**ADD** New Permissions:
* file_manager_access_library
* file_manager_access_upload_form
* file_manager_access_recent
* file_manager_files_delete
* file_manager_files_rename
* file_manager_folders_create
* file_manager_folders_delete
* file_manager_folders_rename
  
## 4.16.11.16 ##  
**ADD** Added a new filter for all generated form jquery. Uses the format of form_jquery_FORMID.  
  
## 4.16.11.15.02 ##  
**FIX** Extended the length of the permission name and file.  
  
## 4.16.11.15.01 ##  
**ADD** Added permissions to the User Management system. There are checks in place to make sure users only access what they are allowed to. Users cannot upgrade their own User Level.  
**FIX** Made change to getMyUserIDs() function to avoid extra DB calls.  
**FIX** Fixed bug in get_permission_setting($name) function where it failed to return the role_ids for a permission.  
**FIX** Updating a user's username triggers a check to make sure it is not already in use.  
**ADD** New Functions:
* canAccessUser($userID)

**ADD** New Permissions:
* users_access
* users_create
* users_edit
* users_edit_user_level

**FIX** Updated Permissions:
* createUser -> users_create
* editUser -> users_edit
  
## 4.16.11.15 ##  
**ADD** New Filter:
* dashboard_alerts
  
## 4.16.08.22 ##  
**FIX** Defaulted the database connection to UTF8 to avoid issues with JSON conversion of non-UTF8 data.  
  
## 4.16.08.19 ##  
**FIX** Fixed return_warning_alert function to use the warning alert instead of danger alert class.  
  
## 4.16.07.22 ##  
**ADD** Added an image uploader functionality for forms. If this is integrated into any plugins that allow user level access you will need to modify security checks within the plugin. Please contact Fast Track Sites should you need help with this to verify future compatibility.  
**ADD** Enabled image uploader functionality for logo on Configure page.  
**ADD** Enabled background image uploader functionality for modern theme.  
  
## 4.16.06.30 ##  
**ADD** Added checks to make sure that multiple password resets cannot be triggered within a 5 minute period.  
**ADD** Added checks to make sure the same emails are not being triggered within a 5 minute period. This is to cut down on potential abuse and generation of spam emails due to hacking attempts.  
**ADD** New functions:
* logEmailMessage( $emailAddress, $subject, $message )
* sentEmailRecently( $emailAddress, $subject, $message )
  
## 4.16.06.16 ##  
**FIX** Updated Chart.js to utilize the latest version via CDN.  
  
## 4.16.05.28 ##  
**FIX** Updated naming convention for the theme settings tab from "THEME_NAME - Settings" to "Theme Options - THEME_NAME".  
  
## 4.16.05.23 ##  
**FIX** Updated CDN URL for TinyMCE.  
  
## 4.16.05.18 ##  
**ADD** Added ability to change the footer copyright text. This can be changed from the Configure page.  
**ADD** Added additional details to the version information box.  
**ADD** Enabled caching for license checks.  
**ADD** Added warning box for expiring licenses.  
**FIX** Updated icon on returnIsValidSerialNumberImage() function.  
  
## 4.16.04.21 ##  
**ADD** Added new color options for the modern theme. The options override theme defaults so please be careful when changing these.  
**FIX** Fixed styling for sidebar widgets.  
**FIX** Fixed spacing for sidebar items.  
**FIX** Fixed bug for the collapse menu button that caused a black border to appear on certain menu colors.  
  
## 4.16.04.18 ##  
**ADD** Added new filter for the login page called 'form_fields_login_extra_form_options'. This allows customizing the additional buttons of the form.  
**REMOVE** Removed the bootstrap theme and defaulted the selected theme to modern. Attempted to import the settings we could.  
  
## 4.16.03.18 ##  
**FIX** Fixed styling for edit in place textareas and selects to avoid white text on white background.  
**FIX** Updated jquery.editable to latest version.  
  
## 4.16.03.09 ##  
**ADD** Added filters to the email system to allow us to add custom tags. Filters added are: parseAndSendTemplateExists_tags  
**ADD** Added module hooks to the email system to allow us to add custom tags. Hooks added are: handleParseAndSendEmailTemplate  
**FIX** Fixed hook call in getDropdownArrayto make sure the prefix was passed.  
  
## 4.16.02.29 ##  
**ADD** Added attributes to control size of form input labels and input containers using: cols_label_class and cols_input_container_class  
**FIX** Fixed attributes for bootstrap switch and built in backwards compatibility. The attributes should be data_on_text, data_off_text, and data_text_label NOT data_on_label, data_off_label, and data_label_text.  
  
## 4.16.02.23 ##  
**ADD** Added new user field: facebook.  
**ADD** Added new user field: google_plus.  
**ADD** Added new user field: instagram.  
**ADD** Added new user field: linkedin.  
**ADD** Added new user field: phone_number.  
**ADD** Added new user field: pinterest.  
**ADD** Added new user field: title.  
**ADD** Added new user field: twitter.  
**FIX** Increased size of DB Field users->company.  
**FIX** Increased size of DB Field users->email_address.  
**FIX** Increased size of DB Field users->username.  
**FIX** Increased size of DB Field users->website.  
  
## 4.16.02.09 ##  
**ADD** Added ability for modules to add jQuery to the Configure page.  
**ADD** Added video walkthrough to the welcome screen.  
**FIX** Updated bootstrap switch.  
**FIX** Fixed bug in user limitation function.  
**FIX** Fixed free version license.  
  
## 4.15.10.22 ##  
**FIX** Changed notification function names to solve integration issue with IWP.  
  
## 4.15.10.02 ##  
**ADD** Added rel attribute dropdown list for tinymce editors.  
**ADD** Added rel attribute option for menu items.  
**ADD** Added notification center.  
**FIX** Updated tinymce.  
  
## 4.15.09.01 ##  
**FIX** Prepared DB for notifications system.  
  
## 4.15.08.31 ##  
**FIX** Fixed the modern template to allow you to fully hide the sidebar menu.  
  
## 4.15.08.27 ##  
**ADD** Added SMTP email functionality.  
  
## 4.15.08.25 ##  
**FIX** Moved email functions from general.php to email.php.  
  
## 4.15.08.23 ##  
**FIX** Fixed bug in installer which affected module installation.  
**FIX** Fixed bug in updater which caused the system to rerun current updates.  
  
## 4.15.08.07 ##  
**ADD** Added inputmode to form item options.  
  
## 4.15.08.06 ##  
**ADD** Added disabled to form item options.  
  
## 4.15.07.29 ##  
**FIX** Fixed bug in update screen when using SSL.  
**FIX** Licensing and update checks now go over SSL.  
  
## 4.15.07.27.01 ##  
**FIX** Fixed prettyPhoto security vulnerability.  
  
## 4.15.07.27 ##  
**FIX** Added the ability to recheck serial number status when site is disabled.  
  
## 4.15.05.14 ##  
**ADD** Added the DB table to handle notifications. The rest of the functionality will be released in the near future.  
**FIX** Changed the return_?_alert functions to all route through return_alert().  
  
## 4.15.04.27 ##  
**FIX** Fixed bug where language might not be in the database.  
**FIX** Forced site to load in HTTPS if setting is in place.  
  
## 4.15.04.15.01 ##  
**FIX** Fixed access check on the Edit profile link to make sure it doesn't appear. Normal users would get an access denied message when attempting to use it.  
**FIX** Fixed bug for using a button group on datepicker inputs.  
  
## 4.15.04.15 ##  
**ADD** Added readonly to form item options.  
**FIX** Fixed missing datepicker title styles.  
  
## 4.15.04.14 ##  
**FIX** Fixed printer friendly page to enable AJAX events and increase viewport.  
  
## 4.15.04.09 ##  
**ADD** Added Enable SSL option on Configure page.  
**ADD** Enabled CORS by default in htaccess file.  
**FIX** Fixed bug in links for SSL support.  
  
## 4.15.04.08 ##  
**ADD** Added 'reports_otherVersionLinks' filter to allow tying into the buttons on the report page.  
**FIX** Fixed bug in printer-friendly report link that affected modules.  
  
## 4.15.04.06 ##  
**FIX** Increased length of permission name to allow for more complex permissions.  
**FIX** Fixed the tag for site URL in the email templates.  
  
## 4.15.03.26 ##  
**FIX** Removed the custom jquery ui css file as it broke most of the jquery ui css and widgets in turn.  
  
## 4.15.03.25 ##  
**ADD** Added constants_category_type filter to allow tying into the categories constant which in turn allows modules to add additional categories.  
  
## 4.15.03.19 ##  
**FIX** Fixed colspan on permissions table to handle more permissions.  
**FIX** Fixed colspan on menu items permissions table to handle more permissions.  
**FIX** Fixed bug in tags for user emails.  
  
## 4.15.03.03 ##  
**FIX** Fixed animation bug when removing rows from tables such as when deleting items.  
  
## 4.15.02.27 ##  
**FIX** Fixed bug in password rest procedure.  
  
## 4.15.02.13 ##  
**FIX** Removed FusionCharts since Charts.js is in place.  
**FIX** Updated Charts.js.  
**FIX** Updated TinyMCE.  
  
## 4.15.02.12 ##  
**FIX** Fixed issue in the sitemap generation code.  
  
## 4.15.01.23 ##  
**FIX** Fixed issue in the module include code.  
  
## 4.14.12.31 ##  
**ADD** Added Analytics Code box on the Settings page and to the themes.  
  
## 4.14.12.11 ##  
**FIX** Fixed bug with animations where they looped infinitely.  
  
## 4.14.11.24 ##  
**FIX** Updated Bootstrap to 3.3.1.  
**FIX** Updated Glyphicons Pro.  
**FIX** Updated Glyphicons icon names in files and added database fix for menu items.  
  
## 4.14.11.04 ##  
**FIX** Fixed bug in UTC tiemzone calculations.  
  
## 4.14.10.28 ##  
**ADD** Added password strength bars to new and edit user forms. The functionality of these prevents more than one being visible on each page so we can't add it to the change password popup.  
**ADD** Added jQuery Smart menus plugin.  
**ADD** Added dropdown support for modern theme.  
**ADD** Added theme update notice.  
**ADD** Added new timezone settings and updated code to allow for better.  
**FIX** Fixed colors and styling of tabs and dropdowns in the modern theme.  
**FIX** Fixed preview images for themes.  
**FIX** Fixed widget header styling.  
  
## 4.14.10.27 ##  
**FIX** Removed xml from forced caching in .htaccess due to issue with sitemap.xml.  
  
## 4.14.10.21 ##  
**FIX** Fixed bug that causes an infinite login loop when the site is inactive.  
  
## 4.14.09.17 ##  
**ADD** Added new background to Modern Theme: Interlocking Blocks.  
  
## 4.14.09.16 ##  
**ADD** Added new options to Modern Theme: Custom CSS, Login Page Body Style Themes, Text Color, and Text Shadow Color.  
**FIX** Fixed bug in Modern Theme for MiniColors plugin.  
**FIX** Fixed bug in Modern Theme for glyphicons color in box headers.  
**FIX** Fixed bug on Edit Template page.  
  
## 4.14.09.11 ##  
**ADD** Added ability to select a background color and pattern for the login page (Modern Theme Only).  
**FIX** Fixed bug in theme to show correct background color (Both Themes).  
**FIX** Fixed JS and CSS includes on login page (Both Themes).  
  
## 4.14.08.05 ##  
**FIX** Changed signup_date from an int (datetimestamp) to a datetime field.  
  
## 4.14.08.04 ##  
**FIX** Fixed bug in dashboard tour that caused the jumbotron to never disappear.  
  
## 4.14.07.31 ##  
**FIX** Updated Bootstrap from 3.0.0 to v3.2.0.  
**FIX** Removed forced white icon colors, this should be handled via CSS.  
**FIX** Changed box toolbar tabs to nav-tabs instead of nav-pills.  
**FIX** Fixed bug in theme settings tab so that color and background previews show on page load.  
  
## 4.14.07.15.01 ##  
**ADD** Added HTML5 elements and attributes to the form class. Elements added were: `search, email, url, tel, number, range, date, month, week, time, datetime, datetime-local, color`. Attributes added were: `autocomplete, autofocus, min, max, pattern, required, step`.  
  
## 4.14.07.15 ##  
**ADD** Added App Info section to Configure page.  
**FIX** Fixed bug in Slimdown paragraph rule.  
**FIX** Fixed bug in update popup for modules.  
**FIX** Fixed icons for update/version alerts.  
**ADD** New functions:
* returnAppVersionBlock()
* returnAppInfoBlock()
  
## 4.14.07.14 ##  
**ADD** Added CSS classes for notes. Valid types are same as alerts, ie. `note note-danger`, `note note-info`, etc.  
**ADD** Added Email Users page which include Email Templates.  
**FIX** New Account and Account Update alerts have been moved into the Email Templates page.  
  
## 4.14.07.11 ##  
**ADD** Added [Select2](http://ivaynberg.github.io/select2/) for better select boxes.  
**ADD** Added [jQuery Autosize](http://www.jacklmoore.com/autosize/) for autoresizing textareas.  
**ADD** Added optgroups and multiple selection for dropdowns.  
**ADD** Added disabled and readonly form inputs.  
**ADD** Added icons to all menu items.  
**ADD** Added ability to email users and manage email templates.  
**FIX** Fixed bug with page tabs not filling entire bar height.  
**FIX** Fixed bug with tabs in forms not working.  
  
## 4.14.07.09 ##  
**FIX** Updated filters for better performance, also added phpDoc blocks.  
**FIX** Renamed includes/functions/load-scripts.php to link-template.php.  
**FIX** Updated KSES.  
**FIX** Moved default DB update fixes into includes/dbUpdates/defaults.php.  
**FIX** Added fix for broken variable in checkForModuleUpdates();  
**FIX** File names now use - instead of _ to denote a space;  
**ADD** New functions:
* addMenusToPage()
* compressCode( $buffer )
* returnAppVersionInfo()
  
## 4.14.07.08 ##  
**FIX** Updated Slimdown to latest version.  
**FIX** Upgraded fileFunctions class with better error handling and more options.  
  
## 4.14.06.06 ##  
**FIX** Add javascript include for uploadify to template.  
  
## 4.14.06.04.01 ##  
**ADD** Addes Server Requirements checker to Installer.  
**FIX** Redesigned Installer.  
**FIX** Fixed validation on Installer.  
**FIX** Fixed serial number checker bug on Installer.  
  
## 4.14.06.04 ##  
**FIX** TinyMCE now saves on AJAX form validation.  
  
## 4.14.06.03.01 ##  
**FIX** Tours moved to their own file, AJAX call now allows for multiple tours to be handled from one call.  
  
## 4.14.06.03 ##  
**ADD** Alerts now support ids.  
**ADD** Added support for application tours and added the dashboard tour.  
**FIX** Condensed all update calls and notifications to use a single access check (perform_update).  
**ADD** New functions:
* showedUpdatePopup()  
**FIX** Updated functions:
* return_alert( $text, $showIcon = 1, $icon = 'glyphicon glyphicon-ok' ) -> return_alert( $text, $showIcon = 1, $icon = 'glyphicon glyphicon-ok', $id = '' )
* return_error_alert( $text, $showIcon = 1, $icon = 'glyphicons white warning_sign' ) -> return_error_alert( $text, $showIcon = 1, $icon = 'glyphicons white warning_sign', $id = '' )
* return_info_alert( $text, $showIcon = 1, $icon = 'glyphicons white warning_sign' ) -> return_info_alert( $text, $showIcon = 1, $icon = 'glyphicons white warning_sign', $id = '' )
* return_warning_alert( $text, $showIcon = 1, $icon = 'glyphicons white warning_sign' ) -> return_warning_alert( $text, $showIcon = 1, $icon = 'glyphicons white warning_sign', $id = '' )
  
## 4.14.06.02 ##  
**ADD** Added details for module updates.  
**ADD** Added ability to specify favicon.  
**ADD** Added apple touch icons.  
**FIX** Fixed bug in notification for expired serial numbers.  
**FIX** Fixed theme preview.  
**ADD** New functions:
* showUpdatePopup()
  
## 4.14.05.30 ##  
**ADD** Added notification for expired serial numbers.  
**FIX** keeptasafe() now keeps new line and return characters.  
  
## 4.14.05.16 ##  
**FIX** New menu items now keep the permissions specified.  
**FIX** Modules table now displays latest version image correctly.  
  
## 4.14.05.09 ##  
**FIX** Fixed bug with logging session ids.  
  
## 4.14.04.29 ##  
**ADD** System now performs 301 redirect if user access non-www for www site and www for non-www site.  
  
## 4.14.04.10 ##  
**FIX** Various form and link fixes utlizing il() to fix mod_rewrite issues.  
**FIX** Input text now shows when hovering over an edit in place element.  
  
## 4.14.03.28 ##  
**FIX** Updated to latest version of Bootstrap and added necessary fixes.  
  
## 4.14.03.25.01 ##  
**FIX** Fixed bug where logs were pruned even when set not to.  
  
## 4.14.03.25 ##  
**ADD** Added DataTables to the system.  
**ADD** Added ability for plugins to add columns to the Latest Log Entries Report table.  
**ADD** Added ability for plugins to add columns to the User Details Report table.  
**ADD** Latest Log Entries and Users reports are now using DataTables.  
**ADD** Latest Log Entries now shows 500 most recent entries.  
  
## 4.14.03.21 ##  
**ADD** Started logging successful and failed emails.  
  
## 4.14.03.20 ##  
**ADD** Started logging successful and failed logins.  
  
## 4.14.03.19 ##  
**ADD** Added latest log entries report.  
  
## 4.14.03.14.01 ##  
**FIX** Fixed bug in widgets.  
  
## 4.14.03.14 ##  
**FIX** Fixed bug in logging.  
  
## 4.14.03.12 ##  
**FIX** Fixed styling for closed widget areas.  
**FIX** Fixed logging on index.php.  
**ADD** Added logging on ajax.php.  
  
## 4.14.03.11 ##  
**ADD** Added assoc_id2 and assoc_id3 to logging table.  
**ADD** Added page level logging.  
**ADD** Added new widget area: 'Left Column: Above Menu'.  
**ADD** Added styling for widgets in the sidebar.  
**FIX** Changed add_config_value() to update current value instead of deleting it to avoid large table indexes.  
  
## 4.14.03.10 ##  
**ADD** Added ability for plugins to add columns to the Users table.  
**FIX** Added ability for apply_filters to utilize additional arguments.  
  
## 4.14.02.28 ##  
**ADD** Added id column to config table.  
**FIX** Made system run previously missed DB updates due to missing includes in updates.php.  
  
## 4.14.02.26 ##  
**ADD** Added underline to TinyMCE toolbar.  
**FIX** Fixed bug in edit user email.  
  
## 4.14.02.24 ##  
**REMOVE** Default theme has been removed since the system fully utilizes the bootstrap theme.  
**ADD** Password strength option for forms, simply add the class showStrength to any password input.  
**ADD** Generate Password to user forms.  
**ADD** Append and prepend button capabilities to forms.  
**ADD** Editable subject for new account and account updated emails.  
**FIX** Upgraded to CDN version of TinyMCE 4 (old files are removed during update).  
**FIX** Fixed bug in new user email.  
  
## 4.14.02.18 ##  
**ADD** New functions:
* generateStrongPassword( $length = 9, $add_dashes = false, $available_sets = 'luds' )  
  
## 4.14.02.13 ##  
**ADD** Added logging.  
**ADD** New functions:
* addLogEvent( $dataArray )
* deleteLogEvent( $id )
* getLogEvent( $id )
* updateLogEvent( $id, $dataArray )* 
* pruneLogs()
* time_elapsed( $secs )
* time_elapsed_alt( $secs )

**ADD** New Constants for use in logging events. These types should be broken into batches of 10 with a general type and then further subtypes below it.
* 'LOG_TYPE_LOGIN', 10 
* 'LOG_TYPE_LOGIN_FAIL', 11
* 'LOG_TYPE_UPDATE', 20 
* 'LOG_TYPE_CRON', 30 
* 'LOG_TYPE_USER', 40 
* 'LOG_TYPE_USER_CREATE', 41 
* 'LOG_TYPE_USER_DELETE', 42 
* 'LOG_TYPE_USER_UPDATE', 43

**FIX** Added log table pruning to cron job.  
**FIX** Fixed bug which overwrites the powered by notice and dashboard text on every cron job run.  
  
## 4.14.02.12 ##  
**ADD** Added ability to change dashboard text from Configure page.  
**ADD** Added database entry for Powered By text so that advanced users can change this without the need for a custom theme.  
**FIX** Changed create account email tag to show unecrypted password.  
  
## 4.14.02.03 ##  
**ADD** Added toggle switches using [Bootstrap Switch](https://github.com/nostalgiaz/bootstrap-switch).  
**ADD** Added to "toggle" as valid form field.  
**ADD** Implemented toggle switches on Settings and Users page.  
  
## 4.14.01.31 ##  
**ADD** Added change password prompt to user avatar menu.  
**FIX** Fixed issue with logout function not expiring the cookie correctly.  
**FIX** Emails were using internal URLs instead of full URLs.  
**FIX** Updated functions:
* il( $link ) -> il( $link, $fullLink = 0 )
  
## 4.14.01.30 ##  
**ADD** Added CSS animation for Bootstrap theme using [animate.css](https://github.com/daneden/animate.css).  
**ADD** Added animation for login page template.  
  
## 4.14.01.27 ##  
**ADD** Added emails for new user and user updated.  
  
## 4.14.01.08 ##  
**FIX** Changed changelog to use Markdown.  
**ADD** Added Markdown parser.  
**ADD** Ability to choose icons for menu items.  
  
## 4.13.12.20 ##  
**FIX** Updated tablesorter JS to latest version to fix sorting bug.  
  
## 4.13.12.16 ##  
**ADD** Started tracking previous DB version in addition to current version.  
**ADD** Popup covering updates after system is updated.  
  
## 4.13.12.12 ##  
**ADD** New Background: Chalkboard  
**ADD** New Background: Diamonds  
**ADD** Users can now login with their email address.  
**FIX** Added the password reset fields to the database.  
**FIX** Bug with logout process  
**FIX** Bug with table glyphicon which caused the text to wrap to a new line.  
  
## 4.13.11.26 ##  
**ADD** Knockout javascript library.  
  
## 4.13.11.20 ##  
**ADD** Ability to preview items from a selection box. This is handled via an ajax call that grabs a genereated JS query, this allows plugins and themes to use IDs or other items to look up how to handle a preview for a specific dropdown.  
  
## 4.13.11.13 ##  
**FIX** Changed graph plugins and method for displaying them.  
**ADD** [JustGuage](http://www.justgage.com/) and [Chart.js](http://www.chartjs.org/).  
  
## 4.13.11.12 ##  
**FIX** Fixed bug in paddString().  
  
## 4.13.11.04 ##  
**ADD** Added custom error handling and backtrace.  
**ADD** New functions:
* msg_handler( $errno, $msg_text, $errfile, $errline )
* get_backtrace()
* exit_handler()
* send_status_line( $code, $message )
  
## 4.13.10.31 ##  
**FIX** Updated paddString() to use str_pad(), it now functions as a wrapper instead of re-writing it.  
  
## 4.13.10.30.01 ##  
**FIX** Added module call on homepage for changePageTemplate hook.  
  
## 4.13.10.30 ##  
**FIX** Added type cast within ftsdb to remove errors from poorly formatted arguments.  
  
## 4.13.10.29 ##  
**ADD** New functions:
* return_alert( $text, $showIcon = 1, $icon = 'glyphicon glyphicon-ok' )
* return_error_alert( $text, $showIcon = 1, $icon = 'glyphicon glyphicon-warning-sign white' )
* return_info_alert( $text, $showIcon = 1, $icon = 'glyphicon glyphicon-warning-sign white' )
* return_warning_alert( $text, $showIcon = 1, $icon = 'glyphicon glyphicon-warning-sign white' )
  
## 4.13.10.28 ##  
**FIX** Fixed bug in update permissions ajax.  
**FIX** Disabled database file recreation on update.   
**FIX** Updated validation styling.   
**FIX** Updated login form styling.   
**ADD** Added create account form and option to turn public account creation on or off.  
**ADD** Added ability to further customize forms including extra classes for the submit button and extra buttons.  
**ADD** New functions:
* menu_item_exists( $menu_id, $text, $link, $added_by, $prefix )
* username_exists( $username )
  
## 4.13.10.25 ##  
**FIX** Fixed bug in license check which causes an unescaped character failure in the license file.  
  
## 4.13.10.16 ##  
**FIX** Fixed bug in form cleanup which removed the value##'' on selects causing the --Select One-- item to be stored int he database.  
**FIX** Recoded all edit in place areas to use javascript fuction instead of printing out all the actual edit in place calls.  
  
## 4.13.10.15 ##  
**ADD** Moved deletition prompts to use the bootbox script instead.  
  
## 4.13.10.11 ##  
**ADD** Added Bootbox (Bootstrap Modal wrapper) script.  
**ADD** Added filter for dashboard text so that plugins can change it.  
  
## 4.13.10.07 ##  
**FIX** Fixed Yellow tab colors on theme.  
  
## 4.13.10.06 ##  
**FIX** Links to Fast Track Sites are now nofollow.  
  
## 4.13.09.30 ##  
**FIX** Fixed code to remove duplicate Widgets menu items.  
**FIX** Uploadify now creates the uploads directory if it doesn't exist.  
  
## 4.13.09.25 ##  
**ADD** POST, GET, COKKIE, and REQUEST globals now have slashes stripped from them.  
**ADD** New functions:
* stripslashes_deep( $value )
  
## 4.13.09.23 ##  
**ADD** New functions:
* getUsersNameFromID( $userID )
  
## 4.13.09.17.02 ##  
**FIX** Disabled error messages for strict and deprecated errors since some modules may use PEAR libraries or other code with these problems.  
  
## 4.13.09.17.01 ##  
**FIX** Added static descriptor and PHPDoc to FTS_MapReriteMatches.  
  
## 4.13.09.17 ##  
**FIX** Removed php4 compatability in FTS_Widget.  
  
## 4.13.08.29 ##  
**FIX** Removed 'required' from password fields on the Edit User page.  
  
## 4.13.08.28 ##  
**FIX** Upgraded to Bootstrap 3.  
**FIX** Fixed responsiveness classes in theme.  
**FIX** Removed table-hover as it breaks scrolling on mobile devices.  
**FIX** Integrate Bootstrap 2 button and input styling.  
**FIX** Switched Glyphicons Pro to use font files and match same size as built-in Halflings.  
  
## 4.13.08.27 ##  
**FIX** Fixed theme page to utilize new theme immediately after changing the active theme, avoids the broken page look if two themes use different stylesheet names/uris.  
  
## 4.13.08.23 ##  
**FIX** Fixed cases where array_merge destroyed numeric indexes in arrays.  
**FIX** Fixed menus to show all menu items, not just system menus items.  
**FIX** Fixed username and gravatar image in theme.  
**FIX** Added email address to the create/edit user forms.  
  
## 4.13.08.19.01 ##  
**FIX** Replaced ereg_replace with preg_replace in includes/functions/widgets.php.  
  
## 4.13.08.19 ##  
**FIX** Fixed CSS for cross browser support.  
**FIX** Database updates now have their own folder.  
  
## 4.13.08.18 ##  
**FIX** Fixed login page CSS and removed stay logged in checkbox, default is to stay logged in using a cookie.  
  
## 4.13.08.16 ##  
**FIX** Changed print and export links to buttons with icons.  
**FIX** Replaced kolorpicker with minicolors for better color picking.  
  
## 4.13.08.14 ##  
**ADD** Glyphicons Pro from http://glyphicons.com .  
  
## 4.13.08.14 ##  
**ADD** New default theme: Bootstrap, utilizes the twitter Bootstrap framework, copies existing color settings from default theme.  
**ADD** New options on page class methods for more in depth themeing.  
**FIX** Widgets Page: Moved JS to its ow file, added ability to collapse the widget aeas and available widgets. Collapsed all but the first by default.  
  
## 4.13.08.13 ##  
**ADD** New function: get_gravatar() .  
  
## 4.13.08.12 ##  
**FIX** Altered ftsdb to return true if an update/delete/insert query succeeds instead of the row count since if no data is changed the row count would be 0 and cause a failure message.  
**FIX** Added mysql_connect back to the _db.php temporarily in order to allow old installations to completely update.  
**FIX** Switched all forms and their jQuery actions to use the new makeForm() and makeFormJQuery() functions.  
**ADD** added ability to force an update of all modules and the main system by adding the URL parameter 'forceUpdate'.  
  
## 4.13.08.11 ##  
**FIX** Changed coding style of if/else to be more in line with Zend standards.  
  
## 4.13.08.09 ##  
**FIX** Bug in $page->printSidebar() where it looks for $tag which doesn't exist for this methd.  
**FIX** Made sure updates don't overwrite development versions.  
**FIX** Changed error reporting level.  
**ADD** Functions to create forms and their jquery, will minimize code needed for internal and module pages.  
  
## 4.13.08.08  
**FIX** Deprecated functions:
* parseurl($makesafe) => keepsafe($makesafe)
* returnRemoteFilePost($host, $directory, $filename, $urlVariablesArray = array()) => $fts_http->request( $path, $method = 'GET', $request_vars = array() )
* curlGetURL($url) => $fts_http->request( $path, $method = 'GET', $request_vars = array() )
  
**ADD** New functions:
* load_config_values()
* config_value_exists($name)
* add_config_value($name, $value)
* delete_config_value($name)
* get_config_value($name)
* update_config_value($name, $value)
* add_url_rewrite($match, $query, $added_by = 'System', $prefix = '')
* delete_url_rewrite($match)
* get_url_rewrite($match)
* update_url_rewrite($match, $query)
* url_rewrite_exists($match)
* add_permision_setting($name, $role_ids)
* delete_permision_setting($name)
* get_permission_setting($name)
* permision_setting_exists($name)
* esc_js( $text )
* esc_html( $text )
* esc_attr( $text )
* esc_textarea( $text )
* tag_escape($tag_name)
* fts_strip_all_tags($string, $remove_breaks = false)
* sanitize_text_field($str)
* sanitize_email( $email )

**ADD** fts_http class and global variable for using CURL GET/POST.  
**ADD** Cron flood control.  
**FIX** Widget areas now expandable via 'widget_areas' filter.  
**FIX** Removed ending PHP tags on files.  
**FIX** Optimized install.php.  
**FIX** Login and session expiration moved to hookable actions.  
**FIX** Added WP-esque action and filters.  
**FIX** Reorganized functions folder and files.  
**FIX** Login template now used whenever login screen is shown.  
**FIX** Performance improvements in header.php.  
**FIX** _db.php updated.  
**FIX** Added new security checks.  
**FIX** Database tables optimized.  
**ADD** Database version tracking.  
**FIX** Migrated to new database handler.