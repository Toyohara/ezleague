#ezLeague 3.5.7#

Я не автор скрипта, я просто поперевожу.

"a custom php online gaming script"

(bug fixes listed at the bottom)

http://www.mdloring.com/ezleague-free-php-online-gaming-league-script/

demo: http://www.mdloring.com/ezl
###Default Login###
username: admin

password: ezadmin

###Dummy Data SQL Dump###
The following sql data dump is available for download to test and explore ezLeague
http://www.mdloring.com/ezleague/ezleague-sql-dummy-data.zip

Installation
------------------------------------------------------------------------------------------------------------------------
1. Open '/lib/db.class.php' & '/admin/lib/db.class.php' and modify your site URL and database values
2. Navigate to the web address / directory where you uploaded the script
3. Enter your Site Name, and check over the database information to make sure it is correct
4. Run the installation, and if successful, you should be redirected to login to the admin page
5. Default admin login -- username: admin password: ezadmin
6. After logging in successfully, navigate back to the main index page (the user-frontend side)
7. If you encounter no issues, please delete the 'install.php' file from your server as a security risk

About ezLeague
------------------------------------------------------------------------------------------------------------------------

After writing 2-3 different PHP Gaming League scripts, I've received numerous emails over the past 6months asking to offer it up for download...but it wasn't exactly user friendly. So that's what ezLeague is.

Instead of having to build in a custom templating system, the front and backend are being built on Bootstrap to make theming easier for the user.

A full-list of features is soon to come. I'm still in debate on how to combine the 3 previous scripts I've written.

News, Leagues, Matches, Teams, Users, Site Settings, Support Multiple Games

Team/Guild Challenge System for Matches
Rankings will be built on an ELO algorithm, the same ranking system used for Chess Players ...more to be listed later

Admin Panel to control and modify all Leagues, Matches, Users and Data

How to Upgrade
------------------------------------------------------------------------------------------------------------------------
When a new version is released, unless you have made your own changes to specific files, simply re-download ezLeague, and overwrite all files/folders EXCEPT for /lib/db.class.php & /admin/lib/db.class.php.

If you overwrite the db.class.php files, you'll have to re-add your database configuration information.

If you run into any issues, please post them on the Issues page (https://github.com/stoopkid1/ezleague/issues) and I'll look into it.

v3.0 BUG FIXES AND UPDATES
------------------------------------------------------------------------------------------------------------------------
https://github.com/stoopkid1/ezleague/issues

#ezLeague v2.x
The previous ezLeague v2.x can still be downloaded from here: http://www.mdloring.com/ezleague-v2.zip , but no future updates will be released for it (except for maybe some sort of v2.0 to 3.0 upgrade)
