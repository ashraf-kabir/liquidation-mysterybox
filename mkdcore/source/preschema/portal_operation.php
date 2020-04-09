{
    "name": "{{{portal_name}}}",
    "timestamp": true,
    "migration": true,
    "unique": [],
    "seed": [],
    "field": [
        ["user_id", "integer",[], "xyzUser", "required|integer", ""],
        ["action", "string",[{"limit": 50}], "xyzAction", "required|max[50]", ""],
        ["detail", "text",[], "xyzDetail", "required", ""],
        ["last_ip", "string",[{"limit": 25}], "xyzLast IP", "required", ""],
        ["user_agent", "string",[{"limit": 100}], "xyzUser Agent", "required", ""]
    ],
    "method": "\tpublic function get_ip()\n \t{\n \t\tif(!empty($_SERVER['HTTP_CLIENT_IP']))\n \t\t{\n \t\t\t$ip = $_SERVER['HTTP_CLIENT_IP'];\n \t\t}\n \t\telseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))\n \t\t{\n \t\t\t$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];\n \t\t}\n \t\telse\n \t\t{\n \t\t\t$ip = $_SERVER['REMOTE_ADDR'];\n \t\t}\n \t\treturn $ip;\n \t}\n\n\tpublic function get_user_agent()\n \t{\n \t\treturn $_SERVER['HTTP_USER_AGENT'];\n \t}\n\tpublic function log_activity($action, $detail, $user_id)\n\t{\n\t\treturn $this->create([\n\t\t\t\t'user_id' =>  $user_id,\n\t\t\t\t'action' =>   $action,\n\t\t\t\t'detail' =>   json_encode($detail)\n\t\t]);\n\t}\n",
    "join": [{
        "name": "{{{model}}}",
        "field": "user_id"
    }],
    "mapping": {
    },
    "pre": "$data['last_ip'] = $this->get_ip();\n\t\t$data['last_ip'] = $this->get_user_agent();\n",
    "post": "",
    "count": "",
    "override": ""
}