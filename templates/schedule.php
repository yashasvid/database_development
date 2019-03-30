<html>
<body>

<form method="post" action="">
<input type="date" name="date">
<select name="repetition">
<option value="No"> Does not repeat</option>
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
<input type="time"  name="starttime"  min="00:00" max="23:59" required>
 to
<input type="time"  name="endtime"  min="00:00" max="23:59" required>
<br><br>
<input type="submit" value="Submit">
</form>

</body>
</html>

