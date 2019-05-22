<?php
if(!isset($_SESSION)) // check session start or not, if not start the session
{
	session_start(); // session start
}
if(isset($_SESSION["usertype"]))
{
	$usertype=$_SESSION["usertype"];
}
else
{
	$usertype="guest";
}
include("config.php"); //include the server connection page
if(isset($_POST["btnsubmit"]))
{
		$sqlinsert="INSERT INTO customer(customer_id,name,mobile,land,email,address)
					VALUES('".mysql_real_escape_string($_POST["txtusername"])."',
							'".mysql_real_escape_string($_POST["txtf_name"])."',
							'".mysql_real_escape_string($_POST["txtmobile"])."',
							'".mysql_real_escape_string($_POST["txtland"])."',
							'".mysql_real_escape_string($_POST["txtemail"])."',
							'".mysql_real_escape_string($_POST["txtaddress"])."')";
		$resultinsert=mysql_query($sqlinsert) or die("sql error in sqlinsert ".mysql_error());
		
		
	
	$password=rand();
	$password=md5($password);
	$sqlinsertlogin="INSERT INTO login(user_id,usertype,attempt,status,code,password)
			VALUES('".mysql_real_escape_string($_POST["txtusername"])."',
					'".mysql_real_escape_string("customer")."',
					'".mysql_real_escape_string(0)."',
					'".mysql_real_escape_string("Active")."',
					'".mysql_real_escape_string(0)."',
					'".mysql_real_escape_string($password)."')";
	$resultinsertlogin=mysql_query($sqlinsertlogin) or die("sql error in sqlinsertlogin ".mysql_error());
	
	
	if($resultinsert)
	{
		echo '<script>alert("Successfully Register"); 
				window.location.href="index.php?page=login.php"; </script>';		
	}
}
?>
<div class="login">
		<div class="main-agileits">
				<div class="form-w3agile form1">
					<h3>Register</h3>
					<form action="#" method="POST">
						<div class="key">
							<i class="fa fa-user" aria-hidden="true"></i>
							<input  type="text" name="txtusername" id="txtusername" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Username';}" required="">
							<div class="clearfix"></div>
						</div>
						<div class="key">
							<i class="fa fa-user" aria-hidden="true"></i>
							<input  type="text" id="txtf_name" name="txtf_name" onfocus="this.value = '';" required="">
							<div class="clearfix"></div>
						</div>
						<div class="key">
							<i class="fa fa-user" aria-hidden="true"></i>
							<input  type="text" id="txtl_name" name="txtl_name"  required="">
							<div class="clearfix"></div>
						</div>
						
						<div class="key">
							<i class="fa fa-phone" aria-hidden="true"></i>
							<input  type="text" id="txtmobile" name="txtmobile" required="">
							<div class="clearfix"></div>
						</div>
						
						<div class="key">
							<i class="fa fa-phone" aria-hidden="true"></i>
							<input  type="text" id="txtland" name="txtland" required="">
							<div class="clearfix"></div>
						</div>
						
						<div class="key">
							<i class="fa fa-envelope" aria-hidden="true"></i>
							<input  type="email" id="txtemail" name="txtemail"  required="">
							<div class="clearfix"></div>
						</div>
						
						<div class="key">
							<i class="fa fa-envelope" aria-hidden="true"></i>
							<textarea required placeholder="Address" id="txtaddress" maxlength="40"name="txtaddress"></textarea>
							<div class="clearfix"></div>
						</div>
						<input type="submit"  name="btnsubmit" id="btnsubmit" value="Register">
					</form>
				</div>
				
			</div>
		</div>