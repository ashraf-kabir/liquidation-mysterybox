function dropdown() {
  return {
    show: false,
    open() {
      this.show = true;
    },
    close() {
      this.show = false;
    },
    isOpen() {
      return this.show === true;
    },
  };
}

function checkType(value) {
  if (value == "false") {
    return false;
  }
  if (value == "true") {
    return true;
  }
  if (value.indexOf("[") == 0) {
    return eval(value);
  }
  if (!isNaN(value)) {
    return Number(value);
  }
  return value;
}
function globalState() {
  return {
    config: initState(),
    state: {
      currentState: "",
      modelAdd: false,
      roleAdd: false,
      copyAdd: false,
      routeAdd: false,
      databaseAdd: false,
      databaseEdit: false,
      roleEdit: false,
      routeEdit: false,
      copyEdit: false,
      modelEdit: false,
    },
    form: {
      role: "",
      roleId: 0,
      copyKey: "../mkdcore/custom/",
      copyValue: "../release/",
      roleEditKey: "",
      roleEditValue: "",
      roleEditKeyOld: 0,
      copyEditKey: "",
      copyEditValue: "",
      copyEditKeyOld: "",
      routeKey: "",
      routeValue: "",
      routeEditKey: "",
      routeEditValue: "",
      routeEditKeyOld: "",
      databaseKey: "",
      databaseValue: "",
      databaseEditKey: "",
      databaseEditValue: "",
      databaseEditKeyOld: "",
    },
    resetDefault: function () {
      this.config = initState();
      this.resetState("none");
    },
    getCopyState: function () {
      var keys = Object.keys(this.config.copy);
      var results = [];
      for (var i = 0; i < keys.length; i++) {
        results.push({ key: keys[i], value: this.config.copy[keys[i]] });
      }
      return results;
    },
    getDatabaseState: function () {
      var keys = Object.keys(this.config.database);
      var results = [];
      for (var i = 0; i < keys.length; i++) {
        results.push({ key: keys[i], value: this.config.database[keys[i]] });
      }
      return results;
    },
    getRouteState: function () {
      var keys = Object.keys(this.config.routes);
      var results = [];
      for (var i = 0; i < keys.length; i++) {
        results.push({ key: keys[i], value: this.config.routes[keys[i]] });
      }
      return results;
    },
    //add
    addRole: function (id, role) {
      this.config.roles.push({ id: id, name: role });
      this.form.roleId = 0;
      this.form.role = "";
      this.state.roleAdd = false;
    },
    addCopy: function (key, value) {
      this.config.copy[key] = value;
      this.form.copyKey = "../mkdcore/custom/";
      this.form.copyValue = "../release/";
      this.state.copyAdd = false;
    },
    addRoute: function (key, value) {
      this.config.routes[key] = value;
      this.form.routeKey = "";
      this.form.routeValue = "";
      this.state.routeAdd = false;
    },
    addDatabase: function (key, value) {
      this.config.database[key] = checkType(value);
      this.form.databaseKey = "";
      this.form.databaseValue = "";
      this.form.databaseEditKeyOld = "";
      this.state.databaseEdit = false;
      this.state.databaseAdd = false;
    },
    //delete
    removeRole: function (roleId) {
      this.config.roles = this.config.roles.filter((role) => role.id != roleId);
    },
    removeCopy: function (copyKey) {
      delete this.config.copy[copyKey];
      this.state.copyAdd = false;
    },
    removeRoute: function (routeKey) {
      delete this.config.routes[routeKey];
      this.state.routeAdd = false;
    },
    removeDatabase: function (databaseKey) {
      delete this.config.database[databaseKey];
      this.state.databaseAdd = false;
    },
    //edit
    editRole: function (oldKey, key, value) {
      for (var i = 0; i < this.config.roles.length; i++) {
        if (this.config.roles[i].id == oldKey) {
          this.config.roles[i].id = Number(this.form.roleEditKey);
          this.config.roles[i].name = this.form.roleEditValue;
        }
      }
      this.form.roleEditKey = "";
      this.form.roleEditValue = "";
      this.form.roleEditKeyOld = "";
      this.state.roleEdit = false;
      this.state.roleAdd = false;
    },
    editRoleShow: function (key) {
      this.form.roleEditKey = key;
      this.form.roleEditKeyOld = key;
      for (var i = 0; i < this.config.roles.length; i++) {
        if (this.config.roles[i].id == key) {
          this.form.roleEditValue = this.config.roles[i].name;
        }
      }
      this.state.roleAdd = false;
      this.state.roleEdit = true;
    },
    editCopy: function (oldKey, key, value) {
      delete this.config.copy[oldKey];
      this.config.copy[key] = checkType(value);
      this.form.copyEditKey = "";
      this.form.copyEditValue = "";
      this.form.copyEditKeyOld = "";
      this.state.copyEdit = false;
      this.state.copyAdd = false;
    },
    editCopyShow: function (key) {
      this.form.copyEditKey = key;
      this.form.copyEditValue = this.config.copy[key];
      this.form.copyEditKeyOld = key;
      this.state.copyAdd = false;
      this.state.copyEdit = true;
    },
    editRoute: function (oldKey, key, value) {
      delete this.config.routes[oldKey];
      this.config.routes[key] = checkType(value);
      this.form.routeEditKey = "";
      this.form.routeEditValue = "";
      this.form.routeEditKeyOld = "";
      this.state.routeEdit = false;
      this.state.routeAdd = false;
    },
    editRouteShow: function (key) {
      this.form.routeEditKey = key;
      this.form.routeEditValue = this.config.routes[key];
      this.form.routeEditKeyOld = key;
      this.state.routeAdd = false;
      this.state.routeEdit = true;
    },
    editDatabase: function (oldKey, key, value) {
      delete this.config.database[oldKey];
      this.config.database[key] = checkType(value);
      this.form.databaseEditKey = "";
      this.form.databaseEditValue = "";
      this.form.databaseEditKeyOld = "";
      this.state.databaseEdit = false;
      this.state.databaseAdd = false;
    },
    editDatabaseShow: function (key) {
      this.form.databaseEditKey = key;
      this.form.databaseEditValue = this.config.database[key];
      this.form.databaseEditKeyOld = key;
      this.state.databaseAdd = false;
      this.state.databaseEdit = true;
    },
    resetState: function (state) {
      this.state.roleAdd = false;
      this.state.modelAdd = false;
      this.state.copyAdd = false;
      this.state.routeAdd = false;
      this.state.databaseAdd = false;
      this.state.databaseEdit = false;
      this.state.copyEdit = false;
      this.state.routeEdit = false;
      this.state.roleEdit = false;
      this.form.databaseEditKeyOld = "";
      this.form.copyEditKeyOld = "";
      this.form.routeEditKeyOld = "";
      this.form.roleEditKeyOld = "";
      this.state.currentState = state;
    },
  };
}

