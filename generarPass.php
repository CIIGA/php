<?php
require "../../acnxerdm/cnx.php";
    $idusr=$_POST['alumnos'];
    
$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz#$%?ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$shuffle=substr(str_shuffle($permitted_chars), 4, 4);
    
$permitted_charss = '0123456789abcdefghijklmnopqrstuvwxyz#$%?ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$shuffles=substr(str_shuffle($permitted_charss), 2, 2);
    
    $cadena=$shuffles.rand(10,99).$shuffle.date('s');
    
    echo $cadena;
    
    ?> 