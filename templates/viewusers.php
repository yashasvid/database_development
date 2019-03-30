<?php    
include("server.php");
//$userId = $_SESSION['uid'];
$uid = $_SESSION['uid'];


$sql_friends="select uname from user where uid in 
(select distinct uid from friendrequest where action = 'accepted' and  requestuser ='$uid'
UNION
select distinct requestuser from friendrequest where action = 'accepted' and uid = '$uid')";
$result = mysqli_query($db ,$sql_friends);
$numRows = mysqli_num_rows($result);

//var_dump($userId);
$isempty_friends=0;
if($numRows==0) {
    $isempty_friends=1;
} 



$sql_sentRequest="select uname from user where uid in  (select requestuser from friendrequest 
where action = 'pending' and uid = '$uid')" ;
$result_request= mysqli_query($db ,$sql_sentRequest);
$numRows_request= mysqli_num_rows($result_request);

//var_dump($userId);
$isempty_sentRequest=0;
if($numRows_request==0) {
    $isempty_sentRequest=1;
} 


$sql_none="select uname from user where uid not in
(select uid from friendrequest where action = 'accepted' and  requestuser = '$uid'
UNION
select requestuser from friendrequest where action = 'accepted' and uid = '$uid'
union 
select requestuser from friendrequest where action = 'pending' and uid = '$uid'
union select uid from user where uid = '$uid')";

$result_none= mysqli_query($db ,$sql_none);
$numRows_none= mysqli_num_rows($result_none);

//var_dump($userId);
$isempty_none=0;
if($numRows_none==0) {
    $isempty_none=1;
} 

?>

<html>
	<head>
		<title> View All users</title>
		<link rel="stylesheet" type="text/css" href="style.css">

	</head>
	<body>
	<div class="headerhomepage">
    <h2>Users Page</h2>
</div>
 
   <table width="600"  cellpadding="1" cellspacing="1">
  <tr>
  <th>UserName </th>
  <th>Status</th>
  </tr>
  <?php
     while ($record=mysqli_fetch_assoc($result)){
 ?>
 
   <tr>
	   <td ><?php echo $record['uname'];?></td>
	   <td><input type="button" class="success btn" value="Friends"></td>
   </tr>
	 
  <?php }
 ?>
<?php
     while ($record=mysqli_fetch_assoc($result_request)){
 ?>
 
   <tr>
	   <td ><?php echo $record['uname'];?></td>
	   <td><input type="button" class=" error btn" value="Friend Request Sent"></td>
   </tr>
	 
  <?php }
 ?>

 <?php
     while ($record=mysqli_fetch_assoc($result_none)){
        $param=$record['uname'];
 ?>
 
   <tr>
	   <td ><?php echo $record['uname'];?></td>
       <td><button class="btn"><a href="./sendrequest.php?uname=<?php echo $param;?>" style="color: white;">Send Friend Request ?
     </a></button></td>
   </tr>
	 
  <?php }
 ?>


</table>

<div class="input-group">

				  <button class="btn" onclick="history.go(-1);">Back </button>

                    </div>
   </body>
</html>