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

  	<h2>Sign Up</h2>
  </div>
	
  <form method="post" action="register.php">
  <p style="text-align:right;font-size: small;">
            Registered User? <a href="login.php">Sign in</a>
  	</p>
  	<?php include('errors.php'); ?>
  	<div class="input-group">
  	  <label>Username</label>
  	  <input type="text" name="username" value="<?php echo $username; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Email</label>
  	  <input type="email" name="email" value="<?php echo $email; ?>">
  	</div>
  	<div class="input-group">
  	  <label>Password</label>
  	  <input type="password" name="password_1">
  	</div>
  	<div class="input-group">
  	  <label>Confirm password</label>
  	  <input type="password" name="password_2">
  	</div>
  	<div class="input-group">
  	  <button type="submit" class="btn" name="reg_user">Register</button>
  	</div>
  	
  </form>
</body>
</html>