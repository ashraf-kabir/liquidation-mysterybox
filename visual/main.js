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

function globalState() {
  return {
    config: initState(),
    state: {
      currentState: "",
      modelAdd: false,
      roleAdd: false,
      copyAdd: false,
    },
    form: {
      role: "",
      roleId: 0,
      copyKey: "../mkdcore/custom/",
      copyValue: "../release/",
    },
    resetDefault: function () {
      this.config = initState();
      this.resetState("none");
    },
    getCopyState: function () {
      var keys = Object.keys(this.config.copy);
      var results = [];
      for (var i = 0; i < keys.length; i++) {
        var element = keys[i];
        results.push({ key: keys[i], value: this.config.copy[keys[i]] });
      }
      return results;
    },
    removeRole: function (roleId) {
      this.config.roles = this.config.roles.filter((role) => role.id != roleId);
    },
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
    removeCopy: function (copyKey) {
      delete this.config.copy[copyKey];
      this.state.copyAdd = false;
    },
    resetState: function (state) {
      this.state.roleAdd = false;
      this.state.modelAdd = false;
      this.state.copyAdd = false;
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
    copy: {},
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
