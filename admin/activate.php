<?php
include_once '../include/db_connect.php';

$conn=getConn();
date_default_timezone_set('Asia/Calcutta');
$reg_date= date("Y-m-d H:i:s");

$uc = $_GET['uc'];
$email = $_GET['email'];
	$author_query = mysqli_query($conn,"SELECT admin_name,auth,admin_email FROM ".tbl_admin_details." WHERE unique_code='$uc' and admin_email='$email'");
       	 
       	if(mysqli_num_rows($author_query) > 0)
		{
			$rowid    = mysqli_fetch_array($author_query);
            $email    = $rowid['admin_email'];
			$admin_name    = $rowid['admin_name'];
            $auth     = $rowid['auth'];
			
			if($auth == 'Y')
             {
            	  $frm_msg="already_activated";
             }
			 else
				{
					$author   = mysqli_query($conn,"UPDATE ".tbl_admin_details." SET auth='Y' WHERE unique_code='$uc' and admin_email='$email' LIMIT 1");
			
					if(mysqli_affected_rows($conn) > 0)
					{
						$mailbody  =   "<p><b>Hello ".ucwords($admin_name)"</b><br /><br />";
						$mailbody  .=   "Congratulations! You are now an admin at CE! Please <a href='index.php'>Sign In</a> to explore.<br /><br />";
						$mailbody     .= "<p>Thanks,<br /> Best Regards,<br /> Team @ <a href='#'>CE</a><br />";
						$subject    =   "Account activated!!";

						$to            = $email;
						$fromemail     = "ce@xyz.com";
						$msg           = $mailbody;

						$headers       = "MIME-Version: 1.0" . "\r\n";
						$headers      .= "Content-type: text/html; charset=iso-8859-1" . "\r\n";
						$headers 	  .= "From: ".$fromemail ."\r\n";
						$headers;

						if(mail($to, $subject, $msg, $headers))
						{
							$frm_msg='success';
						}
						else
						{
							$frm_msg='failed';
						}
						else
						{
							 $frm_msg='failed';
						}
			 }
			 
		}
		
?>
<html>
<head>
<title>Activation</title>
<head>
<body>
<?php if($frm_msg=='success') 
	{
		echo "<div class='success'>Congratulations! You are now an admin at CE! Please <a href='index.php'>Sign In</a> to explore.</div>";
	}
	elseif($frm_msg=='failed';)
	{
		echo "<div class='error'>There seems to be some problem in your account activation or you have clicked on a wrong link. Please write to <a href='mailto:ce@xyz.com'>ce@xyz.com</a> with your details.</div>";
	}
	
	?>
</body>
</html>