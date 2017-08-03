<?php
include_once '../include/db_connect.php';

$conn=getConn();
if(!session_id()){
    session_start();
}
 $admin_name = $_SESSION['admin_name'];
 $fpid = $_GET['id'];
 $query = mysqli_query($conn,"SELECT rating_number, FORMAT((total_points / rating_number),1) as average_rating FROM post_rating WHERE user_id = '$fpid' AND status = 1");
 $ratingRow=mysqli_fetch_array($query);
 
 if($admin_name==''){
	
   echo "<p class='errorMessage'>Please <a href='index.php'>login</a> to access this page.</p>";
}
else{
	?>
	<html>
	<title>View Details</title>
	<head>
	<link href="../css/rating.css" rel="stylesheet" type="text/css">
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
	<div class="container">
	<script type="text/javascript" src="../js/rating.js"></script>
	<script language="javascript" type="text/javascript">
	$(function() {
		$("#rating_star").rating_widget({
			starLength: '5',
			initialValue: '',
			callbackFunctionName: 'processRating',
			imageDirectory: '../images/',
			inputAttr: 'user_id'
		});
	});

	function processRating(val, attrVal){
		
		$.ajax({
			type: 'POST',
			url: 'rating.php',
			data: 'user_id='+attrVal+'&ratingPoints='+val,
			dataType: 'json',
			success : function(data) {
				alert(data);
				if (data.status == 'ok') {
					alert('You have rated '+val+' to CodexWorld');
					$('#avgrat').text(data.average_rating);
					$('#totalrat').text(data.rating_number);
				}else{
					alert('Some problem occured, please try again.');
				}
			}
		});
	}
	</script>
	<style type="text/css">
		.overall-rating{font-size: 14px;margin-top: 5px;color: #8e8d8d;}
	</style>
	<?php
	
	echo "<p>Hello ".ucfirst($admin_name).",</p><br/>"; 
		
	echo "<div class='table-responsive'>";
	echo "<table width='90%' class='table table-bordered'>";
	$result=mysqli_query($conn,"SELECT * FROM ".tbl_ce_users." WHERE id=$fpid");
	if(mysqli_num_rows($result) > 0)
	  {	
		$res=mysqli_fetch_array($result);
		$name 			= $res['name'];
		$email 			= $res['email'];
		$web_addr 		= $res['web_addr'];
		$cover_letter 	= $res['cover_letter'];
		$like_work 		= $res['like_work'];
		$sub_date 		= $res['sub_date'];
		$attachment 	= $res['attachment'];
		 
		echo "<tr class='info'><td width='15%'>Name</td><td>".ucfirst($name)."</td></tr>";
		echo "<tr class='danger'><td width='15%'>Email</td><td>".$email."</td></tr>";
		echo "<tr class='info'><td width='15%'>Web Address</td><td>".$web_addr."</td></tr>";
		echo "<tr class='danger'><td width='15%'>Cover Letter</td><td>".nl2br(stripslashes($cover_letter))."</td></tr>";
		echo "<tr class='info'><td width='15%'>Like to work</td><td>".$like_work."</td></tr>";
		echo "<tr class='danger'><td width='15%'>Submitted Date</td><td>".$sub_date."</td></tr>";
		echo "<tr class='danger'><td width='15%'>Rate</td><td>";
		?>
		<input name="rating" value="0" id="rating_star" type="hidden" user_id="<?php echo $fpid; ?>"/>
    <div class="overall-rating">(Average Rating <span id="avgrat"><?php 	echo $ratingRow['average_rating']; ?></span>
		Based on <span id="totalrat"><?php echo $ratingRow['rating_number']; ?></span>  rating)</span>
	</div>
		<?php
		echo "</td></tr>";
		echo "<tr class='danger'><td width='15%'>Resume</td><td><iframe src='$attachment' style='width:600px; height:500px;' frameborder='0'></iframe></td></tr>";
	  }
	  echo "</td></tr>";
	echo "</table></div>";
	?>
	
	</div><!-- /.container -->
	
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
	<!--<script type="text/javascript" src="js/validation.js"></script> -->
	</body>
	</html>
<?php } ?>
    