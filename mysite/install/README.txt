
Automated installation
----------------------
Run the following command from the root of the site

	php mysite/install/install.php -c install -l /silverstripe/location -u dbusername -p dbpass -d dbname -b /base

where
	-c = the action to perform (by default it is install)
	-l = the location of a silverstripe package that has been unzipped
	-m = (Optional) CSV of additional modules that will be copied through from the location specified by -l
	-u = the username for the database
	-p = the password for the database
	-d = the database name
	-h = (Optional) the hostname of the database
	-b = the rewrite base for the website url (eg http://localhost/base). Leave this blank if it is at the root of a website. 


Manual installation
-------------------
Assuming this archive has been extracted as PROJPATH

1) Link to SS assets

	cd PROJPATH
	ln -s /path/to/SilverStripe-vX.X.X/sapphire sapphire
	ln -s /path/to/SilverStripe-vX.X.X/cms cms
	ln -s /path/to/SilverStripe-vX.X.X/jsparty jsparty


2) Link to any modules you require

	cd PROJPATH
	ln -s /path/to/SilverStripe-vX.X.X-modules/<modulename> <modulename>


3) Copy PROJPATH/mysite/install/htacces.sample to .htaccess. Update and edit the "RewriteBase" value to an appropriate one for the site location

4) Move PROJPATH/mysite/install/db.conf.php to PROJPATH/mysite. Update the database configuration in that file.

5) Execute PROJPATH/mysite/install/install.sql to populate the DB with default values

6) Run wget http://localhost/<installpath>/dev/build


