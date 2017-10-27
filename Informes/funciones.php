<?php


class JuicioVal{

	public $rSqlJ = array();
	public $rutaImg='';
		
	public function __construct($anio){
		if ($_SERVER['HTTP_HOST']=="lalvirtual.com" or $_SERVER['HTTP_HOST']=="www.lalvirtual.com"){
			$hostname="localhost";
			$database="lalvirtu_myvc";
			$login="lalvirtu_admin";
			$pass="exalted";
		}else{
			$hostname="localhost";
			$database="lalvirtu_myvc";
			$login="root";
			$pass="123456";			
		}
		
		$con=mysql_connect($hostname, $login, $pass) or die("Problemas con la conexión al servidor");
		
		mysql_select_db($database, $con)or die ("No se conecta a la db");
		
		
		/////////////////////////// SELECCIONO LOS JUICIOS DE ESTE AÑO //////////////////////
		
		$sqlJ="Select * from tbjuiciosvalorativos where YearJuic=".$anio;
		$qSqlJ=mysql_query($sqlJ, $con)or die("No se trajeron las reglas calificativas para del año ".$anio);
		
		while($reg=mysqli_fetch_assoc($qSqlJ)){
			$this->rSqlJ[] = $reg;
		}
		
		
		//echo "Todo ".$this->rSqlJ[2];
	}
	
	public function Palabra($Nota){
		//echo "Mi Juicio: ".sizeof($this->rSqlJ)." Nota: ". $Nota;
		
		for($i=0; $i< sizeof($this->rSqlJ); $i++){
			
			for($j= $this->rSqlJ[$i]['ValorInicialJuic']; $j<= $this->rSqlJ[$i]['ValorFinalJuic']; $j++){
				
				if($Nota==$j){
					return $this->rSqlJ[$i]['NombreJuic'];
				} 
			}
		}
		
		
	}
	
	
	public function Carita($Nota, $Grupo){
		for($i=0; $i< sizeof($this->rSqlJ); $i++){
			
			for($j= $this->rSqlJ[$i]['ValorInicialJuic']; $j<= $this->rSqlJ[$i]['ValorFinalJuic']; $j++){
				
				if($Nota==$j){
					if ($Grupo < 6 or $Grupo =='T' or $Grupo == 'J'){
						$ruta="<img src='../img/Caritas/".$this->rSqlJ[$i]['imgCaritaPrim']."'  width='15' height='15' class='ImgCalify'  />";
						return $ruta;
					}else{
						$ruta="<img src='../img/Caritas/".$this->rSqlJ[$i]['imgCaritaSecun']."'  width='15' height='15' class='ImgCalify'  />";
						return $ruta;
					}
				} 
			}
		}
	}
	
	
	
	function Mayustil($strPalab){
		$NvPalab = str_replace("Á", "á", $strPalab);
		$NvPalab = str_replace("É", "é", $NvPalab);
		$NvPalab = str_replace("Í", "í", $NvPalab);
		$NvPalab = str_replace("Ó", "ó", $NvPalab);
		$NvPalab = str_replace("Ú", "ú", $NvPalab);

		return $NvPalab;
	}
	
}/////////////////////////// FIN DE CLASE //////////////////////




?>