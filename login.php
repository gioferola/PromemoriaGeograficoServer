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
if(!isset($_REQUEST['email'], $_REQUEST['password'])){
    exit('inserire email e password');
}
//****************************************

/*
* controllo login
*/
//recupero i dati di quell'utente se esiste
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
            accedi($conn);
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

function accedi($conn){
    $luoghi = array();
    if($stmt = $conn->prepare('SELECT * FROM luoghi WHERE emailPersona = ?')){
        $stmt->bind_param('s', $_REQUEST['email']);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($luogo = $result->fetch_assoc()) {
            $luoghi[] = $luogo;
        }
    }
    print json_encode($luoghi);
}


?>
