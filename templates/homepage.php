<?php
  include('server.php');
  
  if (!isset($_SESSION['username'])) {
      $_SESSION['msg'] = "You must log in first";
      header('location: login.php');
  }
  
  if (isset($_GET['logout'])) {
      session_destroy();
      unset($_SESSION['username']);
      header("location: login.php");
  }
  	    
  if (isset($_POST['current_param'])) {
	  
		//location 
		$lat =round($_POST['lat'],5);
		$lon = round($_POST['lon'],5);
		$lname = $lat.'-'.$lon;
		$state=$_POST['state'];
		
		//user id
		//$userid = $_SESSION['uid'];
		//$uid = $userid['uid'];
		$uid = $_SESSION['uid']; 
		
		//schedule
		$date = $_POST['date'];
		$time = $_POST['time'].":00";
		$date = strtotime($date);
		$date=date("Y-m-d", $date);
		
		//current time
		date_default_timezone_set ("America/New_York");
		$current_time = date('Y-m-d H:i:s',strtotime('-1 hour'));
		
		//fetch lid from current_param 
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
			
		//insert query for history table
		$query = "INSERT INTO history (uid,lid,state,loctime)VALUES('$uid','$lid','$state','$current_time')";	
		$result = mysqli_query($db ,$query);
		//var_dump($db->error);exit;
		 
		//find notes visible at current time and location given in current_param
		$query = "create temporary table vn select * from note natural join location natural join schedule
		where checkdistance('$lat','$lon',lat,lon) <= note_radius
		and  
		(((CONVERT('$time', TIME) <= endtime and CONVERT('$time', TIME) >= starttime) and 
		(dayofmonth('$date') = repetition or repetition = 'All' )and (startdate is null and enddate is null))
		or
		( '$date' <= enddate and '$date' >= startdate and repetition = 'NA' and CONVERT('$time', TIME) <= endtime and CONVERT('$time', TIME) >= starttime))";	
		$result = mysqli_query($db ,$query);		

		$query = "create TEMPORARY table fn1 select * from vn natural join friendship where vn.class= 'self' and vn.uid = '$uid'";	
		$result = mysqli_query($db ,$query);
		//var_dump($result);exit;	
		
		$query = "create TEMPORARY table fn2 select * from vn natural join friendship where vn.class= 'friend' and friendship.friendid = '$uid'";	
		$result = mysqli_query($db ,$query);
		//var_dump($result);exit;	
		
		$query = "create TEMPORARY table fn3 select * from vn natural join friendship where vn.class= 'everybody'";	
		$result = mysqli_query($db ,$query);
		//var_dump($result);exit;
		
		$query = "create temporary table fn4 select * from fn1 union (select * from fn2) union (select * from fn3)";	
		$result = mysqli_query($db ,$query);
		//var_dump($result);exit;
		
		$query = "Set @tag = (select distinct tag from userfilter NATURAL JOIN location NATURAL JOIN schedule where checkdistance('$lat','$lon',lat,lon) <= filter_radius and (((CONVERT('13:00:00', TIME) <= endtime and CONVERT('13:00:00', TIME) >= starttime) and (dayofmonth('2018-12-13') = repetition or repetition = 'All' )and (startdate is null and enddate is null)) or ( '2018-12-13' <= enddate and '2018-12-13' >= startdate and repetition = 'NA' and CONVERT('13:00:00', TIME) <= endtime and CONVERT('13:00:00', TIME) >= starttime))
		and uid = '$uid' and state = '$state')";
		$result = mysqli_query($db ,$query);
		
		$query = "set @tag = (select ifnull(@tag,'#happy'))";
		$result = mysqli_query($db ,$query);
		
		$query = "Set @class = (select distinct class from userfilter NATURAL JOIN location NATURAL JOIN schedule where checkdistance('$lat','$lon',lat,lon) <= filter_radius and (((CONVERT('$time', TIME) <= endtime and CONVERT('$time', TIME) >= starttime) and 
		(dayofmonth('$date') = repetition or repetition = 'All' )and (startdate is null and enddate is null))
		or
		( '$date' <= enddate and '$date' >= startdate and repetition = 'NA' and CONVERT('$time', TIME) <= endtime and CONVERT('$time', TIME) >= starttime)) and uid = '$uid' and state = '$state')";	
		$result = mysqli_query($db ,$query);
		//var_dump($result);exit;
		
		$query = "set @class = (select ifnull(@class,'everybody'))";
		$result = mysqli_query($db ,$query);
		
		$query = "create TEMPORARY table fn5 select * from fn4 NATURAL JOIN notetag where (tag = @tag and @class ='self' and uid = '$uid' ) or (tag = @tag and @class ='friend' and friendid = '$uid' )or (tag = @tag and @class ='everybody')";	
		$result = mysqli_query($db ,$query);
		//var_dump($result);exit;
		
		//execute this query to get notes based on current_param
		//$query = "select distinct notetext from fn5";	
		//$result = mysqli_query($db ,$query);
		//var_dump($result);exit;
			
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link href="/Oingo/static/css/bootstrap.min.css" rel="stylesheet">
    <link href="/Oingo/static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="style.css">
	 <!-- Date Picker -->
	 <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.0/css/bootstrap-datepicker3.min.css"
    rel="stylesheet">
      <style>
      /* Set the size of the div element that contains the map */
          #map {
                 height: 400px;  /* The height is 400 pixels */
                 width: 100%;  /* The width is the width of the web page */
               }
        </style>


     <!-- Common scripts -->
     <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
     <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.js"></script>
         
    <script src="https://cdn.rawgit.com/Eonasdan/bootstrap-datetimepicker/e8bddc60e73c1ec2475f827be36e1957af72e2ea/src/js/bootstrap-datetimepicker.js"></script>
         
         
	
