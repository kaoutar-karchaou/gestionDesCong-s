
<?php 
session_start();
include 'ini.php'; 
if(!isset($_SESSION['login']))
{
 if($_SERVER['REQUEST_METHOD']=='POST')
 {
  if(!empty($_POST['login']) && !empty($_POST['pass']))
   {
    $login = addslashes($_POST['login']); 
    $pass = addslashes(md5($_POST['pass']));
   
    $req = $conn->prepare("SELECT CIN,CodePrivilege FROM authentification WHERE authentification.login='$login' AND authentification.MDP='$pass'");
    $req->execute();
    $user = $req->fetch();
      if($user == null){
          echo "<div class='container'> <h3 class='text-danger'>Votre nom d'utilisateur ou mot de passe est inccorect, veuillez réessayer!</h3> </div>";
         
        }else{
        $_SESSION['login']=$login;
        $_SESSION['pass']=$pass;
        $_SESSION['cin']=$user['CIN'];
        $_SESSION['CodePrivilege']=$user['CodePrivilege'];
        $lecin=$_SESSION['cin'] ;
        header("location:formauthentif.php");
             }
    }else{
       echo "<div class='container'> <h3 class='text-danger text-center'>remplir les champs</h3> </div>";
         }
  } 
}else{
    header("location:formauthentif.php");
     }
?>
<html>
    <head>
     <link rel="stylesheet" type="text/css" href="Layout/Css/global.css">
    </head>
<div class="container">
	<div class="d-flex justify-content-center ">
		<div class="card" >
			<div class="card-header bg-warning">
				<h3 class="text-center text-white">Gestion de Congés Application</h3>
                <?php
                   // echo $user1['Nom']."".$user1['prenom'];
                ?>
				
			</div>
			<div class="card-body" >
                	
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ; ?>">
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text bg-info"><i class="fa fa-user "></i></span>
						</div>
						<input type="text" name="login" class="form-control" placeholder="entrez votre nom d'utilisateur">
					</div>
					<div class="input-group form-group">
						<div class="input-group-prepend">
							<span class="input-group-text bg-info"><i class="fa fa-key "></i></span>
						</div>
						<input type="password" name="pass" class="form-control" placeholder="entrez le mot de pass">
					</div>
                                         
					<div class="form-group">
						<input type="submit" value="Login" class="btn float-right login_btn bg-info">
					</div>
                                        
				</form>
			</div>
			<div class="card-footer bg-warning">
				<div class="d-flex justify-content-center links">
				</div>
			</div>
			</div>
 
		</div>
	</div>
