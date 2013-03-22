<?php
require_once("conexion.php");
require_once("verificar_sesion.php");

$con=Conectar();

?>
<div id="tabsAnun">

    <ul>
        <li><a href="#tabs-1">Normal</a></li>
        <li><a href="#tabs-2">Anuncio específico</a></li>
    </ul>

    <div id="tabs-1">
        <form action="" id="frmAnun_Normal" name="frmAnun_Normal">
            <b>Aun no está listo.</b><br><br><br>
            <input type="hidden" name="hdAnuVariosRecep" id="hdAnuVariosRecep" value="0" />
            <label for="SelAnunRecep">¿A quien quienes quieres que le llegue el anuncio?</label>
            <select id="SelAnunRecep" name="SelAnunRecep">
                <option>Deberia ser un atocompletado</option>
            </select>
            <a href="">Añadir</a>
            <br>
            <label for="txtComentAnun">Comentario</label>
            <textarea id="txtComentAnun" name="txtComentAnun"></textarea>
            <input type="submit" value="Anunciar" /> 
        </form>
    </div>

    <div id="tabs-2">
        
        <form action="" id="frmAnun_Especif" name="frmAnun_Especif">
            <input type="hidden" name="hdAnuGrupo" id="hdAnuGrupo" value="0" />
            <input type="hidden" name="hdAnuIdGrupo" id="hdAnuIdGrupo" value="0" />
            <label for="SelTipoAnun">¿Qué tipo de anuncio quieres hacer?</label>
            <select id="SelTipoAnun" name="SelTipoAnun">
                <?php
                $sqlTipoAnun="select * from tbtipoanuncio where EmisoresTipoAnun='TODOS' or ";

                switch ($_SESSION['TipoUsu']) {
                     case 1:
                        $sqlTipoAnun.="EmisoresTipoAnun='MANAGER Y PROFESOR' ";
                        break;
                     
                     case 2:
                        $sqlTipoAnun.="EmisoresTipoAnun='MANAGER Y PROFESOR' or EmisoresTipoAnun='PROFESOR' ";
                        break;

                    case 3:
                        $sqlTipoAnun.="EmisoresTipoAnun='ALUMNO' ";
                        break;

                    case 4:
                        $sqlTipoAnun.="EmisoresTipoAnun='ACUDIENTE' ";
                        break;

                     default:
                         $sqlTipoAnun.="EmisoresTipoAnun='MANAGER Y PROFESOR' or EmisoresTipoAnun='PROFESOR' ";
                         break;
                 } 


                $qSqlTipoAnun=mysql_query($sqlTipoAnun, $con) or die("No se pudo traer los tipos de anuncios.");
                while ($rSqlTipoAnun=mysql_fetch_array($qSqlTipoAnun)) {
                ?>
                <option value="<?php echo $rSqlTipoAnun['idTipoAnun'];?>"><?php echo $rSqlTipoAnun['AnuncioTipoAnun'];?></option>
                <?php
                }
                ?>
            </select>
            <br>
            <label for="txtComent_Esp">Comentario</label>
            <textarea id="txtComent_Esp" name="txtComent_Esp"></textarea>
            <input style="margin: 5px; border-radius: 4px; padding: 8px 12px; cursor: pointer; background: #4297d7" type="submit" value="Anunciar" /> 
        </form>

    </div>

    <div id="RespAnun"></div>

</div>

<?php
mysql_close($con);
?>