</head>
<body>

<div class="headerhomepage">
    <h2>Home Page</h2>
</div>
<div class="contenthomepage" >
      <!-- notification message -->
      <?php if (isset($_SESSION['success'])) : ?>
      <div class="error success" >
          <h3>
          <?php 
              echo $_SESSION['success']; 
              unset($_SESSION['success']);
          ?>
          </h3>
      </div>
      <?php endif ?>

    <!-- logged in user information -->
    <?php  if (isset($_SESSION['username'])) : ?>
        <p>Welcome <strong><?php echo $_SESSION['username']; ?>, </strong><a href="homepage.php?logout='1'" style="color: red;">click here to logout </a> </p><br>
    <?php endif ?>
	<form action="server.php" method="post" >
    <input type="submit" class="btn" value="Post a note" name="post_note">
    <input type="submit" class="btn" value="Create/Update Profile" name="profile_update">
    <input type="submit" class="btn" value="View Users" name="view_user">
	<input type="submit" class="btn" value="View Friend Requests" name="friend_request">
	<input type="submit" class="btn" value="Add Filter" name="add_upd_filter">
	</form>
</div>
  <?php
  
  $query = "SELECT distinct noteid,notetext from fn5";
  $result = mysqli_query($db,$query);
  if ($result !== FALSE)
	{
		$i = 1;
		while ($row = $result->fetch_assoc()) {    
			$html ="<br><p>". $i."  ".$row['notetext']."</p>
			<button class='btn'><a href='./comment.php?noteid=".$row['noteid']."' style='color: white;'>Comment</a></button>";
			echo $html;
			$i++;  
			
		}
	} 
	else {
		$html ="<div  align='center'><p> No notes for given parameters. Please give valid parameters.</p></div>"; 
		echo $html;
	}
	 
  ?>
  <br>
   <div  class="contenthomepage" id="wrapper"  >
        <div class="row">
          <div class="col-md-6" >
            <form method="post" action="<?=$_SERVER['PHP_SELF'];?>"  >

                
				<div id="location" alignment="center">
				Latitude  <input required type="text" name="lat" id="lat"> Longitude  <input required type="text" name="lon" id="lon"><br>
				</div>
				
				<br>
				State
				<select required name="state">
				<option value="happy">happy</option>
				<option value="bored">bored</option>
				<option value="hungry">hungry</option>
				<option value="cheerful">cheerful</option>
				<option value="touristy">touristy</option>
				<option value="alive">alive</option>
				</select>
				<br><br>
				
				Select date and time 
				<input required type="date" name="date"> <input required type="time"  name="time"  min="00:00" max="23:59" >
				
                <div class="input-group">
  		        <button type="submit" class="btn" name="current_param">Submit</button>
                </div>
            </form>
			</div>
			<div class="col-md-6">
<div id="map" class="map"></div>
</div>
			</div>

</body>

<script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker({
          format : 'DD/MM/YYYY HH:mm:00'
        });
    });</script>
 
      <script>
          // Initialize and add the map
          function initMap() {
              var locations = [
                ['NYU Tandon', 40.694332 ,-73.9875867 , 4],
                ['SOHO', 40.7236447 , -74.0050567, 5],
                ['Jackson Heights', 40.7592893 , -73.9015709, 3],
                ['Exchange Place',40.716104,-74.0418455 , 2],
                ['AMC ',40.7567652,-73.991431 , 1]
              ];
              var map = new google.maps.Map(document.getElementById('map'), {
                
                zoom: 11,
                center: new google.maps.LatLng(40.707908, -74.0678935),
                mapTypeId: google.maps.MapTypeId.ROADMAP
              });
          
              var infowindow = new google.maps.InfoWindow();
          
              var marker, i;
          
              for (i = 0; i < locations.length; i++) {  
                marker = new google.maps.Marker({
                  position: new google.maps.LatLng(locations[i][1], locations[i][2]),
                  map: map
                });
          
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                  return function() {
                    infowindow.setContent(locations[i][0]);
                    infowindow.open(map, marker);
                  }
                })(marker, i));
              };
              google.maps.event.addListener(map, 'click', function(event) {
                  document.getElementById('lat').value = event.latLng.lat();
                  document.getElementById('lon').value = event.latLng.lng();
                  });
          
          }
             </script>
              <script async defer
              src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjv31oPC5sbnpCRPpKV6T7lisGJdSkHno&callback=initMap">
              </script>
  
</html>