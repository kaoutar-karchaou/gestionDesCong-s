<?php
 //if(isset($_SESSION['login']))
  {
include 'formauthentif.php';
   
      if($_GET['do']=='Manage')
    {
?>
<?php
$stmt = $conn->prepare("SELECT Personnel.CIN,Personnel.Nom,Personnel.Prenom,grade.Codegrade,grade.datedebut,grade.datefin,grade.Service FROM Personnel  JOIN grade ON grade.CIN=Personnel.CIN"); 
$stmt->execute(); 
$rows=$stmt->fetchall();
?>
<div class="container">
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
       <td><?php echo $row['Codegrade'] ;?></td>
       <td><?php echo $row['Service'] ;?></td>  
       <td><?php echo $row['datedebut'] ;?></td>  
       <td><?php echo $row['datefin'] ;?></td>  
       
 
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
 <?php
    
}else if($_GET['do']=='add')
{
    ?>
   <div class="container">
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
<div class="container">
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
<div class="container">
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
     echo 'bien supprimé';

    }

  include $pages.'footer.php';
 }
/*else{
    header('location:index.php');
}*/
?>