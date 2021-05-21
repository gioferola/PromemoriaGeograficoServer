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
if(!isset($_REQUEST['email'], $_REQUEST['nome'], $_REQUEST['cognome'], $_REQUEST['password'])){
    exit('inserire email, password, nome e cognome');
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
            modify($conn);
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
* modifico il nome e il cognome
*/
function modify($conn){
  if($stmt = $conn->prepare('UPDATE persone SET nome = ? WHERE email = ?')){
    $stmt->bind_param('ss', $_REQUEST['nome'], $_REQUEST['email']);
    $stmt->execute();
    echo "nome modificato";
  }
  if($stmt = $conn->prepare('UPDATE persone SET cognome = ? WHERE email = ?')){
    $stmt->bind_param('ss', $_REQUEST['cognome'], $_REQUEST['email']);
    $stmt->execute();
    echo "cognome modificato";
  } 
}
//********************************************

?>
