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
if($usertype=="Admin" || $usertype=="Clerk" || $usertype=="DeliveryBoy" || $usertype=="OtherBusiness")
{
include("config.php");
if(isset($_POST["btn_submit_new"]))
{
	$sqlinsert="INSERT INTO delivery(order_id,delivery_date,staff_id,status,delivery_address,type)
				VALUES('".mysql_real_escape_string($_POST["txt_order_id"])."',
				'".mysql_real_escape_string($_POST["txt_delivery_date"])."',
				'".mysql_real_escape_string($_POST["txt_staff_id"])."',
				'".mysql_real_escape_string("Delivered")."',
				'".mysql_real_escape_string($_POST["txt_delivery_address"])."',
				'".mysql_real_escape_string($_POST["txt_type"])."')";
	$resultinsert=mysql_query($sqlinsert) or die("sql error in sqlinsert ".mysql_error());
	
	if($resultinsert)
	{
		echo '<script>alert("Successfully Insert!!!");
			window.location.href="index.php?page=bill.php&option=view";</script>';
	}
}
if(isset($_POST["btn_submit_edit"]))
{
	$sqlupdate="UPDATE delivery SET
							delivery_date='".mysql_real_escape_string($_POST["txt_delivery_date"])."',
							staff_id='".mysql_real_escape_string($_POST["txt_staff_id"])."',
							status='".mysql_real_escape_string($_POST["txt_status"])."',
							delivery_address='".mysql_real_escape_string($_POST["txt_delivery_address"])."',
							type='".mysql_real_escape_string($_POST["txt_type"])."'
						WHERE order_id='".mysql_real_escape_string($_POST["txt_order_id"])."'";
	$resultupdate=mysql_query($sqlupdate) or die("sql error in sqlupdate ".mysql_error());
	if($resultupdate)
	{
		echo '<script>alert("Successfully Update!!!");
		window.location.href="index.php?page=delivery.php&option=view";</script>';
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
			if($usertype=="Admin" || $usertype=="Clerk" )
			{
	?>
		<form name="delivery_add" id="delivery_add" action="" method="POST">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Add New Delivery
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
									<tr><th>Order ID</th><td>
									<select required name="txt_order_id" id="txt_order_id" class="form-control">
									
									<?php  
									$sqlloadorder="SELECT order_id FROM order_details WHERE order_id='$_GET[orderid]'";
									$resultloadorder=mysql_query($sqlloadorder) or die("SQL error in sqlloadorder ".mysql_error());
									while($rowloadorder=mysql_fetch_assoc($resultloadorder))
									{
										echo '<option value="'.$rowloadorder["order_id"].'">'.$rowloadorder["order_id"].'</option>';
									}
									?>
									</select>
									</td></tr>
									<tr><th>Delivery Date</th><td><input type="date" value="<?php echo date("Y-m-d") ?>" required name="txt_delivery_date" id="txt_delivery_date" class="form-control"></td></tr>
									<tr><th>Staff ID</th><td>
									<select required name="txt_staff_id" id="txt_staff_id" class="form-control">
									<option value="select_staff">Select The staff</option>
									<?php  
									$sqlloadstaff="SELECT staff_id, name FROM staff WHERE designation='DeliveryBoy'";
									$resultloadstaff=mysql_query($sqlloadstaff) or die("SQL error in sqlloadstaff ".mysql_error());
									while($rowloadstaff=mysql_fetch_assoc($resultloadstaff))
									{
										echo '<option value="'.$rowloadstaff["staff_id"].'">'.$rowloadstaff["name"].'</option>';
									}
									?>
									</select>
									</td></tr>
									
									
									
									<tr><th>Delivery Address</th><td>
									<?php
									$sqlcustomeraddress="SELECT c.address FROM customer as c, bill as b WHERE c.customer_id=b.customer_id AND b.order_id='$_GET[orderid]'";
									$resultcustomeraddress=mysql_query($sqlcustomeraddress) or die("SQL error in sqlcustomeraddress ".mysql_error());
									$rowcustomeraddress=mysql_fetch_assoc($resultcustomeraddress);
									?>
									<input type="text" required value="<?php echo $rowcustomeraddress["address"]; ?> " name="txt_delivery_address" id="txt_delivery_address" class="form-control"></td></tr>
									<tr><th>Type</th><td>
									<select required name="txt_type" id="txt_type" class="form-control">
									<option value="Normal"> Normal </option>
									<option value="Gift"> Gift </option>
									</select></td></tr>
									<tr><td colspan="2">
									<?php
									echo '<table class="table table-striped table-bordered table-hover">';
									echo '<tr><th> Product Name </th> <th> Quantity </th> </tr>';
									$sqloredrdetails="SELECT * FROM order_details WHERE order_id='$_GET[orderid]'";
									$resultorderdetails=mysql_query($sqloredrdetails) or die("sql error in sqloredrdetails ,".mysql_error());
									$enablesubmit=1;
									while($roworderdetails=mysql_fetch_assoc($resultorderdetails))
									{
										$productid=$roworderdetails["product_id"];
																				
										$sqlproduct="SELECT Food_Description FROM products WHERE Index_no='$productid'";
										$resultproduct=mysql_query($sqlproduct) or die("SQL error in sqlproduct ".mysql_error());
										$rowproduct=mysql_fetch_assoc($resultproduct);
										echo '<tr><td> '.$rowproduct["Food_Description"].' </td> <td> '.$roworderdetails["number_of_product"].' </td> </tr>';
									}
									echo '</table>';
									?>
									</td></tr>
									<tr><td colspan="2"><center>
													<a href="index.php?page=bill.php&option=view"><input class="btn btn-default btn-grad" type="button" name="btn_goback" id="btn_goback" value="Go Back"></a>
													<input class="btn btn-danger btn-grad" type="reset" name="btn_clear" id="btn_clear" value="Clear">
													<?php
													if($enablesubmit==1)
													{
														echo '<input type="submit" class="btn btn-success btn-grad" name="btn_submit_new" id="btn_submit_new" value="Save">';
													}
													?>
													</center></td></tr>
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
				header("location:index.php?page=delivery.php&option=view");
			}
		}
		else if($_GET["option"]=="view")
		{
			echo '<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Delivery Details
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
									//echo '<a href="index.php?page=delivery.php&option=new"><button class="btn btn-primary btn-grad">Add New Delivery</button></a>';
									echo '<thead><tr><th>Order ID</th><th>Delivery Date</th><th>Staff Name</th><th>Status</th><th>Action</th></tr></thead><tbody>';
									$sqlview="SELECT order_id,delivery_date,staff_id,status FROM delivery";
									$resultview=mysql_query($sqlview) or die("sql error in sqlview ".mysql_error());
									while($rowview=mysql_fetch_assoc($resultview))
									{
										$sqlstaffname="SELECT name FROM staff WHERE staff_id='$rowview[staff_id]'";
										$resultstaffname=mysql_query($sqlstaffname) or die("sql error in sqlstaffname ,".mysql_error());
										$rowstaffname=mysql_fetch_assoc($resultstaffname);
										echo '<tr>';
											echo '<td>'.$rowview["order_id"].'</td>';
											echo '<td>'.$rowview["delivery_date"].'</td>';
											echo '<td>'.$rowstaffname["name"].'</td>';
											echo '<td>'.$rowview["status"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=delivery.php&option=fullview&orderid='.$rowview["order_id"].'"><button class="btn btn-success btn-grad"><i class="fa fa-eye"></i> View</button></a> ';
												if($usertype=="Admin" || $usertype=="Clerk" )
												{
												/*	echo '<a href="index.php?page=delivery.php&option=edit&orderid='.$rowview["order_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-pencil"></i> Edit</button></a> ';
													echo '<a onclick="return deletedata()" href="index.php?page=delivery.php&option=delete&orderid='.$rowview["order_id"].'"><button class="btn btn-danger btn-grad"><i class="fa fa-trash"></i> Delete</button></a> ';
												*/}
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
			$fullvieworderid=$_GET["orderid"];
			$sqlfullview="SELECT * FROM delivery WHERE order_id='$fullvieworderid'";
			$resultfullview=mysql_query($sqlfullview) or die("sql error in sqlfullview ,".mysql_error());
			$rowfullview=mysql_fetch_assoc($resultfullview);
			
			$sqlstaffname="SELECT name FROM staff WHERE staff_id='$rowfullview[staff_id]'";
			$resulstaffname=mysql_query($sqlstaffname) or die("sql error in sqlstaffname ,".mysql_error());
			$rowstaffname=mysql_fetch_assoc($resulstaffname);
			echo '<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							Order Details Full View
						</div>
						<div class="panel-body">
							<div class="table-responsive">
								<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
								echo '<tr><th>Order ID</th><td>'.$rowfullview["order_id"].'</td></tr>';
								echo '<tr><th>Delivery Date</th><td>'.$rowfullview["delivery_date"].'</td></tr>';
								echo '<tr><th>Staff Name</th><td>'.$rowstaffname["name"].'</td></tr>';
								echo '<tr><th>Status</th><td>'.$rowfullview["status"].'</td></tr>';
								echo '<tr><th>Delivery Address</th><td>'.$rowfullview["delivery_address"].'</td></tr>';
								echo '<tr><th>Type</th><td>'.$rowfullview["type"].'</td></tr>';
								echo '<tr><td colspan="2"><center>';
									echo '<a href="index.php?page=delivery.php&option=view"><button class="btn btn-default btn-grad"><i class="fa fa-reply"></i> Go Back</button></a> ';
									if($usertype=="Admin" || $usertype=="Clerk" )
									{
									//	echo '<a href="index.php?page=delivery.php&option=edit&orderid='.$rowfullview["order_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-pencil"></i> Edit</button></a> ';
									}
								echo '</center></td></tr>';
								echo '</table>
							</div>
						</div>
					</div>
				</div>
			</div>';
		}
		else if($_GET["option"]=="edit")
		{
			if($usertype=="Admin" || $usertype=="Clerk" )
			{
				$editorderid=$_GET["orderid"];
					$sqledit="SELECT * FROM delivery WHERE order_id='$editorderid'";
					$resultedit=mysql_query($sqledit) or die("sql error in sqledit ,".mysql_error());
					$rowedit=mysql_fetch_assoc($resultedit);
					echo '<form name="delivery_add" id="delivery_add" action="" method="POST">';
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									Delivery Details Edit
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
										echo '<tr><th>Order ID</th><td><input type="text" readonly value="'.$rowedit["order_id"].'" name="txt_order_id" id="txt_order_id" class="form-control"></td></tr>';
										echo '<tr><th>Delivery Date</th><td><input type="date" value="'.$rowedit["delivery_date"].'" required name="txt_delivery_date" id="txt_delivery_date" class="form-control"></td></tr>
										<tr><th>Staff Name</th><td>
										<select required name="txt_staff_id" id="txt_staff_id" class="form-control">';
										$sqlloadstaff="SELECT staff_id, name FROM staff";
										$resultloadstaff=mysql_query($sqlloadstaff) or die("SQL error in sqlloadstaff ".mysql_error());
										while($rowloadstaff=mysql_fetch_assoc($resultloadstaff))
										{
											if($rowedit["staff_id"]==$rowloadstaff["staff_id"])
											{
												echo '<option selected value="'.$rowloadstaff["staff_id"].'">'.$rowloadstaff["name"].'</option>';
											}
											else
											{
												echo '<option value="'.$rowloadstaff["staff_id"].'">'.$rowloadstaff["name"].'</option>';
											}											
										}
										echo '</select>';
										echo '</td></tr>
										<tr><th>Status</th><td><input type="text" value="'.$rowedit["status"].'" required name="txt_status" id="txt_status" class="form-control"></td></tr>
										<tr><th>Delivery Address</th><td><input type="text" value="'.$rowedit["delivery_address"].'" required name="txt_delivery_address" id="txt_delivery_address" class="form-control"></td></tr>
										<tr><th>Type</th><td><input type="text" value="'.$rowedit["type"].'" required name="txt_type" id="txt_type" class="form-control"></td></tr>
										<tr><td colspan="2"><center>
													<a href="index.php?page=delivery.php&option=view"><input class="btn btn-default btn-grad" type="button" name="btn_goback" id="btn_goback" value="Go Back"></a>
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
				header("location:index.php?page=delivery.php&option=view");
			}
		}
		else if($_GET["option"]=="delete")
		{
			if($usertype=="Admin" || $usertype=="Clerk" )
			{
				$deleteorderid=$_GET["orderid"];
					$sqldelete="DELETE FROM delivery WHERE order_id='$deleteorderid'";
					$resultdelete=mysql_query($sqldelete) or die("sql error in sqldelete ,".mysql_error());
					if($resultdelete)
					{
						echo '<script>alert("Successfully Deleted!!!");
						window.location.href="index.php?page=delivery.php&option=view";</script>';
					}
			}
			else
			{
				header("location:index.php?page=delivery.php&option=view");
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
