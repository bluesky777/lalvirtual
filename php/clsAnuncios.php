<?php
include_once ('clsPersona.php');

class clsAnuncios extends clsPersona {

	function gAnuncios($idUsu, $TipoUsu){
		$this->Conectar();
		$sqlAnu="";

		switch ($TipoUsu) {
			case 1:
				$sqlAnu.="select * from tbanuncios, tbtipoanuncio where idTipoAnun=TipoAnuncioAnu and (TipoAnuncioAnu=5 or ReceptoresTipoAnun='TODOS' or 
					ReceptoresTipoAnun='MANAGER Y PROFESOR' or ReceptoresTipoAnun='MANAGER') ";
				break;
			case 2:
				$sqlAnu.="select * from tbanuncios, tbtipoanuncio where idTipoAnun=TipoAnuncioAnu and (TipoAnuncioAnu=5 or ReceptoresTipoAnun='TODOS' or 
					ReceptoresTipoAnun='MANAGER Y PROFESOR' or ReceptoresTipoAnun='DOCENTES') ";
				break;
			case 3:
				$sqlAnu="select * from tbanuncios, tbtipoanuncio,
				    (select ga.idGrupo from tbgrupoalumnos ga, tbperiodos p, tbyearcolegio y
				        where ga.idPeriodo=p.idPer and p.Year=y.Year and p.Year=".$_SESSION['Year']." and 
				        ga.idAlumno=".$idUsu." 
				        group by ga.idAlumno ) as r  
				where idTipoAnun=TipoAnuncioAnu 
				    and (TipoAnuncioAnu=5 or ReceptoresTipoAnun='TODOS' 
				    or ReceptoresTipoAnun='ESTUDIANTES'  
				    or (ReceptoresTipoAnun='GRUPO' and idGrupoAnu=r.idGrupo ) )
				group by idAnu ";
				break;
			case 4:
				$sqlAnu.="select * from tbanuncios, tbtipoanuncio where idTipoAnun=TipoAnuncioAnu and (TipoAnuncioAnu=5 or ReceptoresTipoAnun='TODOS' or 
					ReceptoresTipoAnun='ACUDIENTE') ";
				break;
			default:
				$sqlAnu.="select * from tbanuncios, tbtipoanuncio where idTipoAnun=TipoAnuncioAnu and (TipoAnuncioAnu=5 or ReceptoresTipoAnun='TODOS' or 
					ReceptoresTipoAnun='MANAGER Y PROFESOR' or ReceptoresTipoAnun='DOCENTES') ";
				break;
		} 

		$sqlAnu.=" order by idAnu desc;";
		
		return $this->queryx($sqlAnu, "Lo sentimos, no se pudieron traer los anuncios.");
	}

	function Cerrar(){
		mysql_close($this->con);
	}

	function gCommentsxAnun($idAnu){

		$sqlC="select * from tbanuncios a, tbcomentarios c where a.idAnu=".$idAnu." and a.idAnu=c.AnunCmt;";
		$qSqlC = $this->queryx($sqlC, "Lo sentimos, no se pudieron traer los <b>comentarios</b>.");
		$numC = mysql_num_rows($qSqlC);
		
		$Resp="";

		if ($numC == 0){

			return "";
		}elseif($numC > 0){
			?>
			<div class='CmtsTog' id='CmtsTog_$idAnu'><a href='javascript:void(0);'>Mostrar comentarios.</a>
			
			<?php
			while($rSqlC=mysql_fetch_array($qSqlC)){
				$this->DatosxUsu($rSqlC['UsuCrCmt']);
				$Uimg=$this->ImgPerf(); $Unom=$this->gNom(); $Uape=$this->gApe();
			?>
			<div class='CmtsAnu'>
				<span class='imgSujeto'><img src='../img/<?php echo $Uimg; ?>' /></span>
				<span class='NomSujeto'><a href='#'><?php echo $Unom ." ". $Uape; ?></a></span>
				<span class='FecSujeto' title="<?php echo $rSqlC['FechCrCmt']; ?>"><?php $date=new DateTime($rSqlC['FechCrCmt']); echo $date->format('Y/m/d'); ?></span>
				<div class='CmtSujeto'><?php echo $rSqlC['ComentarioCmt']; ?></div>
			</div>
			<?php
			}
			?>
			</div> <!-- fin CmtsAnu -->
			<?php
			

		}

	}

	function Conectar(){
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

		$this->con=mysql_connect($hostname, $login, $pass) or die("Problemas con la conexión al servidor");
		mysql_query("SET NAMES 'utf8'");
		mysql_select_db($database, $this->con)or die ("No se conecta a la DB");

	}

	function AgregarCmmtAnun($idAnu, $Cmt){
		$this->Conectar();
		$sqlC="insert into tbcomentarios(UsuCrCmt, ComentarioCmt, FechCrCmt, AnunCmt) 
			values(".$_SESSION['idUsuar'].",'".$Cmt."', '". date('Y/m/d h:i:s',time())."', ".$idAnu.");";

		$qSqlC = $this->queryx($sqlC, "Lo sentimos, no se guardar el comentario.");
		
		if ($qSqlC) {
			echo "Guardado";
		}else{
			echo "No se pudo guardar el comentario.";
		}
	}

}
