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
<div class="container">
<div class="d-flex justify-content-center ">
		<div class="card" >
			<div class="card-header bg-warning">
				<h3 class="text-center text-white">Gestion de Congés Application</h3>
                
			</div>
			<div class="card-body" >
                	
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link bg-primary text-white" href="?do=manage"><i class="fa fa-home fa-fw text-white " aria-hidden="true"></i>&nbsp;Accueil <span class="sr-only">(current)</span></a>
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
  <?php  
     if($_GET['do']=='listDEM')
      {
    
    $stmt = $conn->prepare(" SELECT `CIN`, `ID`, `DureConge`, `DateDebut`, `DateDemande`, `Datefin`, `Observation`,`Etat` from conge where visaCSRH='en attente' "); 
    $stmt->execute();
     $rows=$stmt->fetchall();
        
?>
 <h4 class="text-center">Demandes reçues </h4>
<div class="container">
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
  <tbody>-
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
<?php     
         
      }
      ?>
    </div>




</div>
            <?php     
         
      }
      ?>