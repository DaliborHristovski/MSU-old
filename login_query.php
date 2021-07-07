<?php
	session_start();
	require_once 'conn.php';
	
	if(ISSET($_POST['login'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		$firstLogin = 0;
		
		$query = "SELECT COUNT(*) as count FROM `Login` WHERE `username` = :username AND `password` = :password";
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->execute();
		$row = $stmt->fetch();
		
		
		$count = $row['count'];
	
		if($count < 1){
		$query = "SELECT COUNT(*) as count FROM `Student` WHERE `BrojNaIndex` = :username AND `EMBG` = :password";
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':password', $password);
		$stmt->execute();
		$row = $stmt->fetch();
		
		$count = $row['count'];
		$firstLogin = 1;
		}
		if($count > 0){
			$_SESSION['logged']=true;
			$_SESSION['username'] =$username;
			if($firstLogin){
				$query = "INSERT INTO 'Login' (`username`,`password`) VALUES(:username ,:password)";
				$stmt = $conn->prepare($query);
				$stmt->bindParam(':username', $username);
				$stmt->bindParam(':password', $password);
				$stmt->execute(); 
			}
			$firstLogin = 0;
			header('location:home.php');
		}
		else{
			$_SESSION['error'] = "Invalid username or password";
			header('location:logout.php');
		}
	}

?>