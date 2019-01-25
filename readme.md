# Korean Folklore Archive

## Setup

Simply clone the repository:

`git clone git@bitbucket.org:uclacdh/kfl-map-search.git`

### Box

This application uses a Box JWT PHP Library for file storage.

1. Download or clone/fork this repo into the root directory of this KFL repo and rename the folder to box-jwt-php

	* https://github.com/allenmichael/box-jwt-php
       
2. Follow setup instructions from original repo.

3. Create a folder in box in which all uploaded files from this web application will be stored.

4. Create a box user (either manually or by following the example in examples/exUsersCreateAppUser.php), and add this user as a collaborator on the box folder.

5. In the src/Config/BoxConstants.php ...

	* Set the **CONFIG_PATH** variable to the full path location.
   
	* Be sure to also set **BOX_ROOT_FOLDER_ID** and **BOX_ROOT_FOLDER_NAME**.

6. In box.config.php ...

	* Set **jwtPrivateKey** to the full path of your pkey.pem file.


7. In vendor/guzzlehttp/guzzle/src/RedirectMiddleware.php ...

	* For **$defaultSettings**, set 'max' => 0 (This will change the amount of redirects allowed. This is very important for downloading files).


### Database
* **If this is an initial setup ... **
    * If this is the first time setting up KFL please run **migrations/initial_setup.sql** to setup the database.

* **If you are upgrading ...**
    * If you are upgrading from Version 1 to Version 2
    * First, seperate the student files into two seperate folders. First folder will be the files that have a numeric name and no file extension. The second folder will be the files that begin with the word 'file' and have an extension.
    * You will need to run the **migrations/2_up_migrate_files_to_box.php** script to update the database and move all existing files off the server to Box.
    * But, before you do, update the **boxFolderId** and **FILEPATH** and **FILEPATH2** accordingly.
    * You may also run migrations/2_up.sql first to update the database before migrating the files if you prefer (but the file migration script will do it as well).

## Other
Please note: 

* Set up database login credentials in dbconfig.php
* Compile SCSS files within map_search directory

## Initial Login
* As the first user of the application, you will need to be the admin.
* Login using Shibboleth, and fill out the collector form.
    * The password is provided in the **mini/passcode.txt** file (you can change the passcode directly in the file, or after becoming admin you will see a menu option to change it there as well).
* Once the form is submitted, go to the collector table in the database and set your **collector_status** to 2 instead of 1.
* Once you are an admin, click on the Change Password menu option, here you can change the password new users use to sign up, add a new current quarter, or add a new admin (from an existing collector).

## Credits

Copyright 2018 The Regents of the University of California.