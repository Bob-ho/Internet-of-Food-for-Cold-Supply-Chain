<?php
if(!isset($_SESSION))//check session start or not
{
	session_start();//session start
}
if(isset($_SESSION["usertype"]))//check usertype is save in session or not (if only save after successfully login)
{
	$usertype=$_SESSION["usertype"];//get user type from session
}
else
{
	$usertype="guest";//if not default user type is guest
}
if($usertype=="guest")//only allow for guest
{
include("config.php");//connection page include
date_default_timezone_set("Asia/Colombo");
if(isset($_POST["btnforgetpassword"]))//recover button click or not
{
	$username=$_POST["txtemail"];//get enter username
	$mobile=$_POST["txtmobile"];//get enter mobile
	$sqlusername="SELECT * FROM login WHERE user_id='$username'";//sql with username
	$resultusername=mysql_query($sqlusername)or die("sql error in sqlusername ".mysql_error());
	if(mysql_num_rows($resultusername)>0)//check username exist ot not
	{
		$rowusername=mysql_fetch_assoc($resultusername);

		if($rowusername["usertype"]=="customer")
		{//for who are customer in this system, get mobile number from customer table
			$sqlmobileno="SELECT mobile FROM customer WHERE customer_id='$username'";	
		}
		else if($rowusername["usertype"]=="retailer")
		{//for who are retailer in this system, get mobile number from retailer table
			$sqlmobileno="SELECT mobile FROM retailer WHERE retailer_id='$username'";	
		}
		else 
		{//for who are staff in this system, get mobile number from parent table
			$sqlmobileno="SELECT mobile FROM staff WHERE staff_id='$username'";	
		}
		$resultmobileno=mysql_query($sqlmobileno)or die("sql error in sqlmobileno ".mysql_error());
		$rowmobileno=mysql_fetch_assoc($resultmobileno);
		if($rowmobileno["mobile"]==$mobile)//check database mobile and user's enter mobile are equal or not
		{
			$verificationcode=rand();//create verification code using php rand function
			$sqlupdate="UPDATE login SET code='$verificationcode' WHERE user_id='$username'";//update that code in table
			$resultupdate=mysql_query($sqlupdate)or die("sql error in sqlupdate ".mysql_error());
			
			if(isset($_SESSION["forgetusername"]))
			{
				unset($_SESSION["forgetusername"]);
			}
			$_SESSION["verifyforgetusername"]=$username;
			echo "<script>alert('We send code to your mobile, please check it');
			window.location.href='index.php?page=verificationcode.php';
			</script>";//re-direct to verificationcode page
		}
		else//error when enter wrong mobile number
		{
			echo "<script>alert('Your mobile No is Wrong');</script>";
		}
	}
	else//for not register username
	{
		echo "<script>alert('There is no Username');</script>";
	}
}
if(isset($_SESSION["forgetusername"]))//if user re-direct from login page, check session forgetusername
{
	$forgetusername=$_SESSION["forgetusername"];//get forgetusername from session
}
else// if user come directly click forget password link
{
	$forgetusername="";
}
?>
	<div class="login">
	
		<div class="main-agileits">
				<div class="form-w3agile">
					<h3>Forget Password</h3>
					<form action="#" method="POST">
						<div class="key">
							<i class="fa fa-envelope" aria-hidden="true"></i>
							<input  type="text" name="txtemail" id="txtemail" placeholder="Enter E-Mail..."  onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}" required="">
							<div class="clearfix"></div>
						</div>
						<div class="key">
							<i class="fa fa-lock" aria-hidden="true"></i>
							<input  type="text" name="txtmobile" id="txtmobile" placeholder="Enter Mobile..."   required="">
							<div class="clearfix"></div>
						</div>
						<input type="submit" value="Recover" name="btnforgetpassword" id="btnforgetpassword">
					</form>
				</div>
				<div class="forg">
					<a href="index.php?page=login.php" class="forg-right">Back to Login</a>
				<div class="clearfix"></div>
				</div>
			</div>
		</div>
<?php 
}
else//others goto index page
{
	echo '<script>window.location.href="index.php";</script>';	
}
?>