# Changelog #

## 2.17.07.12 ##
**FIX** Fixed bug in impersonation system that stopped the `stop impersonating` function to not work.  
**FIX** Fixed changelog name and formatting. 
**FIX** Code formatting fixes.  

## 2.16.07.07 ##
**FIX** Fixed installation issues.  
**FIX** Fixed charts for Chart.js v2.  

## 2.16.12.20 ##
**FIX** Implemented impersonation.  

## 2.15.07.29 ##
**FIX** Licensing and update checks now go over SSL.  

## 2.15.03.03 ##
**FIX** Fixed animation bug when removing rows from tables such as when deleting items.  

## 2.15.02.13 ##
**FIX** Removed reference to FusionCharts GraphClass.  
**FIX** Fixed bug in chart JSON call that caused the graphs to not load when SE Friendly links is enabled.  

## 2.14.11.24 ##
**FIX** Updated Glyphicons icon names in files and added database fix for menu items.  

## 2.14.07.11 ##
**ADD** Added icons to all menu items.  

## 2.14.02.03 ##
**ADD** Implemented toggle switches on Settings page.  
**FIX** Updated Changelog Formatting.  

## 2.13.11.13 ##
**FIX** Changed graphs to utilize the new Chart.js script.  

## 2.13.10.28 ##
**FIX** Fixed bug in install hook that creates multiple menu entries.  

## 2.13.10.16 ##
**FIX** Recoded all edit in place areas to use javascript fuction instead of printing out all the actual edit in place calls.  

## 2.13.08.28 ##
**FIX** Upgraded to Bootstrap 3.  
**FIX** Removed table-hover as it breaks scrolling on mobile devices.  
**FIX** Fixed icon classes to match new style.  

## 2.13.08.19 ##
**Fix** Finalized Bootstrap changes.  

## 2.13.08.12 ##
**Fix** Switched all forms and their jQuery actions to use the new makeForm() and makeFormJQuery() functions.  

## 2.13.08.08 ##
**FIX** Added new security checks.  
**FIX** Removed orders and added migration code to move orders to invoices.  
**FIX** Removed re-declared actual_startsWith from CLMS.php and added it from global scope.  
**FIX** Removed re-declared variables from ajax.php and added them from global scope.  
**FIX** Database tables optimized.  
**FIX** Migrated to new database handler.  