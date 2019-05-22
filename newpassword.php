<?php
if(!isset($_SESSION))//check session start or not
{
	session_start();//session start
}
if(isset($_SESSION["newpasswordforgetusername"]))//only allow when username save in session as newpasswordforgetusername
{
	$newpasswordforgetusername=$_SESSION["newpasswordforgetusername"];//get newpasswordforgetusername from session
include("config.php");//include connection page
date_default_timezone_set("Asia/Colombo");
if(isset($_POST["btnnewpassword"]))//check if change password button click no not
{
	$username=$_POST["txtusername"];//get enter username
	$password=$_POST["txtpassword"];//get enter password
	$repassword=$_POST["txtrepassword"];//get enter re-enter password
	$sqlusername="SELECT * FROM login WHERE user_id='$username'";
	$resultusername=mysql_query($sqlusername)or die("sql error in sqlusername ".mysql_error());
	if(mysql_num_rows($resultusername)>0)//already exist username
	{
		$rowusername=mysql_fetch_assoc($resultusername);
		if($password==$repassword)//check enter and re-enter password are match or not
		{
			$password=md5($password);
			$sqlupdate="UPDATE login SET password='$password' WHERE user_id='$username'";//update new password
			$resultupdate=mysql_query($sqlupdate)or die("sql error in sqlupdate ".mysql_error());
			
			unset($_SESSION["newpasswordforgetusername"]);
			echo "<script>alert('Now Your Password is Changed');
			window.location.href='index.php?page=login.php';
			</script>";// re-direct to login page
		}
		else//if not re-direct to login page
		{
			unset($_SESSION["newpasswordforgetusername"]);
			echo "<script>alert('Your Password And Re-Enter Password Are Missed Match');
			window.location.href='index.php?=page=forgetpassword.php';</script>";
		}
	}
	else
	{
		echo "<script>alert('There is no Username');</script>";
	}
}
?>
<script>
		function checkpassword()
		{
			var enterpassword=document.getElementById("txtpassword").value;
			var enterrepassword=document.getElementById("txtrepassword").value;
			if(enterpassword==enterrepassword)
			{
				return true;
			}
			else 
			{
				alert("Your Password Miss Match");
				document.getElementById("txtpassword").value="";
				document.getElementById("txtrepassword").value="";
				return false;
			}
		}
		</script>
	<div class="login">
	
		<div class="main-agileits">
				<div class="form-w3agile">
					<h3>Login</h3>
					<form action="#" method="post" onsubmit="return checkpassword()">
						<div class="key">
							<i class="fa fa-envelope" aria-hidden="true"></i>
							<input  type="text" id="txtusername" readonly value="<?php echo $newpasswordforgetusername; ?>" required name="txtusername" onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}" required="">
							<div class="clearfix"></div>
						</div>
						<div class="key">
							<i class="fa fa-lock" aria-hidden="true"></i>
							<input  type="password" id="txtpassword" required name="txtpassword" placeholder="Enter Password..."  onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}" required="">
							<div class="clearfix"></div>
						</div>
						<div class="key">
							<i class="fa fa-lock" aria-hidden="true"></i>
							<input  type="password" id="txtrepassword" required name="txtrepassword" placeholder="Enter Re-Password..."  onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}" required="">
							<div class="clearfix"></div>
						</div>
						<input type="submit" id="btnnewpassword" name="btnnewpassword" value="Change Password">
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