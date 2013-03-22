<html>
<head>
	
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="../img/favicon.ico" type="image/x-icon" rel="shortcut icon" />
    
    <title><?php echo $_SESSION['Usuario']; ?> | Mi Colegio Virtual</title>
<?php
if ($_SERVER['HTTP_HOST']=="lalvirtual.com" or $_SERVER['HTTP_HOST']=="www.lalvirtual.com"){    
?>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" ></script> 
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.23/jquery-ui.min.js"></script> 
    <link href='http://fonts.googleapis.com/css?family=Signika:300,600' rel='stylesheet' type='text/css'>
<?php
}else{
?>
    <script type="text/javascript" src="../js/jquery-1.7.2.min.js" ></script>
    <script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-1.8.21.custom.min.js"></script>
<?php
}
?>


    <script type="text/javascript" src="../js/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="../js/jquery-ui-sliderAccess.js"></script>
    <script type="text/javascript" src="funciones.js"></script>
    <script type="text/javascript" src="../js/Colorbox/jquery.colorbox.js"></script>

    
    <style type="text/css" title="currentStyle">
        @import "styleprinc.css";
        @import "reset.css";
        /*@import "css/demo_page.css";
        @import "css/demo_table.css";*/
        @import "../css/jquery-ui-1.8.23.custom.css";
        @import "../js/Colorbox/colorbox.css";
    </style>

</head>

<body>