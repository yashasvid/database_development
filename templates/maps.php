<!DOCTYPE html>
<html>
  <head lang="en">
      <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
      <title>Oingo</title>
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
  
      <div id="wrapper"  >
        <div class="row">
          <div class="col-md-6" style="padding-left: 100px;">
            <form action="" method="post" style="width: 100%;">

                 <div class="form-group">
                     <label class="control-label">User Current Location</label>
                
        
                        <div id="location" alignment="center">
                              Latitude of Location Selected: <input type="text" name="lat" id="lat"><br>
                              Longitude of Location selected: <input type="text" name="lon" id="lon"><br>
                         </div>
                 </div>
             

                <div class="form-group">
                    <label class="control-label"> User Current Date and Time</label>
                    <div class='input-group date' id='datetimepicker1'>
                       <input type='text' class="form-control" />
                       <span class="input-group-addon">
                       <span class="glyphicon glyphicon-calendar"></span>
                    
                    </div>
                 </div>
                 <div class="form-group">
                     <label class="control-label">User Current Location</label>
                      State   <input type='text'  name="state" id="state" />
                </div>
                <div class="input-group">
  		        <button type="submit" class="btn" name="current_param">Submit</button>
                    </div>
            </form>
                

        
        </div>
            <!--The div element for the map -->
        <div class="col-md-6">

          <div id="map" class="map"></div>
        </div>
        </div>
      </div>
      
  </body>
  <script type="text/javascript">
    $(function () {
        $('#datetimepicker1').datetimepicker({
          format : 'DD/MM/YYYY HH:mm'
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
                 // alert(event.latLng.lat() + ", " + event.latLng.lng());
                  document.getElementById('lat').value = event.latLng.lat();
                  document.getElementById('lon').value = event.latLng.lng();
                  });
          
          }
             </script>
              <script async defer
              src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBjv31oPC5sbnpCRPpKV6T7lisGJdSkHno&callback=initMap">
              </script>
  
</html>