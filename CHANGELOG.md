# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

MAJOR version when you make incompatible API changes,

MINOR version when you add functionality in a backwards-compatible manner, and

PATCH version when you make backwards-compatible bug fixes.

## [Unreleased]

## [0.0.32] - 2019-12-2
- added docker compose to project
- added docker compose other config files
- Removed migration builder. Never used it
- Cleaned up unused fields in configuration.json
- Updated model_builder to have migrations use the same numbers each time
- Added port to database.php
- Set default port to server mysql so easier for new person to use Saas_builder
- Changed setting.php back to calling database instead of config file to remove confusion
- Fixed spacing in model.php

## [0.0.31] - 2019-7-24
- added redirect to guest controller
- added import to controller builder
- added import upload csv code to image_builder
- update manaknight_model to get schema, raw_no_error_query, get_last_id, verify_field_type, validate_date
- update model_builder to fix mapping that are numbers to not put quote around it
- update model_builder to make all dates null on migration
- updated mkd-image-gallery to support csv upload now
- added new abstraction for csv import to csv_import_service
- updated all list view to have import
- added keys import and export to configuration.json
- added new translation for import
## [0.0.30] - 2019-7-20
- Added new fields to configuration.json all_record and active_only
- updated configuration.json phone number field in user to include country code
- updated configuration.json token status
- updated configuration.json method_edit_pre
- updated configuration.json verify email and sms
- added twilio to composer.json
- marketing_builder forgot to initialize routes
- updated all controllers to use portal specific view models
- updated all controllers to use logic for active only
- updated all controllers to use logic for all records or specific to user
- added image_or_file function to List_paginate_view_model, List View Model so it can figure out if its file or image before showing it
- added include twilio to sms service
- added new function to handle float filters to core.js
- updated controller builder to have better float and number filter validation including negative number and decimal control
- added back redirect to guest_controller
- added where statement to get_all command in manaknight_model
- forgot to handle is_real_delete the same way as is_delete in controller_builder in route and making controller
- added method method_edit_pre to all controller
- in controller builder remove boolean extra html code
- in controller builder added type imagefile


## [0.0.29] - 2019-7-8
- added flag for frontend to config for frontend version
- fixed pagination bug in controller files where order of parameters was off
- Added file upload file
- fixed all translation missing xyz to have it now
- changed model builder to support file
- added mime_service
- Added s3 upload image and regular file
- Added new flag in config for type of upload

## [0.0.28] - 2019-6-23
- added functionality to join table and allow fields to be used in table
- added new alias for fields that overlap and id
- updated all pagination templates to support new pagination
- configuration file now supports custom pagination
- updated manaknight model to support this

## [0.0.27] - 2019-6-23
- Changed configuration.json to allow listing header, row display, api fields, listing actions
- can customize table without coding
- ignore guest controllers not default ones
- updated controller builder to support new table features
- updated _main.scss so the image is shown in table

## [0.0.26] - 2019-6-22
- Added marketing page automation
- can add js/css and compile them in marketing page footer and header
- Added MarketingBuilder and put it in App_builder
- Fixed bug portal builder not deleting files
- Setup template custom
- Added code to skeleton builder to not remove welcome and guest controller

## [0.0.25] - 2019-6-22
- in production mode, aggregate all js and css file into 1 portal js and css file
- remove all development js/css files
- moved scss out of assets and moved to templates

## [0.0.24] - 2019-6-22
- Added image asset management
- Updated footer to allow image management
- Updated configuration.json to allow images
- added SCSS for image gallery
- added vendor library for image gallery
- added imagebuilder
- updated model factory to support images
- added image viewmodel and controller

## [0.0.23] - 2019-6-13
- changed password bycrypt from equation $2y$ to $2b$ so its easier for bycrypt from nodejs to read
- updated all configuration and factory/service

## [0.0.22] - 2019-6-13
- Added translate text to all builders
- replace heading in controller with page name
- add back hash.php to powerby builder when no license key is present
- for referral model add user type
- for user service add referral type
- made all views translatable
- added default profile controller + view

## [0.0.21] - 2019-6-10
- added in controller method success to execute after successful call
- added image gallery modal to layout footer, not complete yet
- adding welcome controller to git
- added css for modal to be larger
- added setting to portal controller
## [0.0.20] - 2019-6-6
- Added in abstract cron controller
- Added backup Code Cronjob
- Added backup Database Cronjob
- Added Remove Token Cronjob
- Updated config file to support cronjob
- Added place to reference cron times
- Made cron builder

## [0.0.19] - 2019-6-6
- working reporting class/route/controller/view

