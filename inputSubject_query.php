<?php
	require_once('session.php');
	
	
	if(ISSET($_POST['sub'])){
		$username = $_SESSION['username'];
		$AP_ID = $_POST['taskOption'];
        
        
        $query = "INSERT INTO PrijavaZaIspit (IdStudentiNaPredmet) 
               SELECT ListaNaStudentiNaAktiviranPredmet.IdStudentiNaPredmet
               FROM ListaNaStudentiNaAktiviranPredmet
               WHERE AP_ID = :taskOption AND BrojNaIndex = :username;";
		$conn = new PDO( 'sqlite:db/db_member.sqlite3' );
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':taskOption', $AP_ID);
		$stmt->execute();
		//$row = $stmt->fetch();
		
		header('location:home.php');
		
    }else {header('location:index.php'); 
    } 
?>