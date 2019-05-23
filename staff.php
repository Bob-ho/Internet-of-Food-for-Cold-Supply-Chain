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
if($usertype=="Admin" || $usertype=="Clerk" || $usertype=="DeliveryBoy")
{
include("config.php");
if(isset($_POST["btn_submit_new"]))
{
	$sqlinsert="INSERT INTO staff(staff_id,name,nic,gender,dob,address,mobile,land,email,joindate,designation)
							VALUES('".mysql_real_escape_string($_POST["txt_staff_id"])."',
								'".mysql_real_escape_string($_POST["txt_staff_name"])."',
								'".mysql_real_escape_string($_POST["txt_nic"])."',
								'".mysql_real_escape_string($_POST["txt_staff_gender"])."',
								'".mysql_real_escape_string($_POST["txt_dob"])."',
								'".mysql_real_escape_string($_POST["txt_staff_address"])."',
								'".mysql_real_escape_string($_POST["txt_mobile"])."',
								'".mysql_real_escape_string($_POST["txt_land"])."',
								'".mysql_real_escape_string($_POST["txt_staff_email"])."',
								'".mysql_real_escape_string($_POST["txt_staff_joindate"])."',
								'".mysql_real_escape_string($_POST["txt_designation"])."')";
	$resultinsert=mysql_query($sqlinsert) or die("sql error in sqlinsert ".mysql_error());
	
	$sqllogin="INSERT INTO login(user_id,password,usertype,status)
							VALUES('".mysql_real_escape_string($_POST["txt_staff_id"])."',
								'".mysql_real_escape_string(md5($_POST["txt_nic"]))."',
								'".mysql_real_escape_string($_POST["txt_designation"])."',
								'".mysql_real_escape_string("Active")."')";
	$resultlogin=mysql_query($sqllogin) or die("sql error in sqllogin ".mysql_error());
	if($resultinsert)
	{
		echo '<script>alert("Successfully Insert!!!");</script>';
	}
}
if(isset($_POST["btn_submit_edit"]))
{
	$sqlupdate="UPDATE staff SET
							name='".mysql_real_escape_string($_POST["txt_staff_name"])."',
							nic='".mysql_real_escape_string($_POST["txt_nic"])."',
							gender='".mysql_real_escape_string($_POST["txt_staff_gender"])."',
							dob='".mysql_real_escape_string($_POST["txt_dob"])."',
							address='".mysql_real_escape_string($_POST["txt_staff_address"])."',
							mobile='".mysql_real_escape_string($_POST["txt_mobile"])."',
							land='".mysql_real_escape_string($_POST["txt_land"])."',
							email='".mysql_real_escape_string($_POST["txt_staff_email"])."',
							joindate='".mysql_real_escape_string($_POST["txt_staff_joindate"])."',
							designation='".mysql_real_escape_string($_POST["txt_designation"])."'
						WHERE staff_id='".mysql_real_escape_string($_POST["txt_staff_id"])."'";
	$resultupdate=mysql_query($sqlupdate) or die("sql error in sqlupdate ".mysql_error());
	
	
	if($resultupdate)
	{
		echo '<script>alert("Successfully Update!!!");
		window.location.href="index.php?page=staff.php&option=view";</script>';
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
		<form name="staff_add" id="staff_add" action="" method="POST">
					<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									Add New Staff
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover">
										<tr><th>Staff ID</th><td>
										<?php
										$sqlgenerateid="SELECT staff_id FROM staff ORDER BY staff_id DESC LIMIT 1";
										$resultgenerateid=mysql_query($sqlgenerateid) or die("sql error in sqlgenerateid ".mysql_error());
										if(mysql_num_rows($resultgenerateid)>0)
										{
											$rowgenerateid=mysql_fetch_assoc($resultgenerateid);
											$staffid=++$rowgenerateid["staff_id"];
										}
										else
										{
											$staffid="ST001";
										}
										?>
										<input type="text" readonly value="<?php echo $staffid; ?>" name="txt_staff_id" id="txt_staff_id" class="form-control"></td></tr>
										<tr><th>Staff Name</th><td><input type="text" onkeypress="return isTextKey(event)" required name="txt_staff_name" id="txt_staff_name" class="form-control"></td></tr>
										<tr><th>Staff NIC</th><td><input type="text" onblur="nicnumber()" required name="txt_nic" id="txt_nic" class="form-control"></td></tr>
										<tr><th>Staff Gender</th><td><input type="text" readonly required name="txt_staff_gender" id="txt_staff_gender" class="form-control"></td></tr>
										<tr><th>Staff DOB</th><td><input type="date" readonly required name="txt_dob" id="txt_dob" class="form-control"></td></tr>
										<tr><th>Staff Address</th><td><input type="text" required name="txt_staff_address" id="txt_staff_address" class="form-control"></td></tr>
										<tr><th>Staff Moblie</th><td><input type="number" onkeypress="return isNumberKey(event)" onblur="phonenumber()" required name="txt_mobile" id="txt_mobile" class="form-control"></td></tr>
										<tr><th>Staff Land</th><td><input type="number" onkeypress="return isNumberKey(event)" onblur="landnumber()" required name="txt_land" id="txt_land" class="form-control"></td></tr>
										<tr><th>Staff E-Mail</th><td><input type="email" required name="txt_staff_email" id="txt_staff_email" class="form-control"></td></tr>
										<tr><th>Staff Join Date</th><td><input type="date" required name="txt_staff_joindate" id="txt_staff_joindate" class="form-control"></td></tr>
										<tr><th>Designation</th><td>
										<select required name="txt_designation" id="txt_designation" class="form-control">
										<option value="select_designation">Select The Designation</option>
										<option value="Admin">Admin</option>
										<option value="Clerk">Clerk</option>
										<option value="DeliveryBoy">Delivery Boy</option>
										</select>
										</td></tr>
										<tr><td colspan="2"><center>
													<a href="index.php?page=staff.php&option=view"><input class="btn btn-default btn-grad" type="button" name="btn_goback" id="btn_goback" value="Go Back"></a>
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
				header("location:index.php?page=staff.php&option=view");
			}
		}
		else if($_GET["option"]=="view")
		{
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Staff Details
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
								if($usertype=="Admin" || $usertype=="Clerk")
								{
									echo '<a href="index.php?page=staff.php&option=new"><button class="btn btn-primary btn-grad">Add New Staff</button></a>';
								}
								echo '<thead><tr><th>Staff ID</th><th>Staff Name</th><th>NIC</th><th>Mobile</th><th>Action</th></tr></thead><tbody>';
								$sqlview="SELECT staff_id,name,nic,mobile FROM staff";
								$resultview=mysql_query($sqlview) or die("sql error in sqlview ".mysql_error());
								while($rowview=mysql_fetch_assoc($resultview))
								{
									echo '<tr>';
										echo '<td>'.$rowview["staff_id"].'</td>';
										echo '<td>'.$rowview["name"].'</td>';
										echo '<td>'.$rowview["nic"].'</td>';
										echo '<td>'.$rowview["mobile"].'</td>';
										echo '<td>';
											echo '<a href="index.php?page=staff.php&option=fullview&staffid='.$rowview["staff_id"].'"><button class="btn btn-success btn-grad"><i class="fa fa-eye"></i> View</button></a> ';
											if($usertype=="Admin" || $usertype=="Clerk")
											{
												echo '<a href="index.php?page=staff.php&option=edit&staffid='.$rowview["staff_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-pencil"></i> Edit</button></a> ';
												echo '<a onclick="return deletedata()" href="index.php?page=staff.php&option=delete&staffid='.$rowview["staff_id"].'"><button class="btn btn-danger btn-grad"><i class="fa fa-trash"></i> Delete</button></a> ';
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
			$fullviewstaffid=$_GET["staffid"];
			$sqlfullview="SELECT * FROM staff WHERE staff_id='$fullviewstaffid'";
			$resultfullview=mysql_query($sqlfullview) or die("sql error in sqlfullview ,".mysql_error());
			$rowfullview=mysql_fetch_assoc($resultfullview);
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Staff Details Full View
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
								echo '<tr><th>Staff ID</th><td>'.$rowfullview["staff_id"].'</td></tr>';
								echo '<tr><th>Name</th><td>'.$rowfullview["name"].'</td></tr>';
								echo '<tr><th>NIC</th><td>'.$rowfullview["nic"].'</td></tr>';
								echo '<tr><th>Gender</th><td>'.$rowfullview["gender"].'</td></tr>';
								echo '<tr><th>Date of Birth</th><td>'.$rowfullview["dob"].'</td></tr>';
								echo '<tr><th>Address</th><td>'.$rowfullview["address"].'</td></tr>';
								echo '<tr><th>Mobile</th><td>0'.$rowfullview["mobile"].'</td></tr>';
								echo '<tr><th>Land No</th><td>0'.$rowfullview["land"].'</td></tr>';
								echo '<tr><th>E-Mail</th><td>'.$rowfullview["email"].'</td></tr>';
								echo '<tr><th>Join Date</th><td>'.$rowfullview["joindate"].'</td></tr>';
								echo '<tr><th>Designation</th><td>'.$rowfullview["designation"].'</td></tr>';
								if(!isset($_GET["pr"]))
								{
									echo '<tr><td colspan="2"><center>';										
										echo '<a href="index.php?page=staff.php&option=view"><button class="btn btn-default btn-grad"><i class="fa fa-reply"></i> Go Back</button></a> ';
										if($usertype=="Admin" || $usertype=="Clerk")
										{
											echo '<a target="_blank" href="print.php?pr=staff.php&option=fullview&staffid='.$rowfullview["staff_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-print"></i> Print</button></a> ';
											echo '<a href="index.php?page=staff.php&option=edit&staffid='.$rowfullview["staff_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-pencil"></i> Edit</button></a> ';
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
			$editstaffid=$_GET["staffid"];
			$sqledit="SELECT * FROM staff WHERE staff_id='$editstaffid'";
			$resultedit=mysql_query($sqledit) or die("sql error in sqledit ,".mysql_error());
			$rowedit=mysql_fetch_assoc($resultedit);
			echo '<form name="staff_add" id="staff_add" action="" method="POST">';
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Staff Details Edit
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
								echo '<tr><th>Staff ID</th><td><input type="text" readonly value="'.$rowedit["staff_id"].'" name="txt_staff_id" id="txt_staff_id" class="form-control"></td></tr>';
								echo '<tr><th>Staff Name</th><td><input type="text" value="'.$rowedit["name"].'" onkeypress="return isTextKey(event)" required name="txt_staff_name" id="txt_staff_name" class="form-control"></td></tr>
								<tr><th>Staff NIC</th><td><input type="text" value="'.$rowedit["nic"].'" onblur="nicnumber()" readonly required name="txt_nic" id="txt_nic" class="form-control"></td></tr>
								<tr><th>Staff Gender</th><td><input type="text" value="'.$rowedit["gender"].'" readonly required name="txt_staff_gender" id="txt_staff_gender" class="form-control"></td></tr>
								<tr><th>Staff DOB</th><td><input type="date" value="'.$rowedit["dob"].'" readonly required name="txt_dob" id="txt_dob" class="form-control"></td></tr>
								<tr><th>Staff Address</th><td><input type="text" value="'.$rowedit["address"].'" required name="txt_staff_address" id="txt_staff_address" class="form-control"></td></tr>
								<tr><th>Staff Moblie</th><td><input type="number" value="0'.$rowedit["mobile"].'" onkeypress="return isNumberKey(event)" onblur="phonenumber()" required name="txt_mobile" id="txt_mobile" class="form-control"></td></tr>
								<tr><th>Staff Land</th><td><input type="number" value="0'.$rowedit["land"].'" onkeypress="return isNumberKey(event)" onblur="landnumber()" required name="txt_land" id="txt_land" class="form-control"></td></tr>
								<tr><th>Staff E-Mail</th><td><input type="email" value="'.$rowedit["email"].'" required name="txt_staff_email" id="txt_staff_email" class="form-control"></td></tr>
								<tr><th>Staff Join Date</th><td><input type="date" value="'.$rowedit["joindate"].'" required name="txt_staff_joindate" id="txt_staff_joindate" class="form-control"></td></tr>
								<tr><th>Designation</th><td>
								<select required name="txt_designation" id="txt_designation" class="form-control">';
								$designation=array("Admin","Clerk","DeliveryBoy");
								for($x=0;$x<count($designation);$x++)
								{
									if($designation[$x]==$rowedit["designation"])
									{
										echo '<option selected value="'.$designation[$x].'">'.$designation[$x].'</option>';
									}
									else
									{
										echo '<option value="'.$designation[$x].'">'.$designation[$x].'</option>';
									}											
								}
								echo '</select>';
								echo '</td></tr>
								
								<tr><td colspan="2"><center>
											<a href="index.php?page=staff.php&option=view"><input class="btn btn-default btn-grad" type="button" name="btn_goback" id="btn_goback" value="Go Back"></a>
											<input class="btn btn-danger btn-grad" type="reset" name="btn_clear" id="btn_clear" value="Reset">
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
				header("location:index.php?page=staff.php&option=view");
			}
		}
		else if($_GET["option"]=="delete")
		{
			if($usertype=="Admin" || $usertype=="Clerk")
			{
				$deletestaffid=$_GET["staffid"];
				$sqldelete="DELETE FROM staff WHERE staff_id='$deletestaffid'";
				$resultdelete=mysql_query($sqldelete) or die("sql error in sqldelete ,".mysql_error());
				if($resultdelete)
				{
					echo '<script>alert("Successfully Deleted!!!");
					window.location.href="index.php?page=staff.php&option=view";</script>';
				}
			}
			else
			{
				header("location:index.php?page=staff.php&option=view");
			}
		}
	}
		?>
	</body>
<html>
<?php
}
else
{
	header("location:index.php");
}
?>