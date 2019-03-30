<?php 

include("server.php");
//$userId = $_SESSION['uid'];
//$uid = $userId['uid'];
$uid = $_SESSION['uid'];

$noteid = $_GET['noteid'];

$notetext = "Select notetext from note where noteid='$noteid'";
$notetextresult = mysqli_query($db ,$notetext );
$notetextarray= mysqli_fetch_assoc($notetextresult);
    $notetext=$notetextarray['notetext'];



if (!isset($_GET['noteid']) )
{
echo "Error :NoteID is not passed currectly ";
return false;
}

$noteid = $_GET['noteid'];

if (isset($_POST['postcomment'])) {

    
    $current_date = date("Y-m-d H:i:s");
    //var_dump($current_date);
    //var_dump($noteid);
    //exit;
    $commenttext = $_POST['commenttext'];
	

    $sql = "Insert into comment(ctext,uid,noteid,time)
       values ('$commenttext', '$uid','$noteid',' $current_date')";
       $result = mysqli_query($db ,$sql);
       if($db->error)
       {
           echo "<p style='color:red;'>"."Comments could not be added"."</p>";	

       }
       else
       {
           echo"<p style='color:green;'>"."Comment added sucessfully !!"."</p>";
           }
}
?>

<html>
    <head>
    <head>
        <title>Comment</title>
     <link rel="stylesheet" type="text/css" href="style.css">
  </head>
<body>
<div class="headerhomepage"><h2>Add your comments for Note:<?php echo "$notetext";?></h2></div>
<div> 
<form method="post" action="">
    <textarea required rows="4" cols="70" name="commenttext"></textarea>
   <br>

<input class="btn" type="submit" name ='postcomment' value="Post"> 
<button class="btn"><a href="./homepage.php" style=" color: white;">Back</a></button>
</form>

</div>
</body>
</html>