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
if(!isset($_REQUEST['idLuogo'], $_REQUEST['id'], $_REQUEST['email'], $_REQUEST['password'])){
    exit('inserire idLuogo,id, email e password');
}
//****************************************

/*
  * controllo che l'idLuogo sia presente
  */
if($stmt = $conn->prepare('SELECT * FROM luoghi WHERE idLuogo = ?')){
$stmt->bind_param('i', $_REQUEST['idLuogo']);
$stmt->execute();
//salvo il risultato
$stmt->store_result();
if($stmt->num_rows <= 0){
    exit('luogo non presente');
} 
$stmt->close();
}
//********************************************
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
* rimuovo la cosa da fare
*/

function delete($conn){
  if($stmt = $conn->prepare('DELETE FROM cose_da_fare WHERE idLuogo = ? AND id = ?')){
    $stmt->bind_param('ii', $_REQUEST['idLuogo'], $_REQUEST['id']);
    $stmt->execute();
    echo "cose da fare eliminato";
  }
}
//********************************************

?>
