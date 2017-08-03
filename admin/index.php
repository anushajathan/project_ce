<?php
include_once '../include/db_connect.php';

$conn=getConn();
if(!session_id()){
    session_start();
}
$admin_name = $_SESSION['admin_name'];
$admin_emailErr=$admin_email='';
date_default_timezone_set('Asia/Calcutta');
	$reg_date= date("Y-m-d H:i:s");
	$validate=1;
	 
	 if ($_SERVER["REQUEST_METHOD"] == "POST") {
		 
		if (empty($_POST["login"])) {
			$admin_email=$_POST['admin_email'];
			if (empty($admin_email) || ($admin_email=='')) {
			$admin_emailErr = "Email is required";
			$validate=0;
		  } else {
			$admin_email = trim($_POST["admin_email"]);
			// check if e-mail address is well-formed
			if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) {
			  $admin_emailErr = "Invalid email id format";
			  $validate=0;
			}
		  }
			if($validate==1)
			{
				$result_exists = mysqli_query($conn,"SELECT * FROM ".tbl_admin_details." where admin_email='$admin_email'");
								
				if(mysqli_num_rows($result_exists) > 0)
				{
					$admin_row=mysqli_fetch_array($result_exists);
					$auth= $admin_row['auth'];
					if($auth=='Y'){
						$author   = mysqli_query($conn,"UPDATE ".tbl_admin_details." SET current_login='$reg_date' WHERE admin_email='$admin_email' LIMIT 1");
			
						$_SESSION['admin_name'] = $admin_row['admin_name'];
						header("Location: ce-display.php");
					}
					elseif($auth=='N')
					{
						$frm_msg ="not_authorized";
					}
				}
				else
				{
					 $frm_msg ="does_not_exist";
				}
			}
			
		}
	 }
?>    
<html>
<head>
<title> Login Form</title>

</head>
<body>	
<style> 
.success{color:green;}
.error{color:red;}
</style>
<link href="../css/bootstrap.min.css" rel="stylesheet">
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<div class="container">
	<?php
	if($frm_msg =="does_not_exist"){
		echo "<p class='error'>The email ( <b>$admin_email</b> ) is not registered with us as admin. Please <a href='sign-up.php'>sign up</a> to continue.</p>";
	}
	elseif($frm_msg =="not_authorized"){
		echo "<p class='error'>The email ( <b>$admin_email</b> ) is registered with us but have not activated the account. Check your email or contact us at <a href='mailto:ce@xyz.com'> ce@xyz.com</a> for help.</p>";
	}
	else{
	?>
	<form class="well form-horizontal" action="index.php" method="post"  id="login_form">
	<fieldset>
		<p style="text-align:center;">Not registered yet?? <a href='sign-up.php'>Sign up</a> now!</p>
	<!-- Form Name -->
	<legend><center><h2><b>Login Form</b></h2></center></legend><br>
	
	<!-- Text input-->
		   <div class="form-group">
	  <label class="col-md-4 control-label">E-Mail</label>  
		<div class="col-md-4 inputGroupContainer">
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
	  <input name="admin_email" placeholder="E-Mail Address" class="form-control"  type="text">
	  <span class="error"> <?php echo $admin_emailErr;?></span>
		</div>
	  </div>
	</div>
	
	<div class="form-group">
	  <label class="col-md-4 control-label"></label>
	  <div class="col-md-4"><br>
		<button type="submit" class="btn btn-warning" value='login'>Login <span class="glyphicon glyphicon-send"></span></button>
		</div>
	</div>
	
	</fieldset>
	</form>
	<?php } ?>
    </div><!-- /.container -->
	
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
	
	</body>
	</html>