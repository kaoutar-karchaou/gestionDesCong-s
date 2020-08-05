<?php
$bd="bdpersonnel";
$user="root";
$pass="";
$server="localhost";

try{
    $conn=new PDO ("mysql:host=$server; dbname=$bd",$user,$pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
        //echo ("connected successfuly");
}
catch(PDOException $e){echo ("failed").$e->getMessage();}
?>