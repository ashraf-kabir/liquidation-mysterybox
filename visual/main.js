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
      roles: false,
      roleAdd: false,
    },
    form: {
      role: "",
      roleId: 0,
    },
    resetDefault: function () {
      this.config = initState();
      this.state.roles = false;
      this.state.roleAdd = false;
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
        name: "member",
        id: 1,
      },
      {
        name: "admin",
        id: 2,
      },
      {
        name: "admin",
        id: 2,
      },
    ],
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
  alert("copied");
  /* Alert the copied text */
}
