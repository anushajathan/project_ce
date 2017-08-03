<?php
include_once 'include/db_connect.php';
$conn=getConn();

$uname  = $unameErr = $email = $emailErr = $web_addr = $web_addrErr =$like_work = $like_workErr = $cover_letter= $cover_letterErr = $attachment = $attachmentErr=$frm_msg="";
date_default_timezone_set('Asia/Calcutta');

	$sub_date= date("Y-m-d H:i:s");
	$ip_addr = $_SERVER['REMOTE_ADDR'];
	$browser_info=$_SERVER['HTTP_USER_AGENT'];
	$validate=1;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["uname"])) {
    $unameErr = "Name is required";
	$validate=0;
  } else {
    $uname = trim($_POST["uname"]);
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z ]*$/",$uname)) {
      $unameErr = "Only letters and white space allowed";
	  $validate=0;
    }
  }

  if (empty($_POST["email"])) {
    $emailErr = "Email is required";
	$validate=0;
  } else {
    $email = trim($_POST["email"]);
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email id format";
	  $validate=0;
    }
  }

  if (empty($_POST["web_addr"])) {
    $web_addrErr = "Enter you Web URL";
	$validate=0;
  } else {
    $web_addr = trim($_POST["web_addr"]);
    // check if URL address syntax is valid (this regular expression also allows dashes in the URL)
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$web_addr)) {
      $web_addrErr = "Invalid Web URL";
	  $validate=0;
    }
  }
  
  if (empty($_POST["like_work"])) {
    $like_workErr = "Do you like to work?";
	$validate=0;
  } else {
    $like_work = $_POST["like_work"];
  }
  
   if (empty($_POST["cover_letter"])) {
    $cover_letterErr = "Share cover letter";
	$validate=0;
  } else {
    $cover_letter = addslashes($_POST["cover_letter"]);
  }
	
    $fileName=$_FILES["attachment"]["name"];
	$fileSize=$_FILES["attachment"]["size"]/1024;
	$fileType=$_FILES["attachment"]["type"];
	$fileTmpName=$_FILES["attachment"]["tmp_name"];  

	if(($fileType=="application/msword") || ($fileType=="application/pdf")){
		if($fileSize<=2000){
		 
		//New file name
		$random=rand(1111,9999);
		$newFileName=$random.$fileName;

		//File upload path
		$uploadPath="testUpload/".$newFileName;

		//function for upload file
		if(move_uploaded_file($fileTmpName,$uploadPath))
			{
			$validate=1;
			}
		}
		else{
		 $attachmentErr= "Maximum upload file size limit is 2000 kb";
		  $validate=0;
		}
	}
	else{
	  $attachmentErr =  "You can only upload a PDF file.";
	  $validate=0;
	}  
	
	if($validate==1)
	{
		$result_exists = mysqli_query($conn,"SELECT * FROM ".tbl_ce_users." where email='$email'");
		if(mysqli_num_rows($result_exists) > 0)
		{
			$frm_msg = "already_exists";
		}
		else
		{	
			$uploadPath = "../".$uploadPath;
			$message = mysqli_query($conn,"INSERT INTO ".tbl_ce_users." (name,email,web_addr,cover_letter,attachment,like_work,sub_date,ip_addr,browser) VALUES ('$uname','$email','$web_addr','$cover_letter','$uploadPath','$like_work','$sub_date','$ip_addr','$browser_info')");
			if(mysqli_affected_rows($conn) > 0)
			{
				$mailbody      =  "Dear ".ucwords($uname).",<br /><br />";
				$mailbody     .=  "Thank you for registering with us. We shall get back to you shortly.<br /><br />";
				$mailbody     .= "<p>Thanks,<br /> Best Regards,<br /> Team @ <a href='#'>CE</a><br />";
				$subject       =  "Registration Successful!!";
				$to = $email;
				$fromemail     =  "CE<ce@xyz.com>";
				$msg           =  $mailbody;
				$headers     =  "MIME-Version: 1.0" . "\r\n";
				$headers    .=  "Content-type: text/html; charset=iso-8859-1" . "\r\n";
				$headers      .= "From: ".$fromemail ."\r\n";
				$headers; 
				if(mail($to, $subject, $mailbody, $headers))
				{
					$frm_msg ="success";
				}
				else
				{
					$frm_msg ="failed";
				}
			}
			else
				{
					$frm_msg ="failed";
				}
			
		}
	}
  }
	
	
  
