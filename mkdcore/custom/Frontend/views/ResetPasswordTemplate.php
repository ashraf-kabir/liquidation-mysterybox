<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<style type="text/css">
    body {
        width: 100%;
        background-color: #ffffff;
        margin: 0;
        padding: 0;
        -webkit-font-smoothing: antialiased;
        font-family: Georgia, Times, serif
    }

    table {
        border-collapse: collapse;
    }

    td#logo {
        margin: 0 auto;
        padding: 14px 0;
    }

    img {
        border: none;
        display: block;
    }

    a.blue-btn {
        display: inline-block;
        margin-bottom: 34px;
        border: 3px solid #3baaff;
        padding: 11px 38px;
        font-size: 12px;
        font-family: arial;
        font-weight: bold;
        color: #3baaff;
        text-decoration: none;
        text-align: center;
    }

    a.blue-btn:hover {
        background-color: #3baaff;
        color: #fff;
    }

    a.white-btn {
        display: inline-block;
        margin-bottom: 30px;
        border: 3px solid #fff;
        background: transparent;
        padding: 11px 38px;
        font-size: 12px;
        font-family: arial;
        font-weight: bold;
        color: #fff;
        text-decoration: none;
        text-align: center;
    }

    a.white-btn:hover {
        background-color: #fff;
        color: #3baaff;
    }

    .border-complete {
        border-top: 1px solid #dadada;
        border-left: 1px solid #dadada;
        border-right: 1px solid #dadada;
    }

    .border-lr {
        border-left: 1px solid #dadada;
        border-right: 1px solid #dadada;
    }

    #banner-txt {
        color: #fff;
        padding: 15px 32px 0px 32px;
        font-family: arial;
        font-size: 13px;
        text-align: center;
    }

    h2#our-products {
        font-family: 'Pacifico';
        margin: 23px auto 5px auto;
        font-size: 27px;
        color: #3baaff;
    }

    h3.our-products {
        font-family: arial;
        font-size: 15px;
        color: #7c7b7b;
    }

    p.our-products {
        text-align: center;
        font-family: arial;
        color: #7c7b7b;
        font-size: 12px; 
    }

    h2.special {
        margin: 0;
        color: #fff;
        color: #fff;
        font-family: 'Pacifico';
        padding: 15px 32px 0px 32px;
    }

    p.special {
        color: #fff;
        font-size: 12px;
        color: #fff;
        text-align: center;
        font-family: arial;
        padding: 0px 32px 10px 32px;
    }

    h2#coupons {
        color: #3baaff;
        text-align: center;
        font-family: 'Pacifico';
        margin-top: 30px;
    }

    p#coupons {
        color: #7c7b7b;
        text-align: center;
        font-size: 12px;
        text-align: center;
        font-family: arial;
        padding: 0 32px;
    }

    #socials {
        padding-top: 12px;
    }

    p#footer-txt {
        text-align: center;
        color: #303032;
        font-family: arial;
        font-size: 12px;
        padding: 0 32px;
    }

    #social-icons {
        width: 28%;
    }

    @media only screen and (max-width: 640px) {
        body[yahoo] .deviceWidth {
            width: 440px!important;
            padding: 0;
        }
        body[yahoo] .center {
            text-align: center!important;
        }
        #social-icons {
            width: 40%;
        }
    }

    @media only screen and (max-width: 479px) {
        body[yahoo] .deviceWidth {
            width: 280px!important;
            padding: 0;
        }
        body[yahoo] .center {
            text-align: center!important;
        }
        #social-icons {
            width: 60%;
        }
    }

    .btn_reset{
        background: #333333;
        color: #ffff;
        padding: 10px 20px;
        padding-top: 10px;
        padding-right: 20px;
        padding-bottom: 10px;
        padding-left: 20px;
        display: inline-block;
        margin-top: 10px;
        border-radius: 2px;
        border: none;
        margin-bottom: 20px;
    }
</style>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix" style="font-family: Georgia, Times, serif">

    <table width="100%" border="0" cellpadding="0" cellspacing="0" align="center">


        <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="border-complete deviceWidth" bgcolor="#e9e9e9">
            <tr>
                <td width="100%">

                    <table border="0" cellpadding="0" cellspacing="0" align="center" class="deviceWidth">
                        <tr>
                            <td id="logo" align="center">
                                <a href="<?php echo base_url(); ?>" style="text-decoration: none;color: black;"><h1> Vegas Liquidation</h1></a>
                            </td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

        <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="border-lr deviceWidth" bgcolor="#fff">
            <tr>
                <td align="center">
                    <h2 id="our-products"  >Reset Your Password</h2> </td>
                </tr>
                <tr>
                    <td class="center">

                        <table width="100%" border="0" cellpadding="0" cellspacing="0" align="left" class="deviceWidth">

                            <tr>
                                <td align="center">
                                    <p class="our-products"> Click below link to reset your password.</p>
                                    <a href="<?php echo $reset_link ?>" class="btn btn_reset" style="text-decoration: none;">Reset</a>

                                </td>
                            </tr>

                        </table> 
                    </td>
                </tr>
            </table>



            <table width="600" border="0" cellpadding="0" cellspacing="0" align="center" class="border-complete deviceWidth" bgcolor="#eeeeed">
                 

                <tr>
                    <td style="text-align: center;">
                        <p id="footer-txt"> <b>Copyright © 2019 All rights reserved</b>

                        </p>
                    </td>
                </tr>
            </table>

        </table>

    </body>
    </html>

