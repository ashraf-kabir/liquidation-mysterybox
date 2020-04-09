{
    "has_license_key": true,
    "license_key": "4097fbd4f340955de76ca555c201b185cf9d6921d977301b05cdddeae4af54f924f0508cd0f7ca66",
    "project_name": "Manaknightdigital SAAS",
    "powered_by": "Manaknightdigital Inc.",
    "domain": "manaknight.com",
    "locale": false,
    "language": "english",
    "roles": [
      {{{roles}}}
    ],
    "models": [
      {
        "name": "setting",
        "timestamp": true,
        "migration": true,
        "field": [
            ["id", "integer",[], "xyzID", "", ""],
            ["key", "string",[{"limit" : 50}], "xyzSetting Field", "required", ""],
            ["type", "integer",[], "xyzSetting Type", "required", ""],
            ["value", "text",[], "xyzSetting Value", "required", "required"]
        ],
        "method": "\tpublic function get_config_settings()\n\t{\n\t\t$this->db->from('setting');\n\t\t$results = $this->db->get()->result();\n\t\t$data = [];\n\t\tforeach ($results as $key => $value)\n\t\t{\n\t\t\t$data[$value->key] = $value->value;\n\t\t}\n\t\treturn $data;\n\t}\n",
        "join": [],
        "mapping": {
            "type": {"0": "xyzText", "1": "xyzSelect", "2": "xyzNumber", "3": "xyzImage", "4": "xyzRead_only"},
            "maintenance": {"0": "xyzNo", "1": "xyzYes"}
        },
        "pre": "",
        "post": "if(isset($data['key']))\n\t\t{\n\t\t\tunset($data['key']);\n\t\t}\n",
        "count": "",
        "override": "",
        "unique": ["key"],
        "seed":[
            {"key": "site_name", "type": 0, "value": "Manaknight Inc"},
            {"key": "site_logo", "type": 0, "value": "https://manaknightdigital.com/assets/img/logo.png"},
            {"key": "maintenance", "type": 1, "value": "0"},
            {"key": "version", "type": 0, "value":  "1.0.0"},
            {"key": "copyright", "type": 0, "value": "Copyright © 2019 Manaknightdigital Inc. All rights reserved."},
            {"key": "license_key", "type": 4, "value": "4097fbd4f340955de76ca555c201b185cf9d6921d977301b05cdddeae4af54f924f0508cd0f7ca66"}
        ]
    },
    {
        "name": "role",
        "timestamp": true,
        "migration": true,
        "field": [
            ["id", "integer",[], "ID", "", ""],
            ["name", "string",[{"limit" : 16}], "xyzRole Name", "required", "required"]
        ],
        "method": "",
        "join": [],
        "mapping": {},
        "pre": "",
        "post": "",
        "count": "",
        "override": "",
        "unique": ["name"],
        "seed":[
            {"name": "member"},
            {"name": "admin"},
            {"name": "system"}
        ]
    },
    {
        "name": "refer_log",
        "timestamp": true,
        "migration": true,
        "field": [
            ["id", "integer",[], "xyzID", "", ""],
            ["user_id", "integer",[], "xyzReferree User", "required|integer", ""],
            ["referrer_user_id", "integer",[], "xyzReferrer User", "required|integer", ""],
            ["type", "integer",[], "xyxType", "required|integer", "required|integer"],
            ["status", "integer",[], "xyzStatus", "required|integer", ""]
        ],
        "method": "",
        "join": [{
            "name": "user",
            "field": "user_id"
        }, {
            "name": "referrer",
            "field": "referrer_user_id"
        }],
        "mapping": {
            "status": {"0": "xyzPending", "1": "xyzConfirmed", "2": "xyzPaid"},
            "type": {"0":"user"}
        },
        "pre": "$data['status'] = 0;\n",
        "post": "",
        "count": "",
        "unique": [],
        "seed": [],
        "override": ""
    },
    {
        "name": "user",
        "timestamp": true,
        "migration": true,
        "unique": ["email"],
        "field": [
            ["id", "integer",[], "ID", "", ""],
            ["email", "string", [{"limit":255}], "xyzEmail" , "trim|required|valid_email|is_unique[user.email]", "trim|required|valid_email"],
            ["password", "password", [{"limit":255}], "xyzPassword" , "required", ""],
            ["type", "string", [{"limit":1}], "xyzProfile Type" , "", ""],
            ["first_name", "string", [{"limit":50}], "xyzFirst Name" , "required", "required"],
            ["last_name", "string", [{"limit":50}], "xyzLast Name" , "required", "required"],
            ["phone", "string", [{"limit":50}], "xyzPhone #" , "required", "required"],
            ["image", "image|250|250|500|500", [], "xyzImage" , "", ""],
            ["image_id", "integer", [], "xyzImage ID" , "", ""],
            ["refer", "string", [{"limit":50}], "xyzRefer Code" , "", ""],
            ["profile_id", "integer",[], "xyzProfile", "", ""],
            ["verify", "integer",[], "xyzVerified", "", ""],
            ["role_id", "integer",[], "xyzRole", "required|in_list[1,2,3]", "required|in_list[1,2,3]"],
            ["stripe_id", "string", [{"limit":255}], "xyzStripe Id" , "", ""],
            ["status", "integer",[], "xyzStatus", "", "required|in_list[0,1,2]"]
        ],
        "join": [],
        "method": "\tpublic function autocomplete_email($email)\n\t{\n\t\t$this->db->like('email', $email);\n\t\t$query = $this->db->get($this->_table);\n\t\t$result = [];\n\t\tforeach ($query->result() as $row)\n\t\t{\n\t\t\t$result[] = [\n\t\t\t\t'id' => $row->id,\n\t\t\t\t'email' => $row->email\n\t\t\t];\n\t\t}\n\t\treturn $result;\n\t}\n",
        "mapping": {
            "verify": { "0": "xyzNot verified", "1": "xyzVerified"},
            "status": {"0": "xyzInactive", "1": "xyzActive", "2": "xyzSuspend"},
            "role_id": {"1": "xyzMember", "2": "xyzAdmin", "3": "xyzSystem"}
        },
        "pre": "$data['image'] = 'https://i.imgur.com/AzJ7DRw.png';\n\t\t$data['refer'] = uniqid();\n\t\t$data['status'] = 1;\n\t\t$data['verify'] = 0;\n\t\t$data['stripe_id'] = '';\n\n\t\tif(!isset($data['profile_id']))\n\t\t{\n\t\t\t$data['profile_id'] = 0;\n\t\t}\n\n\t\tif(!isset($data['type']))\n\t\t{\n\t\t\t$data['type'] = 'n';\n\t\t}\n\t\tif (strpos($data['phone'], '1') != 0)\n\t\t{\n\t\t\t$data['phone'] = '1' + $data['phone'];\n\t\t}\n",
        "post": "if(isset($data['password']) && strlen($data['password']) < 1)\n\t\t{\n\t\t\tunset($data['password']);\n\t\t}\n\n\t\tif(isset($data['image']) && strlen($data['image']) < 1)\n\t\t{\n\t\t\tunset($data['image']);\n\t\t}\n",
        "count": "",
        "override": "",
        "seed":[
            {"email": "admin@manaknight.com","password": "str_replace('$2y$', '$2b$', password_hash('a123456', PASSWORD_BCRYPT))","type": "n","first_name": "Admin","last_name": "Admin","phone": "12345678","image": "https://i.imgur.com/AzJ7DRw.png","image_id": 1, "refer": "admin","profile_id": 0,"verify": 1,"role_id": 2,"stripe_id": "","status": 1},
            {"email": "member@manaknight.com","password": "str_replace('$2y$', '$2b$', password_hash('a123456', PASSWORD_BCRYPT))","type": "n","first_name": "Admin","last_name": "Admin","phone": "12345678","image": "https://i.imgur.com/AzJ7DRw.png","image_id": 1, "refer": "member","profile_id": 0,"verify": 1,"role_id": 1,"stripe_id": "","status": 1}
        ]
    },
    {
        "name": "token",
        "timestamp": false,
        "migration": true,
        "field": [
            ["token", "text", [], "xyzToken" , "required", "required"],
            ["data", "text", [], "xyzData" , "required", "required"],
            ["type", "integer",[], "xyzToken Type", "required|integer", "required|integer"],
            ["user_id", "integer",[], "xyzUser", "required|integer", "required|integer"],
            ["ttl", "integer",[], "xyzTime To Live", "required|integer", "required|integer"],
            ["issue_at", "datetime",[], "xyzIssue at", "required", "required"],
            ["expire_at", "datetime",[], "xyzExpire at", "required", "required"],
            ["status", "integer",[], "xyzStatus", "required|integer", "required|integer"]
        ],
        "join": [{
            "name": "user",
            "field": "user_id"
        }],
        "method": "\tconst NOT_FOUND = 0;\n\n\tconst EXPIRED = 1;\n\n\tconst FOUND = 2;\n\n\tpublic function create_verify_token ($user_id, $phone)\n\t{\n\t\t$code = rand(100000,999999);\n\t\t$expire_at = date('Y-m-j H:i:s', time() + 60 * 5);\n\t\t$token = $this->create([\n\t\t\t'token' => $code,\n\t\t\t'data' => json_encode([\n\t\t\t\t'code' => $code,\n\t\t\t\t'phone' => $phone\n\t\t\t]),\n\t\t\t'type' => 6,\n\t\t\t'user_id' => $user_id,\n\t\t\t'ttl' => 5 * 60,\n\t\t\t'issue_at' => date('Y-m-j H:i:s'),\n\t\t\t'expire_at' => $expire_at,\n\t\t\t'status' => 1\n\t\t]);\n\t\treturn $code;\n\t}\n\n\tpublic function check_verify_token ($code)\n\t{\n\t\t$exist = $this->get_by_field('token', $code);\n\t\tif (!$exist)\n\t\t{\n\t\t\treturn NOT_FOUND;\n\t\t}\n\t\t$expire_at = strtotime($exist->expire_at);\n\n\t\tif ($expire_at < time())\n\t\t{\n\t\t\treturn EXPIRED;\n\t\t}\n\t\treturn json_decode($exist->data, TRUE);\n\t}\n",
        "mapping": {
            "status": { "0": "xyzInactive", "1": "xyzActive"},
            "type": {"0": "xyzForgot_token", "1": "xyzAccess_token", "2": "xyzRefresh_token", "3": "xyzOther", "4": "xyzApi_key", "5": "xyzApi_secret", "6": "xyzVerify"}
        },
        "pre": "$data['status'] = 1;\n",
        "post": "",
        "count": "",
        "unique": [],
        "seed": [],
        "override": ""
    },
    {
        "name": "black_list_token",
        "timestamp": false,
        "migration": true,
        "field": [
            ["token", "text", [], "xyzToken" , "required", "required"]
        ],
        "join": [],
        "method": "",
        "mapping": {},
        "pre": "",
        "post": "",
        "count": "",
        "unique": [],
        "seed": [],
        "override": ""
    },
    {
        "name": "email",
        "timestamp": true,
        "migration": true,
        "unique": ["slug"],
        "field": [
            ["slug", "string",[{"limit" : 50}], "xyzEmail Type" , "required|is_unique[email.slug]", ""],
            ["subject", "text", [], "xyzSubject" , "required", "required"],
            ["tag", "text", [], "xyzReplacement Tags" , "required", ""],
            ["html", "text", [], "xyzEmail Body" , "required", "required"]
        ],
        "join": [],
        "method": "\tpublic function get_template($slug,$data)\n\t{\n\t\t$this->db->from('email');\n\t\t$this->db->where('slug',$slug,TRUE);\n\t\t$template=$this->db->get()->row();\n\t\tif(!$template)\n\t\t{\n\t\t\treturn FALSE;\n\t\t}\n\t\t$tags_raw=$template->tag;\n\t\t$tags=explode(',',$tags_raw);\n\t\t$template->subject=$this->inject_substitute($template->subject,$tags,$data);\n\t\t$template->html=$this->inject_substitute($template->html,$tags,$data);\n\t\treturn $template;\n\t}\n\n\tpublic function inject_substitute($raw, $tags, $data) \n\t{\n\t\tforeach ($data as $key => $value) \n\t\t{\n\t\t\tif (in_array($key, $tags))\n\t\t\t{\n\t\t\t\t$raw = str_replace('{{{' . $key . '}}}', $value, $raw);\n\t\t\t}\n\t\t}\n\t\treturn $raw;\n\t}\n",
        "mapping": {
        },
        "pre": "",
        "post": "if(isset($data['slug']))\n\t\t{\n\t\t\tunset($data['slug']);\n\t\t}\n",
        "count": "",
        "override": "",
        "seed": [{
            "slug": "reset-password",
            "subject": "Reset your password",
            "tag": "email,reset_token,link",
            "html": "Hi {{{email}}},<br/>You have requested to reset your password. Please click the link below to reset it.<br/><a href=\"{{{link}}}/{{{reset_token}}}\">Link</a>. <br/>Thanks,<br/> Admin"
        }, {
            "slug" : "register",
            "subject" : "Register",
            "tag": "email",
            "html" : "Hi {{{email}}},<br/>Thanks for registering on our platform. <br/>Thanks,<br/> Admin"
        }, {
            "slug" : "confirm-password",
            "subject" : "Confirm your account",
            "tag": "email,confirm_token,link",
            "html" : "Hi {{{email}}},<br/>Please click the link below to confirm your account.<br/><a href=\"{{{link}}}/{{{confirm_token}}}\">Link</a>Thanks,<br/> Admin"
        }, {
            "slug": "verify",
            "subject": "verify account with konfor",
            "tag": "code",
            "html": "Your verification # is {{{code}}}"
        }]
    },
    {
        "name": "sms",
        "timestamp": true,
        "migration": true,
        "unique": ["slug"],
        "field": [
            ["slug", "string",[{"limit" : 50}], "xyzSMS Type" , "required|is_unique[sms.slug]", ""],
            ["tag", "text", [], "xyzReplacement Tags" , "required", ""],
            ["content", "text", [], "xyzSMS Body" , "required", "required"]
        ],
        "join": [],
        "method": "\tpublic function get_template($slug,$data)\n\t{\n\t\t$this->db->from('sms');\n\t\t$this->db->where('slug',$slug,TRUE);\n\t\t$template=$this->db->get()->row();\n\t\tif(!$template)\n\t\t{\n\t\t\treturn FALSE;\n\t\t}\n\t\t$tags_raw=$template->tags;\n\t\t$tags=explode(',',$tags_raw);\n\t\t$template->content=$this->inject_substitute($template->content,$tags,$data);\n\t\treturn $template;\n\t}\n\n\tpublic function inject_substitute($raw, $tags, $data) \n\t{\n\t\tforeach ($data as $key => $value) \n\t\t{\n\t\t\tif (in_array($key, $tags))\n\t\t\t{\n\t\t\t\t$raw = str_replace('{{{' . $key . '}}}', $value, $raw);\n\t\t\t}\n\t\t}\n\t\treturn $raw;\n\t}\n",
        "mapping": {
        },
        "pre": "",
        "post": "if(isset($data['slug']))\n\t\t{\n\t\t\tunset($data['slug']);\n\t\t}\n",
        "count": "",
        "override": "",
        "seed": [
            {"slug": "verify", "tag": "code", "content": "Your verification # is {{{code}}}"}
        ]
    },
    {
        "name": "image",
        "timestamp": true,
        "migration": true,
        "unique": [],
        "seed": [
            {"url": "https://i.imgur.com/AzJ7DRw.png", "caption":"", "user_id": 1, "width":581, "height":581, "type": 1}
        ],
        "field": [
            ["url", "image|250|250|500|500",[], "xyzURL", "required", "required"],
            ["caption", "text",[], "xyzCaption", "", ""],
            ["user_id", "integer",[], "xyzUser", "required|integer", "required|integer"],
            ["width", "integer",[], "xyzWidth", "", ""],
            ["height", "integer",[], "xyzHeight", "", ""],
            ["type", "integer",[], "xyzImage Type", "", ""]
        ],
        "method": "",
        "join": [{
            "name": "user",
            "field": "user_id"
        }],
        "mapping": {
            "type": {"0": "xyzServer Hosted", "1": "xyzExternal Link", "2": "S3", "3": "Cloudinary", "4": "File", "5": "xyzExternal File"}
        },
        "pre": "if(!isset($data['url_id']))\n\t\t{\n\t\t\tunset($data['url_id']);\n\t\t}\n\t\t\tif(!isset($data['caption']))\n\t\t{\n\t\t\t$data['caption'] = '';\n\t\t}\n\t\t\tif(!isset($data['width']))\n\t\t{\n\t\t\t$data['width'] = 0;\n\t\t}\n\t\tif(!isset($data['height']))\n\t\t{\n\t\t\t$data['height'] = 0;\n\t\t}\n\t\tif(!isset($data['type']))\n\t\t{\n\t\t\t$data['type'] = 0;\n\t\t}\n\t\tif(!isset($data['user_id']) || $data['user_id'] == 0)\n\t\t{\n\t\t\t$data['user_id'] = 1;\n\t\t}\n",
        "post": "if(!isset($data['url_id']))\n\t\t{\n\t\t\tunset($data['url_id']);\n\t\t}",
        "count": "",
        "override": ""
    },
      {{{models}}}
    ],
    "portals": [
      {{{portals}}}
    ],
    "controllers": [ 
      {
        "route": "/emails",
        "name": "email",
        "page_name": "Emails",
        "model": "email",
        "controller": "Admin_email_controller.php",
        "api_controller": "Admin_api_email_controller.php",
        "override": "",
        "override_add": "",
        "override_edit": "",
        "override_view": "",
        "override_list": "",
        "override_add_view_model": "",
        "override_edit_view_model": "",
        "override_view_view_model": "",
        "override_list_view_model": "",
        "api": false,
        "frontend": false,
        "paginate": true,
        "paginate_join": "",
        "portal": "admin",
        "is_crud": true,
        "method_edit_pre":"",
        "is_add": true,
        "is_edit": true,
        "is_delete": false,
        "is_real_delete": false,
        "is_list": true,
        "is_view": true,
        "import": false,
        "export": false,
        "is_filter": false,
        "all_records": true,
        "active_only": false,
        "method": "",
        "method_add": "",
        "method_edit": "",
        "method_list": "",
        "method_view": "",
        "method_add_success": "",
        "method_edit_success": "",
        "method_list_success": "",
        "method_view_success": "",
        "method_delete_success": "",
        "custom_view_add": "",
        "custom_view_edit": "",
        "custom_view_list": "",
        "custom_view_view": "",
        "listing_fields_api": ["id", "slug", "subject","tag"],
        "listing_headers": ["Slug", "Subject", "Tags"],
        "listing_rows": ["slug|string", "subject|string","tag|string"],
        "listing_actions": [],
        "filter_fields": [],
        "add_fields": ["slug", "subject","tag","html"],
        "edit_fields": ["subject","html"],
        "view_fields": ["id","slug", "subject","tag","html"],
        "autocomplete": "",
        "dynamic_mapping": {},
        "load_libraries": []
      }, 
      {
        "route": "/sms",
        "name": "sms",
        "page_name": "SMS",
        "model": "sms",
        "controller": "Admin_sms_controller.php",
        "api_controller": "Admin_api_sms_controller.php",
        "override": "",
        "override_add": "",
        "override_edit": "",
        "override_view": "",
        "override_list": "",
        "override_add_view_model": "",
        "override_edit_view_model": "",
        "override_view_view_model": "",
        "override_list_view_model": "",
        "api": true,
        "frontend": false,
        "paginate": true,
        "paginate_join": "",
        "portal": "admin",
        "is_crud": true,
        "method_edit_pre":"",
        "is_add": true,
        "is_edit": true,
        "is_delete": false,
        "is_real_delete": false,
        "is_list": true,
        "is_view": true,
        "import": false,
        "export": false,
        "is_filter": false,
        "all_records": true,
        "active_only": false,
        "method": "",
        "method_add": "",
        "method_edit": "",
        "method_list": "",
        "method_view": "",
        "method_add_success": "",
        "method_edit_success": "",
        "method_list_success": "",
        "method_view_success": "",
        "method_delete_success": "",
        "custom_view_add": "",
        "custom_view_edit": "",
        "custom_view_list": "",
        "custom_view_view": "",
        "listing_fields_api": ["id", "slug", "tag", "content"],
        "listing_headers": ["Slug", "content", "Tags"],
        "listing_rows": ["slug|string", "content|string","tag|string"],
        "listing_actions": [],
        "filter_fields": [],
        "add_fields": ["slug", "tag", "content"],
        "edit_fields": ["content"],
        "view_fields": ["id", "slug", "tag", "content"],
        "autocomplete": "",
        "dynamic_mapping": {},
        "load_libraries": []
    },
     {
        "route": "/image",
        "name": "image",
        "page_name": "Images",
        "model": "image",
        "controller": "Admin_image_controller.php",
        "api_controller": "Admin_api_image_controller.php",
        "override": "",
        "override_add": "",
        "override_edit": "",
        "override_view": "",
        "override_list": "",
        "override_add_view_model": "",
        "override_edit_view_model": "",
        "override_view_view_model": "",
        "override_list_view_model": "",
        "api": false,
        "frontend": false,
        "paginate": true,
        "paginate_join": "",
        "portal": "admin",
        "is_crud": true,
        "method_edit_pre":"",
        "is_add": false,
        "is_edit": false,
        "is_delete": true,
        "is_real_delete": true,
        "is_list": true,
        "is_view": true,
        "import": false,
        "export": false,
        "is_filter": false,
        "all_records": true,
        "active_only": false,
        "method": "",
        "method_add": "",
        "method_edit": "",
        "method_list": "",
        "method_view": "",
        "method_add_success": "",
        "method_edit_success": "",
        "method_list_success": "",
        "method_view_success": "",
        "method_delete_success": "",
        "custom_view_add": "",
        "custom_view_edit": "",
        "custom_view_list": "",
        "custom_view_view": "",
        "listing_fields_api": ["id", "url"],
        "listing_headers": ["ID", "URL"],
        "listing_rows": ["id|integer", "url|image"],
        "listing_actions": [],
        "filter_fields": [],
        "add_fields": ["url", "type"],
        "edit_fields": ["url", "type"],
        "view_fields": ["url", "type"],
        "autocomplete": "",
        "dynamic_mapping": {},
        "load_libraries": []
     },
     {{{controllers}}}
    
    ],
    "packages": {
        "analytics" : false,
        "payment": false,
        "cache": false,
        "pdf": false,
        "voice": false
    },
    "libraries": [],
    "routes": {
    },
    "marketing": {
        "pages": {
            "templates/custom/About_controller.php": "src/application/controllers/Guest/About_controller.php"
        },
        "views": {
            "templates/custom/About.php": "src/application/views/Guest/About.php"
        },
        "routes": {
            "about": "Guest/About_controller"
        },
        "footer": "templates/custom/GuestFooter.php",
        "header": "templates/custom/GuestHeader.php",
        "js": ["/assets/js/guest.js"],
        "css": ["/assets/css/guest.css"]
    },
    "translations": {
      {{{translations}}}
    },
    "reporting": [{
        "name": "Num_users",
        "portal": "admin",
        "page_name": "User Report",
        "route": "/report/users",
        "model": "user",
        "filter_field": [
            ["start_date", "date",[], "Start Date", "required|date", ""],
            ["end_date", "date",[], "End Date", "required|date", ""]
        ],
        "pre_controller": "",
        "parameter": "[]",
        "query": "\t\t$start_date = $this->_start_date;\n\t\t$end_date = $this->_end_date;\n\t\t$query = $this->_model->raw_prepare_query(\"SELECT created_at, COUNT(*) as num FROM user WHERE (created_at>=? AND created_at<=?) GROUP BY created_at\", [$start_date, $end_date]);\n",
        "result": "\t\t\t\t$data[] = ['created_at' => $row->created_at, 'num' => $row->num];\n",
        "post": "",
        "display": "table",
        "filename": "report_users",
        "header": ["Date", "Number of Users"],
        "field": ["created_at", "num"]
    }],
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
        "csrf_exclude_uris": [
            "v1/api.*+",
            {{{csrf_exclude}}}
        ],
        "encryption_key": "1n4uUX6d1Us3quFXKA7ZmqFIaQVC5MtgXlV9ho8F",
        "subclass_prefix": "Manaknight_",
        "migration_number": 9377770345344,
        "language": "english",
        "base_url": "http://devaccount1.manaknightdigital.com",
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
        "hostname": "192.99.245.65",
        "username": "dev_accoLKPwm02k",
        "password": "9rxciM3VGoFuKjQ7EC16w5lz",
        "database": "dev_accoa2tfglsz",
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
    },
    "cronjob": {
        "Cronjob_controller.php": "Cronjob_controller.php",
        "Backup_code_cronjob_controller.php": "Backup_code_cronjob_controller.php",
        "Backup_db_cronjob_controller.php": "Backup_db_cronjob_controller.php",
        "Token_cronjob_controller.php": "Token_cronjob_controller.php"
    },
    "copy": {
    },
    "cron": [
        "* 23 * * * php index.php cli backup_code_cronjob_controller index",
        "* 23 * * * php index.php cli backup_db_cronjob_controller index",
        "30 23 * * * php index.php cli token_cronjob_controller index"
    ]
}