?>    
<html>
<head>
<title> Registration Form</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>	
<style> 
.success{color:green;}
.error{color:red;}
</style>
 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<div class="container">
	<?php
	if($frm_msg =="success")
	{
		echo "<h4 class='success' style='text-align:center;'>Thank you for registering with us. We shall get back to you shortly.</h4>"; ?>
		<div>
		<table class="table table-condensed">
			
			<tr>
				<td>Name</td>
				<td><?php echo ucfirst($uname); ?></td>
			  </tr>
			  <tr>
				<td>Email</td>
				<td><?php echo $email; ?></td>
			  </tr>
			  <tr>
				<td>Website</td>
				<td><?php echo  $web_addr; ?></td>
			  </tr>
			  <tr>
				<td>Cover Letter</td>
				<td><?php echo nl2br(stripslashes($cover_letter)); ?></td>
			  </tr>
			  <tr>
				<td>Resume</td>
				<td><a href='<?php echo $uploadPath; ?>' target='_blank'>View</a></td>
			  </tr>
			  <tr>
				<td>Submitted Date</td>
				<td><?php echo $sub_date; ?></td>
			  </tr>
			
		  </table>
		</div>
<?	}
	elseif($frm_msg =="failed")
	{
		echo "<h4 class='error' style='text-align:center;'>Registration not done!! Try again.</h4>";
	}
	elseif($frm_msg =="already_exists"){
		echo "<h4 class='error' style='text-align:center;'>You are already a registered member.</h4>";
	}
	else{
	?>
    <form class="well form-horizontal" action="index.php" method="post"  id="contact_form" enctype="multipart/form-data">
	<fieldset>

	<legend><center><h2><b>Registration Form</b></h2></center></legend><br>
		 <center><h3><b>Note : All fields are mandatory</b></h3></center><br>
	

	<div class="form-group">
	  <label class="col-md-4 control-label">Name</label>  
	  <div class="col-md-4 inputGroupContainer">
	  <div class="input-group">
	  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
	  <input  name="uname" placeholder="Name" class="form-control"  type="text" required>
	  <span class="error"> <?php echo $unameErr;?></span>
		</div>
	  </div>
	</div>

	<div class="form-group">
	  <label class="col-md-4 control-label">E-Mail</label>  
		<div class="col-md-4 inputGroupContainer">
		<div class="input-group">
			<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
	  <input name="email" placeholder="E-Mail Address" class="form-control"  type="text" required>
	  <span class="error"><?php echo $emailErr;?></span>
		</div>
	  </div>
	</div>
	
	<div class="form-group">
	  <label class="col-md-4 control-label">Web Address</label>  
	  <div class="col-md-4 inputGroupContainer">
	  <div class="input-group">
	  <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
	  <input  name="web_addr" placeholder="Website" class="form-control"  type="text" required>
	  <span class="error"> <?php echo $web_addrErr;?></span>
		</div>
	  </div>
	</div>
	
	<div class="form-group">
      <label class="col-md-4 control-label">Do you like working?</label>
	  <div class="col-md-4 selectContainer">
		<div class="input-group">
		<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
		<input type="radio" name="like_work" value='Yes' checked required>Yes
		<input type="radio" name="like_work" value='No'>No
		<span class="error"> <?php echo $like_workErr;?></span>
	   </div>
	   </div>
    </div>
   	
	<div class="form-group">
      <label class="col-md-4 control-label">Cover Letter</label>
	  <div class="col-md-4 selectContainer">
		<div class="input-group">
		<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
		<textarea name="cover_letter" rows="5" cols="40" required><?php echo $cover_letter; ?></textarea>
		<span class="error"> <?php echo $cover_letterErr;?></span>
	   </div>
	   </div>
    </div>
	
		<div class="form-group">
	  <label class="col-md-4 control-label" >Attachment</label> 
		<div class="col-md-4 selectContainer">
		<div class="input-group">
		 <input name="attachment" placeholder="Attachment" type="file">
		 <span class="error"><?php echo $attachmentErr;?></span>
		</div>
		</div>
	</div>
	
	<div class="form-group">
	  <label class="col-md-4 control-label"></label>
	  <div class="col-md-4"><br>
		<button type="submit" class="btn btn-warning" >SUBMIT <span class="glyphicon glyphicon-send"></span></button>
	  </div>
	</div>

	</fieldset>
	</form>
	<?php } ?>
    </div><!-- /.container -->
	
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	</body>
	</html>