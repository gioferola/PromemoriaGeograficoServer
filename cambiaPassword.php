<?php
/*
* conessione al db
*/
$host = "localhost";
$dbUser = "gioferola";
$dbPwd = "";
$dbName = "my_gioferola";
$conn = new mysqli($host, $dbUser, $dbPwd, $dbName);
if ($conn->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
/*
* controllo che il parametro esista
*/
if(!isset($_REQUEST['email']) || !isset($_REQUEST['vecchia']) || !isset($_REQUEST['nuova'])){
    exit('inserire email, vecchia e nuova password');
}
//****************************************

/*
  * controllo che l'email sia presente
  */
if($stmt = $conn->prepare('SELECT password FROM persone WHERE email = ?')){
  $stmt->bind_param('s', $_REQUEST['email']);
  $stmt->execute();
  //salvo il risultato
  $stmt->store_result();
  if($stmt->num_rows <= 0){
      exit('email non presente');
  } else {
  	//la mail è presente
    $stmt->bind_result($password);
    $stmt->fetch();
    //controllo se le vecchia password è corretta
    if(password_verify($_REQUEST['vecchia'], $password)){
      //le password coincidono
      modify($conn);
    } else {
      //le password non coincidono
      echo "vecchia password errata";
    }
  } 
  $stmt->close();
}
//********************************************

/*
* modifico la password
*/
function modify($conn){
	$password = password_hash($_REQUEST['nuova'],PASSWORD_BCRYPT);
	if($stmt = $conn->prepare('UPDATE persone SET password = ? WHERE email = ?')){
      $stmt->bind_param('ss', $password, $_REQUEST['email']);
      $stmt->execute();
      echo "password modificata";
	}
}
//********************************************
?>
