<?php    
include("server.php");
//$userId = $_SESSION['uid'];
//$uid = $userId['uid'];
$uid = $_SESSION['uid'];
//var_dump($uid);exit;

if(isset($_POST['postnote'])){
	
	$class = $_POST['class'];
	$comments = $_POST['comments'];
	$tag = $_POST['tag'];
	$noteradius = $_POST['noteradius'];
	
	$lat = round($_POST['lat'],5);
	$lon = round($_POST['lon'],5);
	
	$tags = explode(",", $tag);
	
	$startdate = $_POST['startdate'];
	$starttime = $_POST['starttime'].":00";
	$endtime = $_POST['endtime'].":00";
	$enddate = $_POST['enddate'];
	$repetition = $_POST['repetition'];
	$notetext = $_POST['notetext'];
	
	$lname = $lat.'-'.$lon;
	
	date_default_timezone_set ("America/New_York");
	$current_time = date('Y-m-d H:i:s',strtotime('-1 hour'));
	
	if(empty($startdate) || empty($enddate))
	{
		$startdate = null;
		$enddate = null;
	}
	else{
		$startdate = strtotime($startdate);
		$startdate=date("Y-m-d", $startdate);
		$enddate = strtotime($enddate);
		$enddate=date("Y-m-d", $enddate);
	}

	//select lid from location 
	$query = "select lid from location where lat = $lat and lon = $lon";	
	$result = mysqli_query($db ,$query);
	$arrayresult = mysqli_fetch_assoc($result);
	
	if($result->num_rows ==1)
	{
		$lid=$arrayresult['lid'];
	}
	else {
		
		$query = "INSERT INTO location (lname,lat,lon)VALUES('$lname', '$lat','$lon')";	
		$result = mysqli_query($db ,$query);
		$lid = mysqli_insert_id($db);
	}

	if(is_null($startdate)){
		
	//select sid from schedule 
	$query = "select sid from schedule where startdate is null and starttime = CONVERT('$starttime', TIME)and enddate is null and endtime = CONVERT('$endtime', TIME) and repetition = '$repetition'";
		
	}
	else{
	
	//select sid from schedule 
	$query = "select sid from schedule where startdate = '$startdate' and starttime = CONVERT('$starttime', TIME)and enddate ='$enddate' and endtime = CONVERT('$endtime', TIME) and repetition = '$repetition'";
	}
	
	
	$result = mysqli_query($db ,$query);
	$arrayresult = mysqli_fetch_assoc($result);
	
	if($result->num_rows ==1)
	{
		
		$sidarray= mysqli_fetch_assoc($result);
		$sid=$sidarray['sid'];
	}
	else {

		if(is_null($startdate)){
		
			$query = "insert into schedule (starttime,endtime,repetition) values ('$starttime','$endtime','$repetition')";
		}
		else{
			$query = "insert into schedule (starttime,endtime,startdate,repetition,enddate) values ('$starttime','$endtime','$startdate','$repetition','$enddate')";
		}
		$result = mysqli_query($db ,$query);
		$sid = mysqli_insert_id($db);
	}
	
	
	// INSERT INTO note
	$query = "insert into note (notetext,commentenabled,notetime,uid,lid,note_radius,sid,class) values ('$notetext','$comments','$current_time','$uid','$lid','$noteradius','$sid','$class')";
	$result = mysqli_query($db, $query);

	if($db->error)
	{
		echo "<p style='color:red;'>"."Note could not be inserted..Check your parameters once"."</p>";	
		//var_dump($db->error);exit;
	}
	else
	{
		echo"<p style='color:green;'>"."Note Added Successfully...."."</p>";
	}

	
	$noteid = mysqli_insert_id($db);
	foreach ($tags as $key => $val) {
		//var_dump($key.' ->'.$val);
		$query = "insert into notetag (noteid,tag) values ('$noteid','$val')";
	    $result = mysqli_query($db, $query);
		
	 }
	

	
	//var_dump($db->error);exit;
	 
} 

?>
<html>
    <head>
    <link rel="stylesheet" type="text/css" href="style.css">
	   <style>
       /* Set the size of the div element that contains the map */
      #map {
        height: 400px;  /* The height is 400 pixels */
        width: 100%;  /* The width is the width of the web page */
       }
    </style>
    </head>	
<body>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjv31oPC5sbnpCRPpKV6T7lisGJdSkHno&callback=initMap"></script>   
<div class="headerhomepage"><h2>Post a Note</h2></div>

<form method="post" action="postnote.php">
<textarea required rows="4" cols="50" name="notetext"></textarea>

<br>
<select required name="class">
<option value="self"> just for me</option>
<option value="friend"> for friends</option>
<option value="everybody"> for everyone</option>
</select>

<select required name="comments">
<option value="1"> enable comments</option>
<option value="0"> disable comments</option>
</select>

Tag(add , separated tag namespaces)<input required type="text" name="tag" id="tag"> 

Note Radius
<select required name="noteradius">
<option value="100"> 100</option>
<option value="1400"> 1400</option>
<option value="1200">1200</option>
<option value="700"> 700</option>
<option value="1000"> 1000</option>
<option value="200"> 200</option>
</select>

<br><br>
Latitude <input required type="text" name="lat" id="lat"> 
Longitude <input required type="text" name="lon" id="lon">                      
<br><br>

Select schedule for note
<br>
<input type="date" name="startdate"> <input required type="time"  name="starttime"  min="00:00" max="23:59" >
 to
<input required type="time"  name="endtime"  min="00:00" max="23:59" > <input type="date" name="enddate">


<select name="repetition">
<option value="NA"> Does not repeat</option>
<option value="Monday"> Every Monday</option>
<option value="Tuesday"> Every Tuesday</option>
<option value="Wednesday"> Every Wednesday</option>
<option value="Thursday"> Every Thursday</option>
<option value="Friday"> Every Friday</option>
<option value="Saturday"> Every Saturday</option>
<option value="Sunday"> Every Sunday</option>
<option value="All"> Everyday</option>
</select>

<br><br>
<input class="btn" type="submit" name ='postnote' value="Post"> 
<button class="btn"><a href="./homepage.php" style=" color: white;">Back</a></button>
</form>
<div  class="contenthomepage" id="wrapper"  >
<div class="col-md-6">
<div id="map" class="map"></div>
</div>
</div>
<script>

function initMap() {

var newyork = {lat: 40.6971494, lng: -74.2598675};

var map = new google.maps.Map(
  document.getElementById('map'), {zoom: 10, center: newyork});
var marker = new google.maps.Marker({position: newyork, map: map});
	google.maps.event.addListener(map, 'click', function(event) {
  document.getElementById('lat').value = event.latLng.lat();
  document.getElementById('lon').value = event.latLng.lng(); 
  });
}
</script>
   </body></html>