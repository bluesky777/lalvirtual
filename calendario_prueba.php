<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
<link type="text/css" href="css/smoothness/jquery-ui-1.8.18.custom.css" />
<script type="text/javascript" src="js/jquery-ui-1.8.18.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
        $("#datepicker").datepicker({ 
			dateFormat: "yy/mm/dd",
			changeYear: true,
			monthNames: ["Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"],
			dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
			/*dayNamesShort: ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sat"],*/
			nextText: "Sig",
			/*numberOfMonths: [2, 1],*/
			prevText: "Ant",
			showButtonPanel: true,
			currentText: "Hoy",
			closeText: "Listo",
			showAnim: "bounce" 
			 });
    });
</script>
<title>Calendario Jquery</title>

</head>

<body>

<input type="text" id="datepicker" name="txtFecha"/>

</body>
</html>