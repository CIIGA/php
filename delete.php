<?php
session_start();
if((isset($_SESSION['user'])) and (isset($_SESSION['tipousuario']))){
require "../../acnxerdm/cnx.php";
//*****************************************************************
if(isset($_GET['poneUser'])){
    $id_usuarioNuevo=$_GET['usr'];
    
    $delusr="DELETE FROM usuario WHERE id_usuarioNuevo='$id_usuarioNuevo'";
    sqlsrv_query($cnx,$delusr);
    
    $del="DELETE FROM usuarionuevo WHERE id_usuarioNuevo='$id_usuarioNuevo'";
    sqlsrv_query($cnx,$del);
    
    echo '<script> alert("Registro Eliminado.")</script>';
    echo '<meta http-equiv="refresh" content="0,url=config.php">';
}
//**************************************************************
if(isset($_GET['poneacces'])){
    $id_acceso=$_GET['acces'];
    $usr=$_GET['usr'];
    
    $delaccess="DELETE FROM acceso WHERE id_acceso='$id_acceso'";
    sqlsrv_query($cnx,$delaccess);
    
    echo '<meta http-equiv="refresh" content="0,url=permisoPlz.php?usr='.$usr.'&plz=65&crhm=950721&idus=659898895">';
}
//********************************************************
if(isset($_GET['poneplz'])){
    $idplz=$_GET['plz'];
    
    $delaccess="DELETE FROM plaza WHERE id_plaza='$idplz'";
    sqlsrv_query($cnx,$delaccess);
        echo '<script> alert("Registro plaza Eliminado.")</script>';
    echo '<meta http-equiv="refresh" content="0,url=addplz.php">';
}
//****************************************************************************************
if(isset($_GET['poneurl'])){
    $idplz=$_GET['plz'];
    $idurl=$_GET['url'];
    
    $delaccess="DELETE FROM kpi WHERE id_kpi='$idurl'";
    sqlsrv_query($cnx,$delaccess);
        echo '<script> alert("URL de kpi Eliminada.")</script>';
    echo '<meta http-equiv="refresh" content="0,url=urlMap.php?plz='.$idplz.'">';
}        
//****************************************************************************************
if(isset($_GET['poneorigen'])){
    $idorigen=$_GET['origen'];
    
    $delaccess="DELETE FROM proveniente WHERE id_proveniente='$idorigen'";
    sqlsrv_query($cnx,$delaccess);
        echo '<script> alert("Registro origen de datos Eliminado.")</script>';
    echo '<meta http-equiv="refresh" content="0,url=origen.php">';
}
//****************************************************************************************
//****************************************************************************************
if(isset($_GET['ponesync'])){
    $idsyc=$_GET['syc'];
    $idpl=$_GET['idpl'];
    
    $delaccess="DELETE FROM sync WHERE id_sync='$idsyc'";
    sqlsrv_query($cnx,$delaccess);
        echo '<script> alert("Registro Eliminado.")</script>';
    echo '<meta http-equiv="refresh" content="0,url=store.php?idpl='.$idpl.'">';
}
//****************************************************************************************
//********************************************************
if(isset($_GET['ponesub'])){
    $idreg=$_GET['reg'];
    $plz=$_GET['plz'];
    
    $delaccess="DELETE FROM subregistro WHERE id_subregistro='$idreg'";
    sqlsrv_query($cnx,$delaccess);
        echo '<script> alert("Subgrupo Eliminado.")</script>';
    echo '<meta http-equiv="refresh" content="0,url=nuevoGrupo.php?plz='.$plz.'">';
}
//****************************************************************************************
    
    
    } else{
        echo '<script> alert("Su usuario no tiene permisos para eliminar usuarios.")</script>';
        header('location:../../login.php');
    }
?>