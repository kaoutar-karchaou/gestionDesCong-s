<?php
  include 'ini.php';
  ob_start();
  if(isset($_SESSION['login'])){
      if(!isset($_GET['do'])){ $_GET['do']='manage';}
      $req1 = $conn->prepare("SELECT * FROM Personnel WHERE CIN=?");
        $req1->execute(array($_SESSION['cin']));
        $user1 = $req1->fetch();
      $_SESSION['Nom']=$user1['Nom'];
      $_SESSION['Prenom']=$user1['Prenom'];
      $_SESSION['photo']=$user1['Photo'];
      $pic=$_SESSION['photo']; 
      $req2 = $conn->prepare("SELECT * FROM `grade` WHERE `CIN`=?");
        $req2->execute(array($_SESSION['cin']));
        $user2 = $req2->fetch();
      
      function open_days($date_start, $date_stop) {	
	$arr_bank_holidays = array();			
    $year = (int)date('Y', $date_start);
		// Liste des jours feriés
		$arr_bank_holidays[] = '1_1_'.$year; // Jour de l'an
		$arr_bank_holidays[] = '1_5_'.$year; // Fete du travail
		$arr_bank_holidays[] = '30_7_'.$year; // Fête de trône
		$arr_bank_holidays[] = '14_8_'.$year; // Oued Eddahab
		$arr_bank_holidays[] = '20_8_'.$year; // Révolution du roi et du peuple
		$arr_bank_holidays[] = '21_8_'.$year; // Fête de la jeunesse
		$arr_bank_holidays[] = '6_11_'.$year; // Marche verte
		$arr_bank_holidays[] = '18_11_'.$year; // Fête d'indépendance
	$nb_days_open = 0;
	// Mettre <= si on souhaite prendre en compte le dernier jour dans le décompte	
	while ($date_start < $date_stop) {
if (!in_array(date('w', $date_start), array(0, 6)) && !in_array(date('j_n_'.date('Y', $date_start), $date_start), $arr_bank_holidays)){$nb_days_open++;}
$date_start = mktime(date('H', $date_start), date('i', $date_start), date('s', $date_start), date('m', $date_start), date('d', $date_start) + 1, date('Y', $date_start));			
	}		
	return $nb_days_open;
}

  ?>
<div class="container" style="width:900px">
    
  <div class="card-group">
  <div class="card">
      <img class="card-img-top" height="140px" src="log.png" alt="Card image cap">
    
  </div>
  <div class="card">
    <div class="card-body">
      <img class="card-img-top" height="140px" src="bienvenue.jpg" alt="Card image cap">

      
    </div>
  </div>
  <div class="card">
    <img class="card-img-top" height="140px" src="5.png" alt="Card image cap">

    
  </div>
</div>  
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link bg-primary text-white" href="?do=manage"><i class="fa fa-home fa-fw text-white " aria-hidden="true"></i>&nbsp;Accueil <span class="sr-only">(current)</span></a>
      </li>
        <li class="nav-item">
          <a class="nav-link text-white bg-danger" href="?do=infos"> <i class="fa fa-user fa-fw text-white " aria-hidden="true"></i>&nbsp;Mes informations</a>
      </li>
      <li class="nav-item">
          <a class="nav-link bg-success text-white" href="?do=listperso"><i class="fa fa-users fa-fw text-white " aria-hidden="true"></i>&nbsp;Gestion Membre</a>
      </li>
      <li class="nav-item">
          <a class="nav-link text-white bg-warning" href="?do=addconge1"> <i class="fa fa-edit fa-fw text-white " aria-hidden="true"></i>&nbsp;Demander un congé</a>
      </li>
        <li>
          <a class="nav-link text-white bg-info" href="?do=MesDemandes" ><i class="fa fa-list-ul fa-fw text-white " aria-hidden="true"></i>&nbsp;Mes demandes</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-white bg-danger" href="?do=listDEM"><i class="fa fa-list-alt fa-fw text-white " aria-hidden="true"></i>&nbsp; liste Demandes </a>
      </li>
        <li class="nav-item">
        <a class="nav-link bg-secondary text-white" href="?do=vdir"><i class="fa fa-exchange fa-fw text-white " aria-hidden="true"></i>&nbsp; Visa Directrice </a>
      </li>
          <li class="nav-item">
        <a class="nav-link bg-dark text-white" href="?do=Logout"><i class="fa fa-power-off fa-fw text-white " aria-hidden="true"></i>&nbsp;Deconnexion</a>
      </li>
    </ul>
  </div>
</nav>
</div>
<?php
 if($_GET['do']=='manage'){ 
     ?>
<div class="container" style="width:800px">
    <div class="card">
    <h2 class="text-center text-danger bg-light">Application Gestion Congés</h2> 
              
                    		 
            <img class="img-thumbnail text-center" style="width:150px; height:150px; display: block;
  margin-left: auto;
  margin-right: auto;
  " src="ImagesPersonnel\<?php echo $pic; ?>" alt='imgperso'>
              <?php
     echo"<h3 class='text-center'>". $_SESSION['Prenom']." ".$_SESSION['Nom'] ."</h3>"; ?>

<h2 class=" text-center bg-success text-white">Mon solde de congé</h2> 
<?php
 $req3 = $conn->prepare("SELECT * FROM soldeconge WHERE CIN=? AND actif=?");
        $req3->execute(array($_SESSION['cin'],1));
        $rows = $req3->fetchall();
      ?>
   
    <table class="table table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Année</th>
      <th scope="col">Jours</th>
      </tr>
  </thead>
  <tbody>
      
   <?php
 foreach($rows as $row)
 {
     
     ?>
   <tr>  
       <td><?php echo $row['Annee'] ;?></td>
       <td><?php echo $row['Jours'];?></td>
    </tr>
 <?php
 }
 ?>
  </tbody>
</table>
</div>
</div>  
    
    
  <?php
    }else if($_GET['do']=='listDEM')
      {
    
    $stmt = $conn->prepare(" SELECT `CIN`, `ID`, `DureConge`, `DateDebut`, `DateDemande`, `Datefin`, `Observation`,`Etat` from conge where visaCSRH='en attente' "); 
    $stmt->execute();
     $rows=$stmt->fetchall();
        
?>
<div class="container" style="width:800px">
    <div class="card">
 <h4 class="text-center  text-white bg-success">Demandes reçues </h4>
<table class="table table-bordered">
  <thead class="thead-dark">
      <tr style="font-size: 12px;" class="text-center">
      <th scope="col">Cin</th>
      <th scope="col">Numéro d'ordre</th>
      <th scope="col">Durée de congé</th>
      <th scope="col">date de début</th>
        <th scope="col">date fin</th>
      <th scope="col">date de demande</th>
      <th scope="col">Observation</th>
        <th scope="col">Etat</th>
      <th scope="col">controle</th>
    </tr>
  </thead>
  <tbody>
 <?php
 
 foreach($rows as $row)
 {
     ?>
   <tr>  
       <td><?php echo $row['CIN'] ;?></td>
       <td><?php echo $row['ID'];?></td>
       <td><?php echo $row['DureConge'] ;?></td>
       <td><?php echo date('d/m/Y',strtotime($row['DateDebut'])) ;?></td>
       <td><?php echo date('d/m/Y',strtotime($row['Datefin'])) ;?></td>
       <td><?php echo date('d/m/Y',strtotime($row['DateDemande'])) ;?></td>  
       <td><?php echo $row['Observation'] ;?></td>  
        <td><?php echo $row['Etat'] ;?></td>
      <td>
          <a href="?do=Oui&id=<?php echo $row['ID']; ?>&CIN=<?php echo $row['CIN'] ;?>"><i class="fa fa-edit text-success"> envoyer au directeur</i></a>
          <a href="?do=Non&id=<?php echo $row['ID']; ?>&CIN=<?php echo $row['CIN'] ;?>"><i class="fa fa-trash text-danger"> refuser</i></a>
      </td>
    </tr>
 <?php
 }
 ?>
  </tbody>
</table>
</div>
    </div>
<?php     
         
      }else if($_GET['do']=='Oui')
      {
          $cin=$_GET['CIN'];
          $id=$_GET['id'];
        
        $req1 = $conn->prepare("UPDATE `conge` SET `visaCSRH`=1 WHERE `CIN`='$cin' AND `ID`='$id'");
        $req1->execute();
        echo "<h4 class='text-center' style='color:green'>................. :) ";
        header("refresh:2;url=?do=listDEM");
          
      }else if($_GET['do']=='Non')
      {
        $cin=$_GET['CIN'];
        $id=$_GET['id'];
        
     $req1 = $conn->prepare("UPDATE `conge` SET `ETAT`='refusée',`visaCSRH`=0  WHERE `CIN`='$cin' AND `id`='$id'");
     $req1->execute();
          
      }else if ($_GET['do']=='addconge1')
    
     {
       ?>
<div class="container" style="width:800px">
    <div class="card">
    <form method="POST" action="?do=Envoyer">
        <h4 class="text-center">Demander un Congé </h4>
  <div class="form-group col-sm-6">
     
    <label for="exampleFormControlInput1">Cin :</label>
    <input type="text" required=""  class="form-control " readonly="" id="exampleFormControlInput1" value="<?php echo $_SESSION['cin']; ?>" name="cin" placeholder="CIN">
  </div>
        
<div class="form-group col-sm-6">
    <label for="exampleFormControlInput1">Nom :</label>
    <input type="text" required="" class="form-control" readonly="" value="<?php echo $_SESSION['Nom']; ?>"  name="Nom" id="exampleFormControlInput1" placeholder="Nom">
  </div>
        <div class="form-group col-sm-6">
    <label for="exampleFormControlInput1">Prénom :</label>
    <input type="text" required="" class="form-control " readonly="" value="<?php echo $_SESSION['Prenom']; ?>" name="Prenom" id="exampleFormControlInput1" placeholder="Prénom">
  </div>
    
<!--
           <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Durée de congé</label>
    <input type="text" required="" class="form-control "  name="dure" id="exampleFormControlInput1" placeholder="la durée">
  </div>-->
       
  
        <div class="form-group col-sm-6">
    <label for="exampleFormControlInput1">Date début :</label>
    <input type="date" required="" class="form-control "  name="debut" id="exampleFormControlInput1" placeholder="aaaa-mm-jj">
  </div>
        <div class="form-group col-sm-6">
    <label for="exampleFormControlInput1">Date Fin :</label>
    <input type="date" required="" class="form-control "   name="fin" id="exampleFormControlInput1" placeholder="aaaa-mm-jj">
  </div>
   <div class="form-group col-sm-6">
    <label for="exampleFormControlInput1">Raison :</label>
    <input type="text" required="" class="form-control "  name="raison"  id="exampleFormControlInput1" placeholder="raison">
  </div>
        <br>
   <div class="form-group col-sm-6">
    <label for="exampleFormControlInput1"></label>
     <input class="btn btn-primary" type="submit" value="Envoyer">
  </div>     
</form>
 </div>
</div>
<?php
 }else if($_GET['do']=='Envoyer')
      {
          $lecin=$_SESSION['cin'];
        $fin=$_POST['fin'];
        //$duree=$_POST['dure'];
        $obs=$_POST['raison'];
        $debut=$_POST['debut'];
        $datedemande=date("Y-m-d");
         
         if (strtotime($_POST['debut'])>strtotime($_POST['fin']))
         {
            echo "<h4 class='text-center' style='color:red;'>la date de début de congé est supérieure à la date de fin , veuillez entrer une date valide!</h4>" ;
             header("refresh:3;url=formauthentif.php?do=add");
         }else{
             $date_depart = strtotime($debut);
             $date_fin = strtotime($fin);
             $duropn = open_days($date_depart, $date_fin);
//echo 'Il y a '.$jours_ouvres.' jours ouvrés entre le '.date('d/m/Y', $date_depart).' et le '.date('d/m/Y', $date_fin);
             echo "<br>";
             
      $req3 = $conn->prepare("SELECT SUM(Jours) AS tot FROM soldeconge WHERE CIN=? AND actif=?");
      $req3->execute(array($_SESSION['cin'],1));
      $rows=$req3->fetch();        
      $tot=$rows['tot'];
      //echo $tot;         
        if($duropn>$tot){
            echo "<h4 class='text-center' style='color:red;'>la durée demandée dépasse votre solde de Congé qui est:".$tot." Veuiller diminuer la durée !</h4>" ;
             header("refresh:4;url=formauthentif.php?do=add");
        }else{
            
            
            $req1 = $conn->prepare("INSERT INTO conge (`CIN`, `DureConge`, `DateDebut`, `Datefin` , `DateDemande`, `Observation`) VALUES ('$lecin','$duropn','$debut', '$fin' ,'$datedemande','$obs')");
        $req1->execute();
        
    echo "<h4 class='text-center' style='color:green'>votre demande a été envoyée avec succés :) ";
        header("refresh:2;url=?do=manage");   
        
            
        }
        }
        }else if ($_GET['do']=='MesDemandes')
      {
        
    $stmt = $conn->prepare("SELECT `CIN`,`id`, `dureconge`, `datedebut`, `datefin`, `datedemande`, `observation`, `Etat` FROM `conge` WHERE CIN =?"); 
    $stmt->execute(array($_SESSION['cin'])); 
    $rows=$stmt->fetchall();
?>
<div class="container" style="width:800px">
    <div class="card">
<table class="table table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">id</th>
      <th scope="col">durée</th>
      <th scope="col">Date début</th>
      <th scope="col">Date Fin</th>
      <th scope="col">Date demande</th>
      <th scope="col">observation</th>
    <th scope="col">Etat</th>
         <th scope="col">Contrôle</th>
         
    </tr>
  </thead>
  <tbody>
 <?php
 
 foreach($rows as $row)
 {
     ?>
   <tr>  
       <td><?php echo $row['id'] ;?></td>
       <td><?php echo $row['dureconge'];?></td>
       <td><?php echo date('d/m/Y',strtotime( $row['datedebut'] ));?></td>
       <td><?php echo  date('d/m/Y',strtotime($row['datefin'] ));?></td>  
       <td><?php echo date('d/m/Y',strtotime( $row['datedemande'])) ;?></td> 
       <td><?php echo $row['observation'] ;?></td>  
       <td><?php echo $row['Etat'] ;?></td>  
 
      <td>
          <a href="?do=EditDem&id=<?php echo $row['id'];?>"><i class="fa fa-edit text-success"> Modifier</i></a>
          <a href="?do=deleteDem&id=<?php echo $row['id'];?>"><i class="fa fa-trash text-danger"> Supprimer</i></a>
      </td>
    </tr>
 <?php
 }
 ?>
  </tbody>
</table>
   
    <a role="button" class="btn btn-primary form-control" href="?do=addconge1" ><i class="fa fa-plus">Ajouter une nouvelle demande de congé</i></a>
    </div>
</div>
    <?php
        } else if($_GET['do']=='EditDem'){
         $cin=$_SESSION['cin'];
         $id=$_GET['id'];
   $stmt = $conn->prepare("SELECT `DureConge`, `DateFin`,`DateDebut` , `Observation` FROM  `conge` WHERE
   id=?"); 
    $stmt->execute(array($id)); 
    $row=$stmt->fetch();
    ?>
<div class="container" style="width:800px">
    <div class="card">
    <form method="POST" action="?do=updateDem">
        <h4 class="text-center">Modifier la demande de congé:<span class="text-danger"><?php echo $id; ?></span></h4>
  <div class="form-group col-sm-4">
       <input type= "hidden" readonly="" class="form-control " id="exampleFormControlInput1" value="<?php echo $id; ?>" name="id">
    <label for="exampleFormControlInput1">Cin :</label>
    <input type="text" readonly="" class="form-control " id="exampleFormControlInput1" value="<?php echo $cin; ?>" name="cin" placeholder="CIN">
  </div>
 <!-- <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Durée de congé:</label>
    <input type="text" class="form-control " value="<//?php echo $row['DureConge']; ?>" name="DureConge"  id="exampleFormControlInput1" placeholder="Durée de congé">
  </div>-->
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Date début :</label>
    <input type="date" class="form-control " value="<?php echo $row['DateDebut']; ?>" name="deb" id="exampleFormControlInput1" placeholder="aaaa-mm-jj">
  </div>
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Date Fin :</label>
    <input type="date" class="form-control " value="<?php echo $row['DateFin']; ?>" name="fin" id="exampleFormControlInput1" placeholder="aaaa-mm-jj">
  </div>
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Raison:</label>
    <input type="text" class="form-control " value="<?php echo $row['Observation']; ?>" name="obsrv"  id="exampleFormControlInput1" placeholder="Observation">
  </div>
        <input class="btn btn-primary" type="submit" value="Valider" >
</form>

</div>
</div>   
    <?php
          
         }else if ($_GET['do']=='updateDem'){
 $id=$_POST['id'];       
 //$duree=$_POST['DureConge'];
        $deb=$_POST['deb'];
        $fin=$_POST['fin'];
        $obsrv=$_POST['obsrv'];
        $debut = strtotime($deb);
        $dfin = strtotime($fin);
        $duree = open_days($debut, $dfin);
        
$req = $conn->prepare(" update conge set DureConge=?,DateDebut=? , DateFin=? , Observation=? where id=?");
          $req->execute(array($duree, $deb ,$fin ,$obsrv, $id));
             echo "<h3 class='text-success text-center'>Modification réussite </h3>";
    header("refresh:1;url=?do=MesDemandes");
 }else if($_GET['do']=='deleteDem')
{
    $id=$_GET['id'];
    $req1 = $conn->prepare("delete from conge where id=? ");
     $req1->execute(array($id)); 
     
     header("refresh:1;url=?do=MesDemandes");
     echo "<h3 class='text-success text-center'>la demande est bien supprimée</h3>";
 } else if($_GET['do']=='Logout')
      {
          session_destroy();
          echo "<h4 class='text-center' style='color:green'>votre session est term :) ";
        header("refresh:2;url=index.php");
    }
else if($_GET['do']=='infos')
      {
       ?> 

<div class="container" style="width:800px">
    <div class="card">
  <table class="table">
  <thead class="thead-dark">
    <tr>
     <th scope="row" colspan="2" class="text-center">Mes Informations</th>
    </tr>
  </thead>
             
<tbody>
    <tr class="text-center">
      <th scope="row" class="text-center">CIN</th>
      <td><?php echo $_SESSION['cin'];?></td>
    </tr>
    
      <tr class="text-center">
      <th scope="row" class="text-center">Nom</th>
      <td><?php echo $user1['Nom'] ;?></td>
    </tr>
      
    <tr class="text-center">
      <th scope="row" class="text-center">Prénom</th>
      <td><?php echo $user1['Prenom'] ;?></td>
    </tr>
      
    <tr class="text-center">
      <th scope="row" class="text-center">Tel</th>
      <td><?php echo $user1['TEL'] ;?></td>
    </tr>
      
    <tr class="text-center">
      <th scope="row" class="text-center">Grade</th>
      <td> <?php echo $user2['Codegrade'] ;?> </td>
   </tr>
      
   <tr class="text-center">
      <th scope="row" class="text-center" >Service</th>
      <td><?php echo $user2['Service'] ;?></td>
   </tr>
     
</tbody>
</table>
    </div>
</div>
     <?php     
 }else if ($_GET['do']=="listperso"){
      
$stmt = $conn->prepare("SELECT Personnel.CIN,Personnel.Nom,Personnel.Prenom,grade.Codegrade,grade.datedebut,grade.datefin,grade.Service FROM Personnel  JOIN grade ON grade.CIN=Personnel.CIN"); 
$stmt->execute(); 
$rows=$stmt->fetchall();
?>
<div class="container" style="width:800px">
    <div class="card">
        <h3 class="text-center">Les Fonctionnaires</h3>
<table class="table table-bordered">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Cin</th>
      <th scope="col">Nom & Prenom</th>
      <th scope="col">Service</th>
      <th scope="col">Date début</th>
      <th scope="col">Date Fin</th>
      <th scope="col">Grade</th>
      
      <th scope="col">controle</th>
    </tr>
  </thead>
  <tbody>
 <?php
 
 foreach($rows as $row)
 {
     ?>
   <tr>  
       <td><?php echo $row['CIN'] ;?></td>
       <td><?php echo $row['Nom'].' '.$row['Prenom'] ;?></td>
       <td><?php echo $row['Service'] ;?></td>  
       <td><?php echo date('d/m/Y',strtotime($row['datedebut'])) ;?></td>  
       <td><?php echo date('d/m/Y',strtotime($row['datefin'])) ;?></td>  
       <td><?php echo $row['Codegrade'] ;?></td>
       
 
      <td>
          <a href="?do=Edit&cin=<?php echo $row['CIN']?>"><i class="fa fa-edit text-success"> Modifier</i></a>
          <a href="?do=delete&cin=<?php echo $row['CIN']?>"><i class="fa fa-trash text-danger"> Supprimer</i></a>
      </td>
    </tr>
 <?php
 }
 ?>
  </tbody>
</table>
    <a href="?do=add" class="btn btn-primary">Ajouter</a>
</div>
</div>
 <?php
    
}else if($_GET['do']=='add')
{
    ?>
   <div class="container" style="width:800px">
       <div class="card">
    <form method="POST" action="?do=insert">
        <h4 class="text-center">Ajouter Personnel </h4>
  <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Cin :</label>
    <input type="text" required="" class="form-control " id="exampleFormControlInput1"  name="cin" placeholder="CIN">
  </div>
<div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Nom :</label>
    <input required="" type="text" class="form-control"  name="Nom" id="exampleFormControlInput1" placeholder="NOM">
  </div>
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Prénom :</label>
    <input type="text" required="" class="form-control "  name="Prenom" id="exampleFormControlInput1" placeholder="PRENOM">
  </div>
  <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Code Grade :</label>
    <input type="text" class="form-control " required="" name="Codegrade"  id="exampleFormControlInput1" placeholder="CODE GRADE">
  </div>
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Date début :</label>
    <input type="date" class="form-control " required="" name="datedebut" id="exampleFormControlInput1" placeholder="aaaa-mm-jj">
  </div>
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Date Fin :</label>
    <input required="" type="date" class="form-control "   name="datefin" id="exampleFormControlInput1" placeholder="aaaa-mm-jj">
  </div>
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Service :</label>
    <input  required="" type="text" class="form-control "  name="Service"  id="exampleFormControlInput1" placeholder="SERVICE">
  </div>
        <input class="btn btn-primary" type="submit" value="Ajouter" >
</form>
    </div>
</div>
<?php
}
else if($_GET['do']=='insert')
{
$cin=$_POST['cin'];
$nom=$_POST['Nom'];
$prenom=$_POST['Prenom'];
$cg=$_POST['Codegrade'];
$deb=$_POST['datedebut'];
$fin=$_POST['datefin'];
$service=$_POST['Service'];

$req1 = $conn->prepare("INSERT INTO Personnel (`CIN`, `Nom`, `Prenom`) VALUES ('$cin','$nom','$prenom')");
$req1->execute();
$req1 = $conn->prepare("INSERT INTO grade (`CIN`, `Codegrade`, `datedebut`, `datefin`, `Service`) VALUES ('$cin','$cg','$deb','$fin','$service')");
$req1->execute();
    header("refresh:2;url=?do=Manage");

   ?>
<div class="container" style="width:800px">
    <h6>Bien Ajouté </h6>
    
</div>
    <!--code à ajouter -->
    <?php
    
}
else if($_GET['do']=='Edit')
{
    $cin=$_GET['cin'];
   $stmt = $conn->prepare("SELECT Personnel.CIN,Personnel.Nom,Personnel.Prenom,grade.Codegrade,grade.datedebut,grade.datefin,grade.Service FROM Personnel  JOIN grade ON grade.CIN=Personnel.CIN WHERE Personnel.CIN=? "); 
    $stmt->execute(array($cin)); 
    $row=$stmt->fetch();
    ?>
<div class="container" style="width:800px">
    <div class="card">
    <form method="POST" action="?do=update">
        <h4 class="text-center">Modifier Personnel Cin :<span class="text-danger"><?php echo $cin; ?></span></h4>
  <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Cin :</label>
    <input type="text" readonly="" class="form-control " id="exampleFormControlInput1" value="<?php echo $cin; ?>" name="cin" placeholder="CIN">
  </div>
<div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Nom :</label>
    <input type="text" class="form-control " value="<?php echo $row['Nom']; ?>" name="Nom" id="exampleFormControlInput1" placeholder="NOM">
  </div>
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Prénom :</label>
    <input type="text" class="form-control " value="<?php echo $row['Prenom']; ?>" name="Prenom" id="exampleFormControlInput1" placeholder="PRENOM">
  </div>
  <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Code Grade :</label>
    <input type="text" class="form-control " value="<?php echo $row['Codegrade']; ?>" name="Codegrade"  id="exampleFormControlInput1" placeholder="CODE GRADE">
  </div>
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Date début :</label>
    <input type="date" class="form-control " value="<?php echo $row['datedebut']; ?>" name="datedebut" id="exampleFormControlInput1" placeholder="aaaa-mm-jj">
  </div>
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Date Fin :</label>
    <input type="date" class="form-control " value="<?php echo $row['datefin']; ?>" name="datefin" id="exampleFormControlInput1" placeholder="aaaa-mm-jj">
  </div>
        <div class="form-group col-sm-4">
    <label for="exampleFormControlInput1">Service :</label>
    <input type="text" class="form-control " value="<?php echo $row['Service']; ?>" name="Service"  id="exampleFormControlInput1" placeholder="SERVICE">
  </div>
        <input class="btn btn-primary" type="submit" value="Valider" >
</form>

</div>

    </div>
   <?php
}else if($_GET['do']=='update')
{
$cin=$_POST['cin'];
$nom=$_POST['Nom'];
$prenom=$_POST['Prenom'];
$cg=$_POST['Codegrade'];
$deb=$_POST['datedebut'];
$fin=$_POST['datefin'];
$service=$_POST['Service'];
 try{
$req = $conn->prepare(" update Personnel set Nom=?,Prenom=? where CIN=?");
          $req->execute(array($nom,$prenom,$cin));
$req1 = $conn->prepare(" update  grade set Codegrade=?,datedebut=?,datefin=?,Service=? where CIN=?");
        $req1->execute(array($cg,$deb,$fin,$service,$cin));
    header("refresh:2;url=?do=Manage");
 }catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
}else if($_GET['do']=='delete')
{
    $cin=$_GET['cin'];
    $req1 = $conn->prepare("delete from Personnel where CIN=? ");
    $req1 = $conn->prepare("delete from grade where CIN=? ");
     $req1->execute(array($cin)); 
     
     header("refresh:2;url=?do=Manage");
     echo"<div class='container'>". 
         "bien supprimé"."</div>";

    }else if($_GET['do']=='vdir')
      {
    
    $stmt = $conn->prepare(" SELECT `CIN`, `ID`, `DureConge`, `DateDebut`, `DateDemande`, `Datefin`, `Observation`,`Etat` , `visaDirecteur` from conge where Etat='en attente'  "); 
    $stmt->execute();
    $rows=$stmt->fetchall();
        
?>
 <h4 class="text-center">Demandes visées par la directrice</h4>
<div class="container" style="width:800px">
    
<table class="table table-bordered">
  <thead class="thead-dark">
      <tr style="font-size: 12px;" class="text-center">
      <th scope="col">Cin</th>
      <th scope="col">Numéro d'ordre</th>
      <th scope="col">Durée de congé</th>
      <th scope="col">date de début</th>
        <th scope="col">date fin</th>
      <th scope="col">date de demande</th>
      <th scope="col">Observation</th>
        <th scope="col">Etat</th>
         <th scope="col">VisaDirect</th> 
      <th scope="col">controle</th>
    </tr>
  </thead>
  <tbody>
 <?php
 
 foreach($rows as $row)
 {
     ?>
   <tr>  
       <td><?php echo $row['CIN'] ;?></td>
       <td><?php echo $row['ID'];?></td>
       <td><?php echo $row['DureConge'] ;?></td>
       <td><?php echo date('d/m/Y',strtotime( $row['DateDebut'])) ;?></td>
       <td><?php echo date('d/m/Y',strtotime($row['Datefin'])) ;?></td>
       <td><?php echo date('d/m/Y',strtotime($row['DateDemande'])) ;?></td>  
       <td><?php echo $row['Observation'] ;?></td>  
        <td><?php echo $row['Etat'] ;?></td>
       <td><?php echo $row['visaDirecteur'] ;?></td>
      <td>
          <a href="?do=confirmer&id=<?php echo $row['ID']; ?>&CIN=<?php echo $row['CIN'] ;?>&visa=<?php echo $row['visaDirecteur'] ;?>"><i class="fa fa-check text-primary"> confirmer </i></a>
     <!--    /<a href="?do=Nonfinal&id=<//?php echo $row['ID']; ?>&CIN=<//?php echo $row['CIN'] ;?>"><i class="fa fa-trash text-danger"> refuser</i></a>-->
      </td>
    </tr>
 <?php
 }
 ?>
  </tbody>
</table>
        
    </div>
   q

    <?php
  }else if($_GET['do']=="confirmer" && $_GET['visa']==1){
    $id=$_GET['id'];
    $cin=$_GET['CIN'];
    $sql=$conn->prepare("UPDATE conge SET Etat='acceptée' WHERE id =? AND CIN=?");
    $sql->execute(array($id,$cin));
     $sql1=$conn->prepare("UPDATE conge SET Actif=1 WHERE id =? AND CIN=?");
    $sql1->execute(array($id,$cin));
    $sql2=$conn->prepare("SELECT DureConge From conge WHERE id=? AND CIN=?");
    $sql2->execute(array($id,$cin));
    $r=$sql2->fetch();        
    $duree=$r['DureConge'];
   // echo " la durée demandée est ".$duree." <br>";
    
    //le solde du fonct
    $req3 = $conn->prepare("SELECT SUM(Jours) AS tot FROM soldeconge WHERE CIN=? AND actif=?");
      $req3->execute(array($_SESSION['cin'],1));
      $rows=$req3->fetch();        
      $tot=$rows['tot'];
     // echo "le solde total est ".$tot."<br>";        
    // le solde de chaque année pour effectuer les calculs
    $sql2=$conn->prepare("SELECT DateDebut From conge WHERE id=? AND CIN=?");
    $sql2->execute(array($id,$cin));
    $r=$sql2->fetch();        
    $debut=$r['DateDebut'];
    
    
 $yearnow= date("Y", strtotime($debut)); 
$yearavant = date("Y", strtotime($debut))-1;
           //solde de l'année courante 
            $req3 = $conn->prepare("SELECT Jours  FROM soldeconge WHERE CIN=? AND actif=? AND Annee=?");
      $req3->execute(array($cin,1,$yearnow));
      $rows=$req3->fetch();        
      $b=$rows['Jours'];
            
            
           //solde de l'année précédente
            $req3 = $conn->prepare("SELECT Jours  FROM soldeconge WHERE CIN=? AND actif=? AND Annee=?");
      $req3->execute(array($cin,1,$yearavant));
      $rows=$req3->fetch();        
      $a=$rows['Jours'];
    
    // echo "solde de ".$yearavant." est ".$a."<br>";
      // echo  "celui de ".$yearnow." est ".$b;
    
         if($duree<=$a){
                $a=$a-$duree;
            }else{
                if($a!=0){
                    $duree=$duree-$a;
                    $a=0;
                    $b=$b-$duree;
                }else{
                    $b=$b-$duree;
                }
                }
            
         //   echo"la nouvelle valeur de ".$yearavant.": ".$a;
           // echo "la nouvelle valeur de ".$yearnow.": ".$b;
    
    //modification de solde  
          try
        { $sql=$conn->prepare("update soldeconge set Jours=? where CIN=? and actif=? and Annee=?");
         $sql->execute(array($a,$cin,1,$yearavant));
        
        $sql=$conn->prepare("update soldeconge set Jours=? where CIN=? and actif=? and Annee=?");
         $sql->execute(array($b,$cin,1,$yearnow));}catch(PDOexception $e){
                echo $e->getMessage();
            }
            
    
    echo "<h3 class='text-center'>La demande de ".$cin." est ecceptée et son solde de congé est changé</h3>";
    header("refresh:4;url=?do=listDEM");
  
}else if ($_GET['do']=="confirmer" && $_GET['visa']==0){
     $id=$_GET['id'];
    $cin=$_GET['CIN'];
    $sql=$conn->prepare("UPDATE conge SET Etat='refusée' WHERE id =? AND CIN=?");
    $sql->execute(array($id,$cin));
     echo "<h3 class='text-center '>La demande de ".$cin." est refusée </h3>";
   header("refresh:1;url=?do=ListDEM");
}
 }
else{
    header('location:index.php');
}
    
?>












s