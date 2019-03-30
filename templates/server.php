<?php
session_start();

// initializing variables
$username = "";
$email    = "";
$errors = array(); 

// connect to the database
$db = mysqli_connect('localhost', 'root', '', 'oingo');

// REGISTER USER
if (isset($_POST['reg_user'])) {
  // receive all input values from the form
  $uname = mysqli_real_escape_string($db, $_POST['username']);
  $email = mysqli_real_escape_string($db, $_POST['email']);
  $pwd_1= mysqli_real_escape_string($db, $_POST['password_1']);
  $pwd_2= mysqli_real_escape_string($db, $_POST['password_2']);

  // form validation: ensure that the form is correctly filled ...
  // by adding (array_push()) corresponding error unto $errors array
  if (empty($uname)) { array_push($errors, "Username is required"); }
  if (empty($email)) { array_push($errors, "Email is required"); }
  if (empty($pwd_1)) { array_push($errors, "Password is required"); }
  if ($pwd_1 != $pwd_2) {
	array_push($errors, "The two passwords do not match");
  }

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
  $user_check_query = "SELECT * FROM user WHERE uname='$uname' OR email='$email' LIMIT 1";
  $result = mysqli_query($db, $user_check_query);
  $user = mysqli_fetch_assoc($result);
  
  if ($user) { // if user exists
    if ($user['uname'] === $uname) {
      array_push($errors, "Username already exists");
    }

    if ($user['email'] === $email) {
      array_push($errors, "email already exists");
    }
  }



  // Finally, register user if there are no errors in the form
  if (count($errors) == 0) {
    $pwd= md5($pwd_1);//encrypt the password before saving in the database

  	$query = "INSERT INTO user (uname, pwd,email) 
  			  VALUES('$uname', '$pwd','$email')";
  	mysqli_query($db, $query);
  	$_SESSION['username'] = $uname;
    $_SESSION['success'] = "You are now logged in";
  
    $query2 = "SELECT uid FROM user WHERE uname='$uname' AND pwd='$pwd'";
    $result2 = mysqli_query($db, $query2);
    $idarray= mysqli_fetch_assoc($result2);
    
    $_SESSION['uid'] = $idarray['uid'];

  	header('location: homepage.php');
  }

}

// ... 
//LOGIN USER
if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
  
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }
  
    if (count($errors) == 0) {
      $pwd=md5($password);
      if ($stmt = mysqli_prepare($db, "SELECT uid FROM user WHERE uname=? and pwd=?")) {
        mysqli_stmt_bind_param($stmt, "ss", $username,$pwd);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $uid);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['uid'] = $uid;
      }

        if ($uid > 0) {
          $_SESSION['username'] = $username;
          $_SESSION['success'] = "You are now logged in";
          header('location: homepage.php');
        }else {
            array_push($errors, "Wrong username/password combination");
        }
    }
  }
  
  //view all users
  if (isset($_POST['view_user'])) {
    header('location: viewusers.php');
  }

  //view Friend Request
  if (isset($_POST['friend_request'])) {
    header('location: friendrequests.php');
  }
  //Update user profile
  if (isset($_POST['profile_update'])) {
    header('location:profile.php');
  }
  //post a note
  if (isset($_POST['post_note'])) {
    header('location: postnote.php');
  }
  //add update user filter
  if (isset($_POST['add_upd_filter'])) {
    header('location: addfilter.php');
  }
 
  ?>