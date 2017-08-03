<?php
include_once '../include/db_connect.php';

$conn=getConn();
if(!session_id()){
    session_start();
}
 $admin_name = $_SESSION['admin_name'];
 if($admin_name==''){
	
   echo "<p class='error'>Please <a href='index.php'>login</a> to access this page.</p>";
}
else{
?>
<html>
<title>View Details</title>
<head>
<link href="../css/bootstrap.min.css" rel="stylesheet">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</head>
<body>
<style> 
.success{color:green;}
.error{color:red;}
</style>
<div class="container">

<?php
 $row_count='';
 echo "<p>Hello ".ucfirst($admin_name).",</p><br/>";
  echo "<table width='80%' border=1>";
 
  $result1 = mysqli_query($conn,"SELECT * FROM ".tbl_ce_users." ORDER BY id DESC");
  $color1 = "#F7EAEA";
	$color2 = "#D2E9F7";
    if(mysqli_num_rows($result1) > 0)
  {
		echo "<table width='80%' border='0' class='sortable'>";
		echo "<tr bgcolor='#F2B168'>";
		echo "<td width='10%'>Sr No.</td>";
		echo "<td width='10%'>User Name</td>";
		echo "<td width='10%'>Submitted Date</td>";
		echo "</tr>";
		$i=1;
		while($res=mysqli_fetch_array($result1)){

			$row_color = ($row_count % 2) ? $color1 : $color2; 
			$fpid 	= $res['id'];
			$uname	= $res['name'];
			
			echo "<tr bgcolor='".$row_color."' style='font:normal 14px Arial; color:#000;'>";
			echo "<td width='10%' class='blogText'>".$i."</td>";
			echo "<td width='10%'><a href=\"full-profile.php?id=$fpid\">".$uname."</a></td>";
			echo "<td width='10%'>".$res['sub_date']."</td>";
				
			echo "</tr>";
			$row_count++;
			$i++;
		}
		echo "</table>";
  }?>
		

</div>
</body>
</html>
<?php }
?>