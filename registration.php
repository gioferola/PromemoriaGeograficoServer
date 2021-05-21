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
* controllo che i parametri esistano
*/
if(!isset($_REQUEST['nome'],$_REQUEST['cognome'], $_REQUEST['email'], $_REQUEST['password'])){
  //se non inviati esce
  exit('inserire tutti i campi');
}
//****************************************

/*
  * controllo che l'email non sia già usata
  */
if($stmt = $conn->prepare('SELECT * FROM persone WHERE email = ?')){
  $stmt->bind_param('s', $_REQUEST['email']);
  $stmt->execute();
  //salvo il risultato
  $stmt->store_result();
  if($stmt->num_rows > 0){
    exit('email già usata');
  } 
  $stmt->close();
}
//********************************************

/*
*salvo l'utente
*/

if($stmt = $conn->prepare('INSERT INTO persone (nome, cognome, password, email) VALUES (?, ?, ?, ?)')){
  $password = password_hash($_REQUEST['password'],PASSWORD_BCRYPT);
  $stmt->bind_param('ssss', $_REQUEST['nome'], $_REQUEST['cognome'],$password, $_REQUEST['email']);
  $stmt->execute();
  $stmt->close();
  exit('Registrazione effettuata con successo');
}

?>
