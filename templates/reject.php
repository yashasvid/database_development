<?php 
if (!isset($_GET['id']) )
{
echo "Error :Friend Requester id is not passed currectly ";
return false;
}

$id = $_GET['id'];
include("server.php");
//$userId = $_SESSION['uid'];
//$uid = $userId['uid'];
$uid = $_SESSION['uid'];

$sql = "UPDATE friendrequest SET action = 'rejected' WHERE uid ='$id'  and requestuser='$uid'";
$result = mysqli_query($db ,$sql);

  
?>

<html>
    <head>
    <head>
        <title>Accept Request</title>
     <link rel="stylesheet" type="text/css" href="style.css">
  </head>
<body>
   
 <div class="error">   
   You rejected the friend request !!!
</div>
<div class="input-group">
    <button class="btn" onclick="history.go(-1);">Back </button>
</div>
</body>
</html>