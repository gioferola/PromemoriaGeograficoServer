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
if(!isset($_REQUEST['nome'],$_REQUEST['descrizione'], $_REQUEST['idLuogo'], $_REQUEST['email'], $_REQUEST['password'])){
    //se non inviati esce
    exit('inserire tutti i campi');
}
//****************************************

/*
  * controllo che il luogo sia presente
  */
if($stmt = $conn->prepare('SELECT * FROM luoghi WHERE id = ?')){
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
            insert($conn);
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
*salvo l'attivita
*/
function insert($conn){
  if($stmt = $conn->prepare('INSERT INTO cose_da_fare (nome, descrizione, idLuogo, fatto) VALUES (?, ?, ?, 0)')){
    $stmt->bind_param('ssi', $_REQUEST['nome'], $_REQUEST['descrizione'], $_REQUEST['idLuogo']);
    $stmt->execute();
    $stmt->close();
    exit('Attività inserita con successo');
  }
}

?>
