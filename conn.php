<?php
	$conn = new PDO('sqlite:db/db_member.sqlite3');
	
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
	$query = "CREATE TABLE IF NOT EXISTS Login(username INTEGER PRIMARY KEY NOT NULL, password TEXT)";
	
	$conn->exec($query);
?>