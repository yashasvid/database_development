<?php    
include("server.php");
//$userId = $_SESSION['uid'];
//$uid = $userId['uid'];
$uid = $_SESSION['uid'];


$sql_friends ="create temporary table frienduids SELECT uid FROM `friendrequest`
 where requestuser = '$uid' and action = 'pending'" ;
 $resulttemp = mysqli_query($db ,$sql_friends);

$query= "select uid ,uname from frienduids natural join user"; 
$result = mysqli_query($db ,$query);
$numRows = mysqli_num_rows($result);


$isempty_friends=0;
if($numRows==0) {
    $isempty_friends=1;
} 

?>

<html>
	<head>
		<title> Friend Requests </title>
		<link rel="stylesheet" type="text/css" href="style.css">

	</head>
	<body>
	<div class="headerhomepage">
    <h2>Friend Requests For You </h2>
</div>
 
   <table width="600"  cellpadding="1" cellspacing="1">
  <tr>
  <th>UserName </th>
  <th>Accept</th>
  <th>Reject</th>
  </tr>
  <?php
  if($isempty_friends==1)
  echo "<div style ='font:15px/21px Arial,tahoma,sans-serif;color:red'>No Friend Requests</div>";   
   ?>
  <?php
     while ($record=mysqli_fetch_assoc($result)){
        $param=$record['uid'];
        
 ?>
 
   <tr>
   <form action="" method="post" >
	   <td ><?php echo $record['uname']; $uname=$record['uname'];?></td>
       

        <td ><button class="btn"><a href="./accept.php?id=<?php echo $param;?>" style=" color: white;">Accept Request</a></button></td>
        <td ><button class="btn"><a href="./reject.php?id=<?php echo $param;?>" style="color: white;">Reject Request</a></button></td>
     </form>
   </tr>
     </form>
  <?php }?>

</table>

<div class="input-group">

				  <button class="btn" onclick="history.go(-1);">Back </button>

                    </div>
   </body>

   <?php
      if (isset($_POST['accept_request'])) {
        var_dump($uname);
        exit;
      }
    if (isset($_POST['reject_request'])) {
            var_dump($uname);
            exit;
    }
   ?>
</html>