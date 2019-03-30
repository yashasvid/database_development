<?php    
include("server.php");
//$userId = $_SESSION['uid'];
//$uid = $userId['uid'];
$uid = $_SESSION['uid'];


if(isset($_POST['postfilter'])){
	
	$class = $_POST['class'];
	$state = $_POST['state'];
	$tag = $_POST['tag'];
	$filterradius = $_POST['filterradius'];
	
	$lat = round($_POST['lat'],5);
	$lon = round($_POST['lon'],5);
	
	$startdate = $_POST['startdate'];
	$starttime = $_POST['starttime'].":00";
	$endtime = $_POST['endtime'].":00";
	$enddate = $_POST['enddate'];
	$repetition = $_POST['repetition'];
	
	$lname = $lat.'-'.$lon;
	
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
		$sid=$arrayresult['sid'];
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
	
	//insert into filter
	$query = "select fid from userfilter where uid = '$uid' and lid = '$lid' and filter_radius = '$filterradius' and sid = '$sid' and class = '$class' and tag = '$tag' and state = '$state'";	
	$result = mysqli_query($db ,$query);
	$arrayresult = mysqli_fetch_assoc($result);
	
	if($result->num_rows ==1)
	{
		$fid=$arrayresult['fid'];
		echo "<p style='color:red;'>"."Same Filter already exists for you !!"."</p>";
	}
	else {	
		$query = "insert into userfilter (uid,lid,filter_radius,sid,class,tag,state) values ('$uid','$lid','$filterradius','$sid','$class','$tag','$state')";	
		$result = mysqli_query($db ,$query);
		$fid = mysqli_insert_id($db);
		if($db->error)
		{
		echo "<p style='color:red;'>"."Filter could not be inserted..Check your parameters once"."</p>";

		

		}
		else
		{	echo"<p style='color:green;'>"."Filter Added Successfully...."."</p>";
		
		
			}
	
	}	 
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
<div class="headerhomepage"><h2>Add Filter</h2></div>
<div class="contenthomepage" >
<form method="post" action="addfilter.php">

<br>
Class
<select required name="class">
<option value="self"> just from me</option>
<option value="friend"> from friends</option>
<option value="everybody"> from everyone</option>
</select>

State
<select required name="state">
<option value="happy">happy</option>
<option value="bored">bored</option>
<option value="hungry">hungry</option>
<option value="cheerful">cheerful</option>
<option value="touristy">touristy</option>
<option value="alive">alive</option>
</select>

Tag
<select required name="tag">
<option value="#happy"> #happy</option>
<option value="#shopping"> #shopping</option>
<option value="#christmas"> #christmas</option>
<option value="#food"> #food</option>
<option value="#view"> #view</option>
<option value="#christmascelebration"> #christmascelebration</option>
</select>

Filter Radius
<select required name="filterradius">
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

Select schedule for filter
<br>
<input type="date" name="startdate"> <input type="time"  name="starttime"  min="00:00" max="23:59" >
 to
<input type="time"  name="endtime"  min="00:00" max="23:59" > <input type="date" name="enddate">


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
<input class="btn" type="submit" name ='postfilter' value="Add Filter"
><button class="btn"><a href="./homepage.php" style=" color: white;">Back</a></button>

</form>
</div>
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
   
  
</body>
</html>
 