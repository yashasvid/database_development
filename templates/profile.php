<?php    
include("server.php");
//$userId = $_SESSION['uid'];
//$uid = $userId['uid'];
$uid = $_SESSION['uid'];

$sql="SELECT uname FROM user where uid='$uid' ";
$result = mysqli_query($db ,$sql);
$unamearray = mysqli_fetch_assoc($result);
$uname = $unamearray['uname'];
  
$sql="SELECT email FROM user where uid='$uid' ";
$result = mysqli_query($db ,$sql);
$emailarray = mysqli_fetch_assoc($result);
$email = $emailarray['email'];

$sql="SELECT birthday FROM user where uid='$uid' ";
$result = mysqli_query($db ,$sql);
$dobarray = mysqli_fetch_assoc($result);
$dob = $dobarray['birthday'];


$sql="SELECT gender FROM user where uid='$uid' ";
$result = mysqli_query($db ,$sql);
$genderarray = mysqli_fetch_assoc($result);
$gender = $genderarray['gender'];

$sql="SELECT gender FROM user where uid='$uid' ";
$result = mysqli_query($db ,$sql);
$genderarray = mysqli_fetch_assoc($result);
$gender = $genderarray['gender'];

$sql="SELECT phone FROM user where uid='$uid' ";
$result = mysqli_query($db ,$sql);
$phonearray = mysqli_fetch_assoc($result);
$phone = $phonearray['phone'];

$sql="SELECT city FROM user where uid='$uid' ";
$result = mysqli_query($db ,$sql);
$cityarray = mysqli_fetch_assoc($result);
$city = $cityarray['city'];

?>
<html>
    <head>
        <title>Profile Updation
                  </title>
                  <link rel="stylesheet" type="text/css" href="style.css">
        </head>

<body>
<div class="headerhomepage">
    <h2>Profile</h2>
</div>

<form method="post" action="">
User Name    <input type="text" name="uname" value="<?php echo $uname; ?>"><br><br>
Email ID      <input type="text" name="email" value="<?php echo $email; ?>"><br><br>
Date of Birth <input type="date" name="dob" value="<?php echo $dob; ?>" ><br><br>
Gender 
<select required name="gender" value="<?php echo $gender;?>">
<option value="female">Female</option>
<option value="male">Male</option>
</select><br><br>
Phone <input type="text" name="phone"  value="<?php echo $phone; ?>"><br><br>
City  <input type="text" name="city"  value="<?php echo $city; ?>"><br><br>
<input type="submit"  name="update_profile" class="btn" value="Submit">
</form>


<div class="input-group">

<button class="btn" onclick="history.go(-1);">Back </button>

  </div>
  
  <?php
 
//$userId = $_SESSION['uid'];
//$uid = $userId['uid'];
$uid = $_SESSION['uid'];



  if (isset($_POST['update_profile'])) {
    $uname =$_POST['uname'];
    $email =$_POST['email'];
    $dob =$_POST['dob'];
    $gender =$_POST['gender'];
    $phone =$_POST['phone'];
    $city =$_POST['city'];
    $sql = "UPDATE user SET uname = '$uname' , email='$email' ,birthday='$dob', gender='$gender', phone='$phone', city='$city' WHERE uid ='$uid' ";
    $result = mysqli_query($db ,$sql);
    if($result)
		{
			echo"<p style='color:green;'>"."Profile Updated ..."."</p>";
		

		}
		else
		{
		echo "<p style='color:red;'>"."There was some issue while updating"."</p>";

			}
  }

?>
</body>
</html>