function initState() {
  return {
    has_license_key: true,
    license_key:
      "4097fbd4f340955de76ca555c201b185cf9d6921d977301b05cdddeae4af54f924f0508cd0f7ca66",
    project_name: "Manaknightdigital SAAS",
    powered_by: "Manaknightdigital Inc.",
    domain: "manaknight.com",
    locale: false,
    language: "english",
    roles: [
      {
        id: 1,
        name: "member",
      },
      {
        id: 2,
        name: "admin",
      },
    ],
    models: [
      {
        name: "setting",
        timestamp: true,
        migration: true,
        field: [
          ["id", "integer", [], "ID", "", ""],
          ["key", "string", [{ limit: 50 }], "Setting Field", "required", ""],
          ["type", "integer", [], "Setting Type", "required", ""],
          ["value", "text", [], "Setting Value", "required", "required"],
        ],
        method:
          "\tpublic function get_config_settings()\n\t{\n\t\t$this->db->from('setting');\n\t\t$results = $this->db->get()->result();\n\t\t$data = [];\n\t\tforeach ($results as $key => $value)\n\t\t{\n\t\t\t$data[$value->key] = $value->value;\n\t\t}\n\t\treturn $data;\n\t}\n",
        join: [],
        mapping: {
          type: {
            "0": "text",
            "1": "select",
            "2": "number",
            "3": "image",
            "4": "read_only",
          },
          maintenance: { "0": "No", "1": "Yes" },
        },
        pre: "",
        post:
          "if(isset($data['key']))\n\t\t{\n\t\t\tunset($data['key']);\n\t\t}\n",
        count: "",
        override: "",
        unique: ["key"],
        seed: [
          { key: "site_name", type: 0, value: "Manaknight Inc" },
          { key: "maintenance", type: 1, value: "0" },
          { key: "version", type: 0, value: "1.0.0" },
        ],
      },
    ],
    routes: {},
    copy: {},
    database: {
      adapter: "mysql",
      port: 3306,
      dsn: "",
      hostname: "localhost:3306",
      username: "root",
      password: "root",
      database: "code_builder_default",
      dbdriver: "mysqli",
      dbprefix: "",
      pconnect: false,
      db_debug: false,
      cache_on: false,
      cachedir: "",
      char_set: "utf8",
      dbcollat: "utf8_general_ci",
      swap_pre: "",
      encrypt: false,
      compress: false,
      stricton: false,
      failover: [],
      save_queries: true,
    },
  };
}
function copyToClipboard() {
  var element = document.getElementById("result");
  var textArea = document.createElement("textarea");
  // textArea.style = "display:none";
  textArea.value = element.innerHTML;
  document.body.appendChild(textArea);
  textArea.select();
  textArea.setSelectionRange(0, 99999); /*For mobile devices*/

  /* Copy the text inside the text field */
  document.execCommand("copy");
  textArea.remove();
  // alert("copied");
}
