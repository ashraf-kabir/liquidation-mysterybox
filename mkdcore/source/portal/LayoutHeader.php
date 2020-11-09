<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{{title}}}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" crossorigin="anonymous">
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" crossorigin="anonymous"></script>
    <!-- <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
    <!-- Our Vendor CSS -->
    <!-- Our Custom CSS -->
    {{{css}}}
</head>

<body>
<div class="wrapper">
        <!-- Sidebar  -->
        <?php if (!$layout_clean_mode) { ?>
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3 style="height:29px">{{{company}}}</h3>
            </div>

            <ul class="list-unstyled components">
{{{menu}}}
            </ul>
            <span class="copyright d-none">{{{copyright}}}</span>
            <span class="copyright d-none">{{{powered_by}}}</span>
        </nav>
        <?php } ?>
        <div id="content">
            <?php if (!$layout_clean_mode) { ?>
            <nav>
                <div class="navigation-row">
                        <button type="button" id="sidebarCollapse" class="btn btn-light">
                            <span>☰</span>
                        </button>
                </div>
            </nav>
            <?php } ?>
