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
if(!isset($_REQUEST['email'], $_REQUEST['password'], $_REQUEST['id'])){
    exit('inserire email, password e id');
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
    if($stmt->num_rows > 0){
        $stmt->bind_result($password);
        $stmt->fetch();
        //l'account esiste, adesso controllo se le password coincidono
        if(password_verify($_REQUEST['password'], $password)){
            //le password coincidono
            delete($conn);
        } else {
            //le password non coincidono
            echo "username o password errati!";
        }
    } else {
        //username errato
        echo "username o password errati!";
    }
    $stmt->close();
}
//********************************************

/*
* rimuovo il luogo
*/
function delete($conn){
if($stmt = $conn->prepare('DELETE FROM cose_da_fare WHERE idLuogo = ?')){
    $stmt->bind_param('i', $_REQUEST['id']);
    $stmt->execute();
  }
  if($stmt = $conn->prepare('DELETE FROM luoghi WHERE id = ?')){
    $stmt->bind_param('i', $_REQUEST['id']);
    $stmt->execute();
    echo "luogo eliminato";
  }
}
//********************************************

?>
