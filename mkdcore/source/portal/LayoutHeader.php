<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>{{{title}}}</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" crossorigin="anonymous">
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" crossorigin="anonymous"></script>
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
                <h3>{{{company}}}</h3>
            </div>

            <ul class="list-unstyled components">
                {{{menu}}}
            </ul>
            <span class="copyright">{{{copyright}}}</span>
            <span class="copyright">{{{powered_by}}}</span>
        </nav>
    <?php } ?>
    <div id="content">
    <?php if (!$layout_clean_mode) { ?>
        <nav>
            <div class="row">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-dark">
                        <span>☰</span>
                    </button>
                </div>
            </div>
        </nav>
    <?php } ?>
    <?php if(isset($page_section)):?>
        <?php  $this->load->view($page_section); ?>
    <?php endif;?>