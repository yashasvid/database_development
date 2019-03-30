<?php include('server.php') ?>
<!DOCTYPE html>
<html>
<head>
  <title>Registration system PHP and MySQL</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1 style="text-align:center">Welcome To Oingo</h1>
<h1 style="text-align:center">It keeps you updated always .....everywhere and anywhere!!<h2>
  <div class="header">
      <h2>Login</h2>
  </div>
  
  <form method="post" action="login.php">
  <p style="text-align:right;font-size: small;">
  		Not a registered user yet? <a href="register.php">Sign up</a>
  	</p>
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  		<label>Username</label>
  		<input type="text" name="username" >
  	</div>
  	<div class="input-group">
  		<label>Password</label>
  		<input type="password" name="password">
  	</div>
  	<div class="input-group">
  		<button type="submit" class="btn" name="login_user">Login</button>
  	</div>
  
  </form>
</body>
</html>
