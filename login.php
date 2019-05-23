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
	include("config.php");//include the connection page 
	if(isset($_POST["btnlogin"]))//login button click or not
	{	//get user name and password from user 
		$username=$_POST["txtemail"];//get enter username
		$password=md5($_POST["txtpassword"]);//get enter password and encrypt the password
		$sqlusername="SELECT * FROM login WHERE user_id='$username'";//sql with username
		$resultusername=mysql_query($sqlusername)or die("sql error in sqlusername ".mysql_error());//run the sql query
		if(mysql_num_rows($resultusername)>0)//only allow register username exist
		{
			$rowusername=mysql_fetch_assoc($resultusername);//convert array format after run the query
			$sqlpassword="SELECT * FROM login WHERE user_id='$username' AND password='$password'";//sql with username and password
			$resultpassword=mysql_query($sqlpassword)or die("sql error in sqlpassword ".mysql_error());
			if(mysql_num_rows($resultpassword)==1)//only allow username and password correct
			{
				if($rowusername["status"]=="Active")//check user is active now or not
				{
					$_SESSION["username"]=$username;//save username in session
					$_SESSION["usertype"]=$rowusername["usertype"];//save usertype in session
					
					$sqlupdate="UPDATE login SET attempt='0' WHERE user_id='$username'";//set attempt to zero
					$resultupdate=mysql_query($sqlupdate)or die("sql error in sqlupdate ".mysql_error());	
					
					if(isset($_SESSION["addtocardorderid"]))
					{
						header("location:index.php?page=checkout.php");
					}
					else
					{
						header("location:index.php");
					}
				}
				else if($rowusername["status"]=="Pending")//for who user account not activate...
				{
					echo "<script>alert('Your account still under procedure, please contact management for more info');
					window.location.href='index.php';</script>";
				}
				else if($rowusername["status"]=="Phone_code")//for who not verify their mobile number
				{
					echo "<script>alert('Your registration not successfully , please try again!!!');
					window.location.href='index.php';</script>";
				}
				else if($rowusername["status"]=="Deleted")//for who account was deleted
				{
					echo "<script>alert('Your account was deleted, please contact management for more info');
					window.location.href='index.php';</script>";
				}
			}
			else if($rowusername["attempt"]<3)//if attempt less than 3 (password incorrect)
			{
				$sqlupdate="UPDATE login SET attempt=attempt+1 WHERE user_id='$username'";//every password error time increase the attempt
				$resultupdate=mysql_query($sqlupdate)or die("sql error in sqlupdate ".mysql_error());
				echo "<script>alert('Your Password is not Correct');</script>";
			}
			else //for more than three time, re-direct to forgetpassword page for recovery
			{
				$_SESSION["forgetusername"]=$username;//save username as forgetusername
				echo"<script>alert('You Attempt More Than 3 Times; Please Recover Your Password');
				window.location.href='index.php?page=forgetpassword.php';</script>";
			}		
		}
		else//for not register username
		{
			echo "<script>alert('There is no Username');</script>";
		}
	}
?>
	<div class="login">
	
		<div class="main-agileits">
				<div class="form-w3agile">
					<h3>Login</h3>
					<form action="#" method="POST">
						<div class="key">
							<i class="fa fa-envelope" aria-hidden="true"></i>
							<input  type="text" name="txtemail" id="txtemail" placeholder="Enter E-Mail..." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Email';}" required="">
							<div class="clearfix"></div>
						</div>
						<div class="key">
							<i class="fa fa-lock" aria-hidden="true"></i>
							<input  type="password" name="txtpassword" id="txtpassword" placeholder="Enter Password..." onfocus="this.value = '';" onblur="if (this.value == '') {this.value = 'Password';}" required="">
							<div class="clearfix"></div>
						</div>
						<input type="submit" value="Login" name="btnlogin" id="btnlogin">
					</form>
				</div>
				<div class="forg">
					<a href="index.php?page=forgetpassword.php" class="forg-left">Forgot Password</a>
					<a href="index.php?page=register.php" class="forg-right">Register</a>
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