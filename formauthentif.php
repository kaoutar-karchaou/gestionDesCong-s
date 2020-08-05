<?php 
 session_start();
    include 'ini.php';
if(isset($_SESSION['login']))
{if($_SESSION['CodePrivilege']=='GRH') 
   {
       include 'interfaceGRH.php';
       
   }else if($_SESSION['CodePrivilege']=='Superviseur')
   {
      include'interfacesup.php';
       
   }else if($_SESSION['CodePrivilege']=='User')
   {
       include 'interfaceUSER.php';
   }
  
}else{
    header("location : index.php");
}
?>
