<?php 
if (!isset($_GET['uname']) )
{
echo "Error :User Name who has to be added as a friend is not passed currectly ";
return false;
}

$friendname = $_GET['uname'];
include("server.php");
//$userId = $_SESSION['uid'];
//$uid = $userId['uid'];
$uid = $_SESSION['uid'];


$getfrienduid = "SELECT uid FROM user WHERE uname='$friendname'";
$results = mysqli_query($db, $getfrienduid);
$uids = mysqli_fetch_assoc($results);
$friendid=$uids['uid'];

$sql = "INSERT INTO friendrequest(uid,requestuser)
        VALUES('$uid','$friendid') ";
$result = mysqli_query($db ,$sql);
?>

<html>
    <head>
    <head>
        <title>Friend Request Sent </title>
     <link rel="stylesheet" type="text/css" href="style.css">
  </head>
<body>
   
 <div class="success">   
   Friend request sent .....Awaiting Response !!!
</div>
<div class="input-group">
    <button class="btn" onclick="history.go(-1);">Back </button>
</div>
</body>
</html>