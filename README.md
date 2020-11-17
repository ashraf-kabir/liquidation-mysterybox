# Manaknight Code builder

# New Project Setup
1.git clone <repo>

2.Install Docker [MAC Instructions](https://docs.docker.com/docker-for-mac/install/)

[Window 10 instructions](https://docs.docker.com/docker-for-windows/install/)

[Window < 10](https://docs.docker.com/toolbox/toolbox_install_windows/)

3.Get database credentials from Manager

4.Go into configuration.json and replace database config fields

5.Run docker-compose up in terminal

6.Go to page [http://localhost:9000/generator.php](http://localhost:9000/generator.php) to build code

7.To see home page go to [http://localhost:9000](http://localhost:9000)

9.To see admin panel go to [http://localhost:9000/admin/login](http://localhost:9000/admin/login) . Login is admin@manaknight.com/a123456

10.To see member panel go to [http://localhost:9000/member/login](http://localhost:9000/member/login) . Login is member@manaknight.com/a123456

# Mac Setup
1.composer install

2.cd scripts;

3.sudo ./initialize.sh

4.sudo ./build.sh

5.sudo ./generate.sh

6.cd ../release;

7.php -S localhost:9000

# Deployment Steps

```
# New project
# update composer json
composer install # at root folder

# Update configuration.json. Remember to do path ../release and ../mkdcore
./build.sh # This will copy all files to start project and clean out old files

# Start a builder server (server to build project with generator)
cd scripts;
php -S localhost:9001

# Start a project server (server to run application)
cd release;
php -S localhost:9000

# Deploying to Server
# On Server run
cd scripts;
echo "pull";
sudo ./pullCode.sh;
echo "composer";
sudo ./initialize.sh;
echo "build";
sudo ./build.sh;
echo "generate";
sudo ./generate.sh;
sudo rm -rf /var/www/devbluegable.manaknightdigital.com/htdocs/*;
echo "copy";
sudo ./copyToProd.sh;
echo "done";



# Take this file and change path to htdocs copyToProd.dist.sh
sudo ./copyToProd.sh

```

# Configuration Sections
Below I'll explain the meaning of each field
```
    "has_license_key": <boolean> - always true  (dont change)
    "license_key": <string> - generate one from random key on generator.php (change)
    "project_name": <string> - project name (change)
    "powered_by": <string> - (dont change)
    "domain": <string> - (change)
    "locale": <string> - (dont change)
    "language": <string> (dont change)

    "roles": [<Role Array>] - See below for detail (Role in system)

    "models": [<Model Array>] - See below for detail (ORM from codeigniter)

    "portals": [<Portal Array>] - See below for detail (information to build portal)
    "controllers": [<Controller Array>] - See below for detail (information to build controllers)

    "packages": Leave for now TBD
    "libraries": Leave for now TBD

    "routes": {
      "<codeigniter route>": "Path to controller"
    }
    If you need a custom route, put it here

    "marketing": <Marketing Object> - See below for details (Front facing client pages)

    "translations": {
      "<the key refer to in project>": "actual translation"
    }
    This is where you put translations. This helps to change a word in one place over having it everywhere.
    All keys begin with xyz so easy to identify.
    All Table columns will begin with xyz. So add to translation object.
    All buttons are in translation object. I've added all common ones. New ones you need to add yourself.

    i.e. "xyzStatus": "Status"

    "reporting": [Reporting Array] - When user want to generate a report, I have automated process to cut development time.

    "config": {
      "<key>": "<value>",
      "dynamic_config": {
        "<key>": "<value>",
      }
    }
    all config keys should stay. If you need more add to dynamic_config

    "database": {} - check configuration.json. Self explanatory

    "cronjob": {
      "<cron job controller in template>": "<final project cron controller>"
    }

    "copy": {
      "<template file to copy to main project>": "final location in project"
    }
    If you need to override a file completely, use this

```

## Roles
These are the roles in the system. By the requirements, put roles given as instructions.

```
[
  {
    "name": <string> - role name
    "id": <integer> - the id of role that matches database id
  },
]
```

## Models
These are the models in codeigniter. If given model schema by manager, implement model schema.

```
[
  "name": <string> - model name, all lowercase, snakecase
  "timestamp": <boolean> - if we want timestamp on model
  "migration": <b> - if migration script required
  "field": [<Field Array>] - See below for Field array (set validation rules for codeigniter)
  "method": <string> - any custom method this model needs, test it works then make it into 1 line
  "join": [Join Array] - See below for Join array (if table will need to join another table, fill in)
  "mapping: {
    "<field in model that can have multiple options>: {
      "key": "value"
    }
  }
  This mapping is used when we have a form that is a dropdown box like status
  "pre": <string> - any custom code to run before inserting row into database. Put code in 1 line
  "post": <string> - any custom code to run before updating row into database. Put code in 1 line
  "count": <string> - any custom code to run before calling count row in database. Put code in 1 line
  "override": <string> - if model has too much custom code, put path to file in template/custom for your model and builder use your model
  "unique": [<string Array>] - put the field name that unique on model. unique index will be added
  "seed": [<Seed Object>] - If there are see data, put it in an object without id
]
```

## Join
When a join maybe needed, this array will make the function available to join
```
[
  {
    "name": "<other table name>",
    "field": "<other table foreign key>"
  }
]
```

## Field
These are the fields of database table. Look at codeigniter validation rules
```
ie. ["action", "string",[{"limit": 50}], "Action", "required|max[50]", "required|max[50]"]

["<field name>", "<field type>",[<additional database constraints>], "<label>", "<codeigniter validation rule to add>", "<codeigniter validation rule to edit>"]

field name - the field name in schema, use snakecase for all names. Make sure spelling correct
field type
  password - basically string but when form is generated, it process data as password
  image|width|height|boundary_width|boundary_height - ie.image|250|250|500|500
  - width and height is size of image cropper, boundary width and height is the final image size
  file - is text field but when form generated, it will upload file to text field. Make sure to add a file_id to model as well. This way we can reference file in image table
  text - text type
  datetime - date time type
  date - date type
  string - string type. You can set limit to # of characters
  integer - integer type
  float - float type

label - The text to show on forms

codeigniter validation rules - same as regular codeigniter
```

## Portal
Information needed to build portal.
```
[
  {
    "name": <string> - name of portal. Lowercase. One word
    "role": <string> - which role can access this portal
    "model": <string> - which model can access portal. Usually it is user_model unless otherwise.
    "login_type": <'login', 'login_only', 'login_no_social',

    'login_full_function'> - one of these
      login - regular login to portal(reset, forgot, login, register) no social
      login_only - only login function with social login
      login_no_social - only login function no social
      login_full_function - everything
    "forgot": <boolean> - forget password active?
    "reset": <boolean> - reset password active?
    "register": <boolean> - register active?
    "api_auth": <b> - create auth functions with api as well
    "api": <b> - if api version of portal available
    "profile_page_fields": <array> - overwrite default profile page fields leave blank to use system default
    "middleware": ["affilate", "auth", "acl", "maintenance"] - these are middleware that apply. ask manager
    "js": [<string>] - the js files this portal needs. Add file to assets and here
    "css": [<string>] - the css files this portal needs. Add file to assets and here
    "menu": {
      "<menu name user sees>: "<route url>",
      "<main menu name user sees>: {
        "submenu name": "<route url>"
      }
    }
    - if pagination required, make sure route url has /0 at end
  }
]
```

## Controller
Information needed to build a controller
```
[
  {
    "route": <string> - base route of controller ie. /users always plural
    "name": <string> - name of page. One word
    "page_name": <string> - name of page that shows on table and forms
    "model": <string> - model that is used on this page
    "controller": <string> - controller file name. Namespace it by portal ie. Admin_user_controller.php
    "api_controller": <string> - controller file name. Namespace it by portal ie. Admin_api_user_controller.php
    "override": <string> - file name if we are overriding whole controller
    "override_add": <string> - php code to override add function,
    "override_edit": <string> - php code to override edit function,
    "override_view":  <string> - php code to override view function,
    "override_list":  <string> - php code to override list function,
    "override_add_view_model": <string> - php code to override add function,
    "override_edit_view_model": <string> - php code to override edit function,
    "override_view_view_model":  <string> - php code to override view function,
    "override_list_view_model":  <string> - php code to override list function,
    "api": <b> - if api for crud function is setup,
    "frontend": <b> - if api this should be false. Then no view made,
    "paginate": <b> - pagination or not,
    "paginate_join": <string> - paginate join text
    "activity_log": <boolean> - true or false saves activity log
    "portal": <string> - portal controller belong too lowercase
    "is_crud": <b> - setup add, edit, list, delete for controller
    "method_edit_pre": <string> - php code to be called before edit function
    "is_add": <b> - add add function to controller
    "is_edit":  <b> - add edit function to controller
    "is_delete":  <b> - add delete function to controller (Change status to 0 instead of 1)
    "is_real_delete":  <b> - add delete function to controller (Actually delete row)
    "is_list":  <b> - add list function to controller
    "is_view":  <b> - add view function to controller
    "import":  <b> - add import function to controller (user can import csv to add to table)
    "export":  <b> - add export function to controller (user can export table)
    "is_filter":  <b> - add filter table to list function in controller (filter form)
    "all_records":  <b> - if true, return all rows and user can view all rows. If false, user can only see rows belong to them
    "active_only":  <b> - if true, only status = 1 will be returned, if false all returned
    "method": <string> - custom method you add to controller. PHP code 1 line
    "method_add": <string> - add php code to insert data array. So if you need to do something custom this is fast
    "method_edit":  <string> - add php code to update data array. So if you need to do something custom this is fast
    "method_list":  <string> - add php code to list function. So if you need to do something custom this is fast
    "method_view":  <string> - add php code to view. So if you need to do something custom this is fast
    "method_add_success":  <string> - add php code after insert successful. So if you need to do something custom this is fast
    "method_edit_success": <string> - add php code after edit successful. So if you need to do something custom this is fast
    "method_list_success": <string> - add php code after list successful. So if you need to do something custom this is fast
    "method_view_success": <string> - add php code after view successful. So if you need to do something custom this is fast
    "method_delete_success": <string> - add php code after delete successful. So if you need to do something custom this is fast
    "custom_view_add": <string> - override add function
    "custom_view_edit": <string> - override edit function
    "custom_view_list": <string> - override list function
    "custom_view_view": <string> - override view function
    "listing_fields_api": [<string>] - fields that are return by calling listing api
    "listing_headers": [<string>] - table header label(not fields) in table view
    "listing_rows": [<string>] - specify the fields that will be in each column. We also have piping here to change its look
    ie.
    "id|integer"  - return field as integer
    "image|image" - return string as image element
    "complex|email:first_name:last_name:role_id:phone" - combine multiple fields in 1 column row. complex| tell system its complex. Colon seperate fields
    "listing_actions": [<string>] - if you need a custom button and php to trigger it, seperate with a |
    i.e. "Approve|/users/approve/|($data->status == 0)?TRUE:FALSE"
    "filter_fields": [<string>] - fields you will see in filter form
    "add_fields": [<string>] - add field in add form
    "edit_fields": [<string>] - add field in edit form
    "view_fields": [<string>] - add field in view page
    "dynamic_mapping_add" : {},- similar to dynamic mapping inject data in the controller add method instead of the constructor
    "dynamic_mapping_edit" : {}, - similar to dynamic mapping inject data in the controller edit method instead of the constructor
    "dynamic_mapping_view" : {},- similar to dynamic mapping inject data in the controller view  method instead of the constructor
    "dynamic_mapping":
    ```
    {
      "sector": {
          "model": "<model_name>",
          "function": "get_sectors",
          "code": "\tpublic function get_sectors ()
          {
            $this->load->model('model_name_model');
            $result = $this->model_name_model->raw_query('SELECT DISTINCT(sector) as sector FROM voters');
            $sectors = [];
            foreach ($result->result() as $key => $row) {
                if (strlen($row->sector) > 0)
              {
                  $sectors[$row->sector] = $row->sector;
              }
            }
            return $sectors;
          }"
      }
    },
    ```
    This value will be loaded in constructor. So in view, you can have dynamically loaded mapping
  }
]
```

## Marketing Page
This is the place to move custom marketing pages from template/custom folder to the main project.

```
{
  "pages": {
    "<template controller file path>": "<real project path to copy too>"
  },
  "views": {
    "<template view file path>": "<real project path to copy too>"
  },
  "routes": {
    "<custom codeigniter route>": "<path to controller>"
  },
  "footer": "<template footer file path>",
  "header": "<template header file path>",
  "js": ["<js file in asset you need to include in header/footer>"],
  "css": ["<css file in asset you need to include in header/footer>"]
}
```

## Reporting
This is the place to make generic reporting pages.

```
[
  {
    "name": <string> - name of page. Capitalize first letter and snakecase other words
    "portal": <string> - which portal this page on
    "page_name": <string> - Page label user sees
    "route": <string> - Codeigniter route path
    "model": <string> - model used
    "filter_field": [
      {
        "<field_name>", "<field type>", [], "<label>", "validation add", "validation edit"
      }
    ]
    These are fields that show up in filter search form

    "pre_controller": <string> - php code to run before query database
    "parameter": [] - default empty array. If you need to pass variable into reporting service do it here
    "query": <string> - query that you will run to get report.
    ie.
    $start_date = $this->_start_date;
    $end_date = $this->_end_date;
    $query = $this->_model->raw_prepare_query(\"SELECT created_at, COUNT(*) as num FROM user WHERE (created_at>=? AND created_at<=?) GROUP BY created_at\", [$start_date, $end_date]);

    "result": <string> - after query is run, build the report row here
    ie.
    $data[] = ['created_at' => $row->created_at, 'num' => $row->num];

    "post": <string> - after data rows are generated, any additional php work needed will go here

    "display": <table or csv> - if table show table on same page. If CSV download as CSV

    "filename": <string> - file name without extension

    "header": [<string>] - if display=table, these are table header columns

    "field": [<string>] - if display=table, these are table row columns
  }
]
```

## add , edit , filter fields pipes

You can use pipes to specify  how  data will be rendered or the type of input type you will get i.e form controls if you dont put a pipe the code builder will use datatype specified on the model

1. Auto complete pipe
can be used for add, edit and filter fields
for example:
"email|autocomplete:table_name:field_search:field_label_field:field_value_field"
1. email the field
2. autocomplete to generate autocomplete input
3. table_name table you want to search
4. field_search table -> field to search
5. field_label_field ->field to show user
6. field_value_field -> field to use as value example user_id


```
[
  {
     "add_fields": ["role_id", "account_manager_id|autocomplete:users:first_name:first_name:id","status","company_name"],
  },
]
```
## Local Development

You can maintain a local env.json in your project root. This allows you to maintain separate
production and local development configurations. You need to add env.json to .git ignore (important)

```
 {
    "config": {
        "mode": "development",
        "platform_name": "manaknight",
        "from_email": "ryan@manaknightdigital.com",
        "mail_domain": "",
        "mailgun_key": "",
        "twilio_phone_number":  "",
        "twilio_sid":  "",
        "twilio_token":  "",
        "stripe_publish_key": "",
        "stripe_secret_key": "",
        "copyright": "Copyright © 2019 Manaknightdigital Inc. All rights reserved.",
        "powered_by": "Powered By <a href=\"https://manaknightdigital.com\" target=\"__blank\">Manaknightdigital Inc.</a>",
        "email_smtp": {
            "protocol": "smtp",
            "smtp_host": "smtp.mailtrap.io",
            "smtp_port": 2525,
            "smtp_user": "7e8f296dd6a493",
            "smtp_pass": "ac4e30d39321bc",
            "crlf": "\r\n",
            "newline": "\r\n",
            "mailtype": "html",
            "charset": "utf-8"
        },
        "csrf_exclude_uris": ["v1/api.*+", "member/login", "member/forgot", "member/reset/.*", "member/register", "admin/login", "admin/forgot", "admin/reset/.*", "admin/register"],
        "encryption_key": "1n4uUX6d1Us3quFXKA7ZmqFIaQVC5MtgXlV9ho8F",
        "subclass_prefix": "Manaknight_",
        "migration_number": 9377770345344,
        "language": "english",
        "base_url": "http://localhost:9000",
        "site_title" : "Manaknight Saas",
        "company" : "Manaknight Inc.",
        "image_upload": "s3",
        "file_upload": "local",
        "upload_byte_size_limit": "1000000000",
        "dynamic_config": {
            "jwt_key" : "544e68498c28710b10af9dc9c7c9530951438f23",
            "jwt_expire_at" : 3600,
            "jwt_refresh_expire_at" : 7200,
            "google_client_id" : "466040660536-l5pq116jdp6cs3b58a7k4t52f5lpa5se.apps.googleusercontent.com",
            "google_client_secret" : "W_bYoRhly8pf0XJ1IUsk8wC_",
            "google_redirect_uri" : "http://localhost:9000/google",
            "application_name" : "",
            "facebook_client_id" : "",
            "facebook_client_secret" : "",
            "facebook_redirect_uri" : "",
            "facebook_oath_uri": "https://www.facebook.com/v3.0/dialog/oauth",
            "aws_version": "latest",
            "aws_region": "us-east-2",
            "aws_key": "AKIAJD27VH2ITMIWT4IQ",
            "aws_secret": "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",
            "aws_bucket": "com.konfor.images"
        }
    },
    "database": {
        "adapter": "mysql",
        "port": 3306,
        "dsn": "",
        "hostname": "localhost",
        "username": "root",
        "password": "",
        "database": "trading",
        "dbdriver": "mysqli",
        "dbprefix": "",
        "pconnect": false,
        "db_debug": false,
        "cache_on": false,
        "cachedir": "",
        "char_set": "utf8",
        "dbcollat": "utf8_general_ci",
        "swap_pre": "",
        "encrypt": false,
        "compress": false,
        "stricton": false,
        "failover": [],
        "save_queries": true
    }
}
```

```
## Stripe Module

You can activate stripe module in pag

```
 {
    
       
   
}
```

## Reverse Copy
If you want to copy files from project back into custom, add file path to reverse_copy.

Put Release file name as key, Custom folder file path as value

Then run ./reverseCopy.sh in scripts folder

## DRAFT MODE
Sometimes you want to edit raw php files in project and copy them back into custom.

I made this easy for you.

In the php files inside release, add the keyword DRAFTMODE.

Then in script folder, run ./draft.sh

This script does 2 things:

1.It copy all files with DRAFTMODE into mkdcore/custom/generated folder.

2.It removes DRAFTMODE from the file in mkdcore/custom/generated folder.

3.It prints the lines you need to copy into copy object.
```
I.E.
"../mkdcore/custom/generated/release_application_controllers_Guest_Home_controller.php": "../release/application/controllers/Guest/Home_controller.php",
```



## Stripe ACH 
If you want to send ach invoice to customer and  attach webhook for it's response
 
You need to do following steps in order to get it correctly 

1) First add service in copy object Stripe_ach_invoice_service.
2) Load Service where you want to you.
3) Set config using $this->stripe_ach_invoice_service->set_config($this->config);
4) Now you can send invoice using send_ach_invoice_sale_order function of above service if  
    requires 4 Parameters  return Invoice ID as response

  1) Customer Name
  2) Customer Email 
  3) Customer Phone 
  4) Total 
  5) Days until invoice due

5) Don't forget to update your webhook response (mkdcore/source/payment/Stripe_webhooks_api_controller.php =>  function handle_invoice_paid_method )


## Generate Barcode
If you want to generate barcode of any string or number use Barcode_service
 
You need to do following steps in order to get it correctly 

1) First add service in copy object Barcode_service in configuration.json.
2) Load Service where you want to you.
3) use generate_png_barcode function requires 2 Parameters return image url;

    1) String or Number for barcode (required)
    2) Manually name the image (optional)
4) use this function to upload this file to s3 $this->upload_image_with_s3($image_url) return 
upload image url 

 

## Signature 

1) first we need to place this signature html
<div class="wrapper_signature_div">
    <canvas id="signature-pad" class="signature-pad" width=400 height=200></canvas>
</div>
<br>
<button type="button" class="btn btn-success" id="save-png">Save Signature</button> 
<button type="button" class="btn btn-danger " id="clear">Clear</button>

<!-- <div id="signature-div" ></div>  -->
  
<textarea id="signature64" name="signature_in_b64" style="display: none"></textarea>
2) Load Signature Assests from js and css
3) Generate Image and upload image to s3 like we did for barcode 
$folderPath = "uploads/"; 
$image_parts = explode(";base64,", $this->input->post('signature_in_b64', TRUE) ); 
$image_type_aux = explode("image/", $image_parts[0]); 
$image_type = $image_type_aux[1]; 
$image_base64 = base64_decode($image_parts[1]); 
$file = $folderPath . uniqid() . '.'.$image_type; 
file_put_contents($file, $image_base64);

/**
  *  Upload Image to S3
  * 
*/ 
$signature_image  = $this->upload_image_with_s3($file);