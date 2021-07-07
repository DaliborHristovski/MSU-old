<!DOCTYPE html>
<?php session_start()?>
<html lang="mk">
	<head>
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
	</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<a class="navbar-brand" href="https://msu.edu.mk/">МСУ</a>
		</div>
	</nav>
	<div class="col-md-3"></div>
	<div class="col-md-6 well offset-xl-3">
		<h3 class="text-primary"> Логирајте се на новото Е-учење</h3>
		<hr style="border-top:1px solid #ccc;"/>
		
		<br style="clear:both;"/><br />
		<div class="col-md-3"></div>
		<div class="col-md-6 offset-xl-1">
			<form method="POST" action="login_query.php">	
				<div class="alert alert-info">Login</div>
				<div class="form-group">
					<label>Username</label>
					<input type="text" name="username" class="form-control" required="required"/>
				</div>
				<div class="form-group">
					<label>Password</label>
					<input type="password" name="password" class="form-control" required="required"/>
				</div>
				<?php
					if(ISSET($_SESSION['error'])){
				?>
					<div class="alert alert-danger"><?php echo $_SESSION['error']?></div>
				<?php
					session_unset($_SESSION['error']);
					}
				?>
				<button class="btn btn-primary btn-block" name="login"><span class="glyphicon glyphicon-log-in"></span> Login</button>
			</form>	
		</div>
	</div>
</body>
</html>