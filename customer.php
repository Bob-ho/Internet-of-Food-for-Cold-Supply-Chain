<?php
if(!isset($_SESSION))
{
	session_start();
}
if(isset($_SESSION["usertype"]))
{
	$usertype=$_SESSION["usertype"];
}
else
{
	$usertype="Guest";
}
if($usertype=="Admin" || $usertype=="Clerk" || $usertype=="OtherBusiness")
{
include("config.php");
if(isset($_POST["btn_submit_new"]))
{
	$sqlinsert="INSERT INTO customer(customer_id,name,nic,gender,dob,address,mobile,land,email,joindate)
							VALUES('".mysql_real_escape_string($_POST["txt_customer_id"])."',
								'".mysql_real_escape_string($_POST["txt_customer_name"])."',
								'".mysql_real_escape_string($_POST["txt_nic"])."',
								'".mysql_real_escape_string($_POST["txt_customer_gender"])."',
								'".mysql_real_escape_string($_POST["txt_dob"])."',
								'".mysql_real_escape_string($_POST["txt_customer_address"])."',
								'".mysql_real_escape_string($_POST["txt_mobile"])."',
								'".mysql_real_escape_string($_POST["txt_land"])."',
								'".mysql_real_escape_string($_POST["txt_customer_email"])."',
								'".mysql_real_escape_string($_POST["txt_customer_joindate"])."')";
	$resultinsert=mysql_query($sqlinsert) or die("sql error in sqlinsert ".mysql_error());
	
	$sqllogin="INSERT INTO login(user_id,password,usertype,status)
							VALUES('".mysql_real_escape_string($_POST["txt_customer_id"])."',
								'".mysql_real_escape_string(md5($_POST["txt_nic"]))."',
								'".mysql_real_escape_string("Customer")."',
								'".mysql_real_escape_string("Active")."')";
	$resultlogin=mysql_query($sqllogin) or die("sql error in sqllogin ".mysql_error());
	if($resultinsert)
	{
		echo '<script>alert("Successfully Insert!!!");</script>';
	}
}
if(isset($_POST["btn_submit_edit"]))
{
	$sqlupdate="UPDATE customer SET
							name='".mysql_real_escape_string($_POST["txt_customer_name"])."',
							nic='".mysql_real_escape_string($_POST["txt_nic"])."',
							gender='".mysql_real_escape_string($_POST["txt_customer_gender"])."',
							dob='".mysql_real_escape_string($_POST["txt_dob"])."',
							address='".mysql_real_escape_string($_POST["txt_customer_address"])."',
							mobile='".mysql_real_escape_string($_POST["txt_mobile"])."',
							land='".mysql_real_escape_string($_POST["txt_land"])."',
							email='".mysql_real_escape_string($_POST["txt_customer_email"])."',
							joindate='".mysql_real_escape_string($_POST["txt_customer_joindate"])."'
							WHERE customer_id='".mysql_real_escape_string($_POST["txt_customer_id"])."'";
	$resultupdate=mysql_query($sqlupdate) or die("sql error in sqlupdate ".mysql_error());
	if($resultupdate)
	{
		echo '<script>alert("Successfully Update!!!");
		window.location.href="index.php?page=customer.php&option=view";</script>';
	}
}
?>
<html>
	<body>
		<?php
			if(isset($_GET["option"]))
			{
				if($_GET["option"]=="new")
				{
					if($usertype=="Admin" || $usertype=="Clerk")
					{
		?>
					<form name="customer_add" id="customer_add" action="" method="POST">
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									Add New Customer
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover">
										<tr><th>Customer ID</th><td>
										<?php
										$sqlgenerateid="SELECT customer_id FROM customer ORDER BY customer_id DESC LIMIT 1";
										$resultgenerateid=mysql_query($sqlgenerateid) or die("sql error in sqlgenerateid ".mysql_error());
										if(mysql_num_rows($resultgenerateid)>0)
										{
											$rowgenerateid=mysql_fetch_assoc($resultgenerateid);
											$customerid=++$rowgenerateid["customer_id"];
										}
										else
										{
											$customerid="CU00001";
										}
										?>
										<input type="text" readonly value="<?php echo $customerid; ?>" name="txt_customer_id" id="txt_customer_id" class="form-control"></td></tr>
										<tr><th>Customer Name</th><td><input type="text" onkeypress="return isTextKey(event)" required name="txt_customer_name" id="txt_customer_name" class="form-control"></td></tr>
										<tr><th>Customer NIC</th><td><input type="text" onblur="nicnumber()" required name="txt_nic" id="txt_nic" class="form-control"></td></tr>
										<tr><th>Customer Gender</th><td><input type="text" readonly required name="txt_customer_gender" id="txt_customer_gender" class="form-control"></td></tr>
										<tr><th>Customer DOB</th><td><input type="date" readonly required name="txt_dob" id="txt_dob" class="form-control"></td></tr>
										<tr><th>Customer Address</th><td><input type="text" required name="txt_customer_address" id="txt_customer_address" class="form-control"></td></tr>
										<tr><th>Customer Moblie</th><td><input type="number" onkeypress="return isNumberKey(event)" onblur="phonenumber()" required name="txt_mobile" id="txt_mobile" class="form-control"></td></tr>
										<tr><th>Customer Land</th><td><input type="number" required name="txt_land" onkeypress="return isNumberKey(event)" onblur="landnumber()" id="txt_land" class="form-control"></td></tr>
										<tr><th>Customer E-Mail</th><td><input type="email" required name="txt_customer_email" id="txt_customer_email" class="form-control"></td></tr>
										<tr><th>Customer Join Date</th><td><input type="date" required name="txt_customer_joindate" id="txt_customer_joindate" class="form-control"></td></tr>
										<tr><td colspan="2"><center>
													<a href="index.php?page=customer.php&option=view"><input class="btn btn-default btn-grad" type="button" name="btn_goback" id="btn_goback" value="Go Back"></a>
													<input class="btn btn-danger btn-grad" type="reset" name="btn_clear" id="btn_clear" value="Clear">
													<input type="submit" class="btn btn-success btn-grad" name="btn_submit_new" id="btn_submit_new" value="Save"></center></td></tr>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					</form>
		
		
		<?php
					}
					else
					{
						header("location:index.php?page=customer.php&option=view");
					}
		}
				else if($_GET["option"]=="view")
				{
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Customer Details
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
									if($usertype=="Admin" || $usertype=="Clerk")
									{
										echo '<a href="index.php?page=customer.php&option=new"><button class="btn btn-primary btn-grad">Add New Customer</button></a>';
									}
									echo '<thead><tr><th>Customer ID</th><th>Customer Name</th><th>NIC</th><th>Mobile</th><th>Action</th></tr></thead><tbody>';
									$sqlview="SELECT customer_id,name,nic,mobile FROM customer";
									$resultview=mysql_query($sqlview) or die("sql error in sqlview ".mysql_error());
									while($rowview=mysql_fetch_assoc($resultview))
									{
										
										echo '<tr>';
											echo '<td>'.$rowview["customer_id"].'</td>';
											echo '<td>'.$rowview["name"].'</td>';
											echo '<td>'.$rowview["nic"].'</td>';
											echo '<td>'.$rowview["mobile"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=customer.php&option=fullview&customerid='.$rowview["customer_id"].'"><button class="btn btn-success btn-grad"><i class="fa fa-eye"></i> View</button></a> ';
												if($usertype=="Admin" || $usertype=="Clerk")
												{
													echo '<a href="index.php?page=customer.php&option=edit&customerid='.$rowview["customer_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-pencil"></i> Edit</button></a> ';
													echo '<a onclick="return deletedata()" href="index.php?page=customer.php&option=delete&customerid='.$rowview["customer_id"].'"><button class="btn btn-danger btn-grad"><i class="fa fa-trash"></i> Delete</button></a> ';
												}
											echo '</td>';
										echo '</tr>';
									}
									echo '</tbody></table>
								</div>
							</div>
						</div>
					</div>
				</div>';			
				}
				else if($_GET["option"]=="fullview")
				{
					$fullviewcustomerid=$_GET["customerid"];
					$sqlfullview="SELECT * FROM customer WHERE customer_id='$fullviewcustomerid'";
					$resultfullview=mysql_query($sqlfullview) or die("sql error in sqlfullview ,".mysql_error());
					$rowfullview=mysql_fetch_assoc($resultfullview);
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									Customer Details Full View
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
										echo '<tr><th>Customer ID</th><td>'.$rowfullview["customer_id"].'</td></tr>';
										echo '<tr><th>Name</th><td>'.$rowfullview["name"].'</td></tr>';
										echo '<tr><th>NIC</th><td>'.$rowfullview["nic"].'</td></tr>';
										echo '<tr><th>Gender</th><td>'.$rowfullview["gender"].'</td></tr>';
										echo '<tr><th>Date of Birth</th><td>'.$rowfullview["dob"].'</td></tr>';
										echo '<tr><th>Address</th><td>'.$rowfullview["address"].'</td></tr>';
										echo '<tr><th>Mobile</th><td>'.$rowfullview["mobile"].'</td></tr>';
										echo '<tr><th>Land No</th><td>'.$rowfullview["land"].'</td></tr>';
										echo '<tr><th>E-Mail</th><td>'.$rowfullview["email"].'</td></tr>';
										echo '<tr><th>Join Date</th><td>'.$rowfullview["joindate"].'</td></tr>';
										if(!isset($_GET["pr"]))
										{
											echo '<tr><td colspan="2"><center>';
												echo '<a href="index.php?page=customer.php&option=view"><button class="btn btn-default btn-grad"><i class="fa fa-reply"></i> Go Back</button></a> ';
												if($usertype=="Admin" || $usertype=="Clerk")
												{
													echo '<a target="_blank" href="print.php?pr=customer.php&option=fullview&customerid='.$rowfullview["customer_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-print"></i> Print</button></a> ';
													echo '<a href="index.php?page=customer.php&option=edit&customerid='.$rowfullview["customer_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-pencil"></i> Edit</button></a> ';
												}
												echo '</center></td></tr>';
										}
										echo '</table>
									</div>
								</div>
							</div>
						</div>
					</div>';
				}
				else if($_GET["option"]=="edit")
				{
					if($usertype=="Admin" || $usertype=="Clerk")
					{
					$editcustomerid=$_GET["customerid"];
					$sqledit="SELECT * FROM customer WHERE customer_id='$editcustomerid'";
					$resultedit=mysql_query($sqledit) or die("sql error in sqledit ,".mysql_error());
					$rowedit=mysql_fetch_assoc($resultedit);
					echo '<form name="customer_add" id="customer_add" action="" method="POST">';
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									Customer Details Edit
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
										echo '<tr><th>Customer ID</th><td><input type="text" readonly value="'.$rowedit["customer_id"].'"name="txt_customer_id" id="txt_customer_id" class="form-control"></td></tr>';
										echo '<tr><th>Customer Name</th><td><input type="text" value="'.$rowedit["name"].'" onkeypress="return isTextKey(event)" required name="txt_customer_name" id="txt_customer_name" class="form-control"></td></tr>
										<tr><th>Customer NIC</th><td><input type="text" value="'.$rowedit["nic"].'" onblur="nicnumber()" readonly required name="txt_nic" id="txt_nic" class="form-control"></td></tr>
										<tr><th>Customer Gender</th><td><input type="text" value="'.$rowedit["gender"].'" readonly required name="txt_customer_gender" id="txt_customer_gender" class="form-control"></td></tr>
										<tr><th>Customer DOB</th><td><input type="date" value="'.$rowedit["dob"].'" readonly required name="txt_dob" id="txt_dob" class="form-control"></td></tr>
										<tr><th>Customer Address</th><td><input type="text" value="'.$rowedit["address"].'" required name="txt_customer_address" id="txt_customer_address" class="form-control"></td></tr>
										<tr><th>Customer Moblie</th><td><input type="number" value="'.$rowedit["mobile"].'"onkeypress="return isNumberKey(event)" onblur="phonenumber()" required name="txt_mobile" id="txt_mobile" class="form-control"></td></tr>
										<tr><th>Customer Land</th><td><input type="number" value="'.$rowedit["land"].'"onkeypress="return isNumberKey(event)" onblur="landnumber()" required name="txt_land" id="txt_land" class="form-control"></td></tr>
										<tr><th>Customer E-Mail</th><td><input type="email" value="'.$rowedit["email"].'" required name="txt_customer_email" id="txt_customer_email" class="form-control"></td></tr>
										<tr><th>Customer Join Date</th><td><input type="date" value="'.$rowedit["joindate"].'" required name="txt_customer_joindate" id="txt_customer_joindate" class="form-control"></td></tr>
										<tr><td colspan="2"><center>
													<a href="index.php?page=customer.php&option=view"><input class="btn btn-default btn-grad" type="button" name="btn_goback" id="btn_goback" value="Go Back"></a>
													<input class="btn btn-danger btn-grad" type="reset" name="btn_clear" id="btn_clear" value="Clear">
													<input type="submit" class="btn btn-success btn-grad" name="btn_submit_edit" id="btn_submit_edit" value="Save Changes"></center></td></tr>';
										echo '</table>
									</div>
								</div>
							</div>
						</div>
					</div>';
					echo '</form>';
					}
					else
					{
						header("location:index.php?page=customer.php&option=view");
					}
				}
				else if($_GET["option"]=="delete")
				{
					if($usertype=="Admin" || $usertype=="Clerk")
					{
						$deletecustomerid=$_GET["customerid"];
						$sqldelete="DELETE FROM customer WHERE customer_id='$deletecustomerid'";
						$resultdelete=mysql_query($sqldelete) or die("sql error in sqldelete ,".mysql_error());
						if($resultdelete)
						{
							echo '<script>alert("Successfully Deleted!!!");
							window.location.href="index.php?page=customer.php&option=view";</script>';
						}
					}
					else
					{
						header("location:index.php?page=customer.php&option=view");
					}
				}
			}
		?>
	</body>
</html>
<?php
}
else
{
	header("location:index.php");
}
?>