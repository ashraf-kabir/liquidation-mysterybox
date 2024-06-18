<?php
function adminer_object()
{
  // required to run any plugin
  include_once "./adminer/plugins/plugin.php";

  // autoloader
  foreach (glob("./adminer/plugins/*.php") as $filename) {
    include_once "./$filename";
  }

  // enable extra drivers just by including them
  //~ include "./plugins/drivers/simpledb.php";

  $plugins = [
    // specify enabled plugins here
    new AdminerTablesFilter(),
    new AdminerEditTextarea(),
    new AdminerDumpJson(),
    new AdminerDumpDate(),
    new AdminerDumpXml()
  ];

  /* It is possible to combine customization and plugins:
  class AdminerCustomization extends AdminerPlugin {
  }
  return new AdminerCustomization($plugins);
   */

  return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
include "./adminer/adminer-mysql-en.php";