## [0.0.18] - 2019-6-04
- made kill code
- made license checking code
- made remove kill code
- made new router template
- added all new builders to app builder

## [0.0.17] - 2019-6-04
- fixed token api token in controllers
- removed requirement to initialize model in token service
- fixed token middleware

## [0.0.16] - 2019-6-04
- Added Setting to configuration and new ui
- Added welcome to core
- added index.php to core
- added welcome to skeleton builder
- added guest controller to skeleton builder

## [0.0.15] - 2019-6-04
- added generator random key code
- removed maintanence from index.php

## [0.0.14] - 2019-6-04
- added license server code

## [0.0.13] - 2019-6-03
- added toast bar to layout and scss
- moved guest controller to new folder
- added middleware to controller
- added token service to api auth controllers
- added token service
- fixed a lot of files missing the codeigniter header line
- added table strips to all tables
- added setting controller to system
- caches settings now
- all controllers load setting now
- added all token keys to mapping
- added settings to guest controller
- added raw preparied query to manaknight model
- added setting update by api and javascript
- added report service


## [0.0.12] - 2019-6-02
- added back affilate to register function
- added affilate middleware
- added in login only controller
- added in guest controller to skeleton builder

## [0.0.11] - 2019-6-02
- fixed and tested full crud works now
- removed the sidebar button not showing on mobile
- added field type password to configuration so edit and add view show password field and model change it back to string
- added styling of pagination to view model
- forgot to add/edit heading in add and edit view model

## [0.0.10] - 2019-6-02
- add vendor js code to portal
- add vendor css code to portal
- added custom template folder
- add in dashboard code
- fixed font size on copyright
- added facebook login access token check only

## [0.0.9] - 2019-5-30
- fixed phinx missing adapter and port
- moved all controller code into controller folder
- moved all portal code into portal folder
- moved all core code into core folder
- moved all model code into model folder

## [0.0.8] - 2019-5-30
- fixed all crud bugs

## [0.0.7] - 2019-5-30
- make auth flow all apis from config
- make controllers from api as well
- add to json to view model for view
- add to json to view model for list
- add to json to view model for paginate
- fixed controller builder bugs
- fixed controller templates
- added portal api controller
- moved all api controller code to controller folder
- added token and token acl middleware
- added 2 middleware to skeleton builder
- added api auth routes to portal builder
- added api routes controller builder
- fixed bug on count in manaknight model
- removed unecessary code in maintanence middleware
- cleaned up model.php count function
- set maintanence to 0 in index.php
- added api flag to portal
- added api flag to controller

## [0.0.6] - 2019-5-30
- updated clean script
- updated frontend script
- added rebuild to generator script
- index.php corrected error reporting
- not auto loading database in auto loader
- add language spanish to language files

## [0.0.5] - 2019-5-30
- moved menus to portal instead of controller
- portal builds layout now
- added page_name to controller so easier to check on layout
- fixed all login/reset/register/forgot css/scss
- added in all user auth flow controller/builder/views
- changed config_builder to have proper dynamic config keys
- model_builder added create/update time to seeds properly
- model_builder added index unique now
- all views and controller can add custom of each type(add/edit/view/list)
- added working layouts header and footer now to project
- load database from manaknight controller instead of autoloading it
- fixed typo in manaknight model
- fixed migration files to not crash if tables exist
- fixed seed files so they truncate everything before inserting
- portal controller now sets page name from config
- added facebook_service
- added google_service
- added user service and fixed all bugs in it
- seperated social login from regular login controllers

## [0.0.4] - 2019-5-9
- added seeds to configuration.json and model_builder
- added reset database script
- copy_builder
- removed sms and email model code from skeleton and put it in config
- email, sms, user model code now in config instead of template files
- updated migration_file.php
- moved mapping back to model and reference in view model
- removed user model from user module builder
- added copy paste controller/model json on generator file
- added profile_id and type field to user factory and user service
- added token to config and moved reset token into token instead of user model
- added member/admin operation to config
- added setting seed values
- added unique field to config file
- added refer log to config file

## [0.0.3] - 2019-5-9
- Added Powerby_builder
- Added Destroy Code
- Fixed path for middleware in manaknight_controller.php and all controller templates
- Phinx_builder
- Added boolean field to controller_builder
- Added phinx.php to gitignore
- Added System Router.php to template files so replaces it properly

## [0.0.2] - 2019-5-8
- BUG: Model_builder was deleting all files in seed as well
- Database_builder

## [0.0.1] - 2019-5-8
- initial commit
- working CRUD builder
- working CRUD for pages add/edit/view/delete/list/filter/model/routes/config