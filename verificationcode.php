<?php
if(!isset($_SESSION))//check session start or not
{
	session_start();//session start
}
if(isset($_SESSION["verifyforgetusername"]))//only allow when username save in session as verifyforgetusername
{
	$verifyforgetusername=$_SESSION["verifyforgetusername"];//get username from session
include("config.php");
date_default_timezone_set("Asia/Colombo");
if(isset($_POST["btnverificationcode"]))//verify button click or not
{
	$username=$_POST["txtemail"];//get username
	$verificationcode=$_POST["txtverificationcode"];//get enter verification code
	$sqlusername="SELECT * FROM login WHERE user_id='$username'";
	$resultusername=mysql_query($sqlusername)or die("sql error in sqlusername ".mysql_error());
	if(mysql_num_rows($resultusername)>0)
	{
		$rowusername=mysql_fetch_assoc($resultusername);
		if($rowusername["code"]==$verificationcode)//check enter code and db code are equal or not
		{
			unset($_SESSION["verifyforgetusername"]);
			$_SESSION["newpasswordforgetusername"]=$username;//save username in session
			echo "<script>alert('Now You can change your password');
			window.location.href='index.php?page=newpassword.php';
			</script>";
		}
		else// if not re-direct to forgetpassword page
		{
			unset($_SESSION["verifyforgetusername"]);
			echo "<script>alert('Your verification code is Wrong');
			window.location.href='index.php?page=forgetpassword.php';</script>";
		}
	}
	else
	{
		echo "<script>alert('There is no Username');</script>";
	}
}
?>
	<div class="login">
	
		<div class="main-agileits">
				<div class="form-w3agile">
					<h3>Login</h3>
					<form action="#" method="post">
						<div class="key">
							<i class="fa fa-envelope" aria-hidden="true"></i>
							<input  type="text"  name="txtemail" id="txtemail" readonly value="<?php echo $verifyforgetusername; ?>" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}" required="">
							<div class="clearfix"></div>
						</div>
						<div class="key">
							<i class="fa fa-lock" aria-hidden="true"></i>
							<input type="number" id="txtverificationcode" placeholder="Enter Verification code..."  required name="txtverificationcode">
							<div class="clearfix"></div>
						</div>
						<input type="submit" id="btnverificationcode" name="btnverificationcode" value="Verify">
					</form>
				</div>
				<div class="forg">
					<a href="index.php?page=forgetpassword.php" class="forg-left">Forgot Password</a>
				<div class="clearfix"></div>
				</div>
			</div>
		</div>
<?php
}
else//others goto forgetpassword page
{
	echo"<script>window.location.href='forgetpassword.php';</script>";
}
?>