<?php
include_once '../include/db_connect.php';

$conn=getConn();
$admin_name=$admin_nameErr= $admin_email = $admin_emailErr= $msg="";
	date_default_timezone_set('Asia/Calcutta');
	$reg_date= date("Y-m-d H:i:s");
	$ip_addr = $_SERVER['REMOTE_ADDR'];
	$browser_info=$_SERVER['HTTP_USER_AGENT'];
	 
	if($_SERVER['REQUEST_METHOD'] == "POST")
	{
		
		if (empty($_POST["register"])) {
		
		$validate=1;
		$admin_name=$_POST['admin_name'];
		if (empty($admin_name) || ($admin_name=='')) {
			$admin_nameErr = "Name is required";
			$validate=0;
		} 
		else {
			$admin_name = trim($_POST["admin_name"]);
			// check if name only contains letters and whitespace
			if (!preg_match("/^[a-zA-Z ]*$/",$admin_name)) 
			{
				$admin_nameErr = "Only letters and white space allowed";
				$validate=0;
			}
		}	
		
		$admin_email=$_POST['admin_email'];
		if (empty($admin_email) || ($admin_email=='')) {
			$admin_emailErr = "Email is required";
			$validate=0;
		} 
		else {
			$admin_email = trim($_POST["admin_email"]);
			// check if e-mail address is well-formed
			if (!filter_var($admin_email, FILTER_VALIDATE_EMAIL)) 
			{
				$admin_emailErr = "Invalid email id format";
				$validate=0;
			}
		}
	if($validate==1)
	{
		$result_exists = mysqli_query($conn,"SELECT * FROM ".tbl_admin_details." where admin_email='$admin_email'");
		if(mysqli_num_rows($result_exists) > 0)
		{
			$frm_msg = "already_exists";
		}
		else{
						
				$unique_code = md5($reg_date);
				$result_ins = mysqli_query($conn,"INSERT INTO ".tbl_admin_details." (admin_name,admin_email,reg_date,unique_code,auth,ip_addr,browser) VALUES ('$admin_name','$admin_email','$reg_date','$unique_code','N','$ip_addr','$browser_info')");
				if(mysqli_affected_rows($conn) > 0)
				{
					$mailbody  =   "<p><b>Hello ".ucwords($admin_name)."</b>,<br /><br />";
					$mailbody     .=  "Thank you for registering with us. We shall get back to you shortly.<br /><br />";
					$mailbody     .= "Click on the following link to confirm and activate your registration. <br /><br />";
					$mailbody     .= "<b><a style='font-size:16px;' href='http://yourdomain/admin/activate.php?uc=$unique_code&&email=$admin_email' title='Activate My account'>Activate My account</a></b><br /><br />";
					$mailbody     .= "<p>Thanks,<br /> Best Regards,<br /> Team @ <a href='#'>CE</a><br />";
					$subject    =   "Activate your account at CE";
					$to = $admin_email;
					$fromemail     =  "CE<ce@xyz.com>";
					$msg           =  $mailbody;
					$headers     =  "MIME-Version: 1.0" . "\r\n";
					$headers    .=  "Content-type: text/html; charset=iso-8859-1" . "\r\n";
					$headers      .= "From: ".$fromemail ."\r\n";
					$headers; 
					
				if(mail($to, $subject, $mailbody, $headers))
				{ echo $mailbody;
					$frm_msg ="success";
				}
				else
				{
					$frm_msg ="failed";
				}
			}
			else
			{
				$frm_msg = "failed";
			}
				
			}
			
			
		  }
		}
	}
	
?>
<html>
<head>
<title> Sign up Form</title>
<link href="../css/bootstrap.min.css" rel="stylesheet">
</head>
<body>	
<style> 
.success{color:green;}
.error{color:red;}
</style>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<div class="container">
	<?php
	
	if($frm_msg == "success")
	{
		echo "<p class='success' style='text-align:center;'>Check your email to activate your admin account.</p>";
	}
	elseif($frm_msg == "failed")
	{
		echo "<p class='error' style='text-align:center;'>Sign up unsuccessful. Try again later.</p>";
	}
	elseif($frm_msg == "already_exists")
	{
		echo "<p class='error' style='text-align:center;'>The admin <b>$admin_name</b> already exists. Please <a href='index.php'>login</a> to continue.</p>";
	}else{
	?>
	<form class="well form-horizontal" action="sign-up.php" method="post" id="signup_form">
	<fieldset>

	<!-- Form Name -->
	<legend><center><h2><b>Sign Up Form</b></h2></center></legend><br>
	<!-- Text input-->
	<div class="form-group">
	  <label class="col-md-4 control-label">Name</label>  
	  <div class="col-md-4 inputGroupContainer">
	  <div class="input-group">
	  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	  <input  name="admin_name" placeholder="Name" class="form-control"  type="text">
	  <span class="error"> <?php echo $admin_nameErr;?></span>
		</div>
	  </div>
	</div>

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
	

	<!-- Button -->
	<div class="form-group">
	  <label class="col-md-4 control-label"></label>
	  <div class="col-md-4"><br>
		<button type="submit" class="btn btn-warning" value='register'>Sign Up <span class="glyphicon glyphicon-send"></span></button>
	  </div>
	</div>

	</fieldset>
	</form>
	<?php } ?>
	 </div><!-- /.container -->
	
    <script type="text/javascript" src="../js/bootstrap.min.js"></script>
	
	</body>
	</html>