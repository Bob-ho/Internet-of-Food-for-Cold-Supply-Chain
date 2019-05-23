<?php
if(!isset($_SESSION))
{
	session_start();
}
if(isset($_SESSION["usertype"]))
{
	$usertype=$_SESSION["usertype"];
	$username=$_SESSION["username"];
}
else
{
	$usertype="Guest";
}
if($usertype=="Admin" || $usertype=="Clerk" || $usertype=="DeliveryBoy" || $usertype=="Customer" || $usertype=="OtherBusiness")
{
include("config.php");
if(isset($_POST["btn_submit_new"]))
{
	$sqlinsert="INSERT INTO bill(order_id,customer_id,date,time,payment_mode,pay_status)
				VALUES('".mysql_real_escape_string($_POST["txt_order_id"])."',
					'".mysql_real_escape_string($_POST["txt_customer_id"])."',
					'".mysql_real_escape_string($_POST["txt_date"])."',
					'".mysql_real_escape_string(date("H:i:s"))."',
					'".mysql_real_escape_string($_POST["txt_paymentmode"])."',
					'".mysql_real_escape_string("Pending")."')";
	$resultinsert=mysql_query($sqlinsert) or die("sql error in sqlinsert ".mysql_error());
	if($resultinsert)
	{
		if(isset($_SESSION["addtocardorderid"]))
		{
			unset($_SESSION["addtocardorderid"]);
		}
		if(isset($_SESSION["addtocardorderid_totalprice"]))
		{
			unset($_SESSION["addtocardorderid_totalprice"]);
		}
		echo '<script>alert("Successfully Insert!!!");alert("Please pay your amount to BOC bank account 765124"); </script>';
	}
}
if(isset($_POST["btn_submit_edit"]))
{
	$sqlupdate="UPDATE bill SET
							customer_id='".mysql_real_escape_string($_POST["txt_customer_id"])."',
							date='".mysql_real_escape_string($_POST["txt_date"])."',
							time='".mysql_real_escape_string($_POST["txt_time"])."',
							payment_mode='".mysql_real_escape_string($_POST["txt_paymentmode"])."',
							pay_status='".mysql_real_escape_string($_POST["txt_pay_status"])."'
						WHERE order_id='".mysql_real_escape_string($_POST["txt_order_id"])."'";
	$resultupdate=mysql_query($sqlupdate) or die("sql error in sqlupdate ".mysql_error());
	if($resultupdate)
	{
		echo '<script>alert("Successfully Update!!!");
		window.location.href="index.php?page=bill.php&option=view";</script>';
	}
}	

?>
<html>
<script> 
function calculate_billprice()
{
	var orderid=document.getElementById("txt_order_id").value;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() 
	{
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
		{
			var response=xmlhttp.responseText.trim();
			document.getElementById("txt_totalprice").value=response;
		}
	};
	xmlhttp.open("GET", "ajaxpage.php?frompage=billnew&orderid=" + orderid, true);
	xmlhttp.send();	
}
</script>
	<body>
	<?php
	if(isset($_GET["option"]))
	{
		if($_GET["option"]=="new")
		{
			if($usertype=="Admin" || $usertype=="Clerk" || $usertype=="Customer")
			{
	?>
			<form name="bill_add" id="bill_add" action="" method="POST">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Add New Bill
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
									<tr><th>Order ID</th><td>
									<select required name="txt_order_id" id="txt_order_id" class="form-control" onchange="calculate_billprice()">
									
									<?php
									if(isset($_SESSION["addtocardorderid"]))
									{
										$orderid=$_SESSION["addtocardorderid"];
										$sqlloadorder="SELECT DISTINCT order_id FROM order_details WHERE order_id='$orderid'";
									}
									else
									{
										$sqlloadorder="SELECT DISTINCT order_id FROM order_details";
										echo '<option value="select_order">Select The Order</option>';
									}
									$resultloadorder=mysql_query($sqlloadorder) or die("SQL error in sqlloadorder ".mysql_error());
									while($rowloadorder=mysql_fetch_assoc($resultloadorder))
									{
										$sqlbillordercheck="SELECT order_id FROM bill WHERE order_id='$rowloadorder[order_id]'";
										$orderresultcheck=mysql_query($sqlbillordercheck) or die ("SQL error in sqlbillordercheck ".mysql_error());
										if(mysql_num_rows($orderresultcheck)==0)
										{
											echo '<option value="'.$rowloadorder["order_id"].'">'.$rowloadorder["order_id"].'</option>';
										}
									}
									?>
									</select>
									</td></tr>
									<tr><th>Customer ID</th><td>
									<select required name="txt_customer_id" id="txt_customer_id" class="form-control">
									
									<?php 
									if($usertype=="Customer")
									{
										$sqlloadcustomer="SELECT customer_id, name FROM customer WHERE customer_id='$username'";
									}
									else
									{
										$sqlloadcustomer="SELECT customer_id, name FROM customer";
										echo '<option value="select_customer">Select The customer</option>';
									}
									$resultloadcustomer=mysql_query($sqlloadcustomer) or die("SQL error in sqlloadcustomer ".mysql_error());
									while($rowloadcustomer=mysql_fetch_assoc($resultloadcustomer))
									{
										echo '<option value="'.$rowloadcustomer["customer_id"].'">'.$rowloadcustomer["name"].'</option>';
									}
									?>
									</select>
									</td></tr>
									<?php
									if(isset($_SESSION["addtocardorderid_totalprice"]))
									{
										$totalprice=$_SESSION["addtocardorderid_totalprice"];
									}
									else
									{
										$totalprice=0;
									}
									?>
									<tr><th>Total Pay Amount</th><td><input type="text" value="<?php echo $totalprice;  ?>" required readonly name="txt_totalprice" id="txt_totalprice" class="form-control"></td></tr>
									<tr><th>Date</th><td><input type="date" readonly required value="<?php echo date("Y-m-d");  ?>" name="txt_date" id="txt_date" class="form-control"></td></tr>
									
									<tr><th>Payment Mode</th><td>
									<select required name="txt_paymentmode" id="txt_paymentmode" class="form-control">
									<option value="Cash">Cash </option>
									<option value="EzCash">EzCash </option>
									<option value="Bank">Bank </option>
									<option value="Card">Card</option>
									</select></td></tr>
									<tr><td colspan="2"><center>
													<a href="index.php?page=bill.php&option=view"><input class="btn btn-default btn-grad" type="button" name="btn_goback" id="btn_goback" value="Go Back"></a>
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
				header("location:index.php?page=bill.php&option=view");
			}
	}
		else if($_GET["option"]=="view")
		{
			echo '<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Bill Details
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
									if($usertype=="Admin" || $usertype=="Clerk")
									{
										echo '<a href="index.php?page=bill.php&option=new"><button class="btn btn-primary btn-grad">Add New Bill</button></a>';
									}
									echo '<thead><tr><th></th><th>Order ID</th><th>Customer Name</th><th>Date</th><th>Pay Status</th><th>Action</th></tr></thead><tbody>';
									if($usertype=="Customer")
									{
										$sqlview="SELECT order_id,customer_id,date,pay_status FROM bill WHERE customer_id='$username' ORDER BY order_id DESC";
									}
									else
									{
										$sqlview="SELECT order_id,customer_id,date,pay_status FROM bill ORDER BY order_id DESC";
									}
									$resultview=mysql_query($sqlview) or die("sql error in sqlview ".mysql_error());
									$x=1;
									while($rowview=mysql_fetch_assoc($resultview))
									{
										$sqlcustomername="SELECT name FROM customer WHERE customer_id='$rowview[customer_id]'";
										$resultcustomername=mysql_query($sqlcustomername) or die("sql error in sqlcustomername ,".mysql_error());
										$rowcustomername=mysql_fetch_assoc($resultcustomername);
										echo '<tr>';
											echo '<td>'.$x.'</td>';
											echo '<td>'.$rowview["order_id"].'</td>';
											echo '<td>'.$rowcustomername["name"].'</td>';
											echo '<td>'.$rowview["date"].'</td>';
											echo '<td>'.$rowview["pay_status"].'</td>';
											echo '<td>';
												echo '<a href="index.php?page=bill.php&option=fullview&orderid='.$rowview["order_id"].'"><button class="btn btn-success btn-grad"><i class="fa fa-eye"></i> View</button></a> ';
												if($usertype=="Admin" || $usertype=="Clerk" || $usertype=="Customer")
												{
													if($rowview["pay_status"]=="Pending")
													{
														//echo '<a href="index.php?page=bill.php&option=edit&orderid='.$rowview["order_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-pencil"></i> Edit</button></a> ';
														echo '<a onclick="return deletedata()" href="index.php?page=bill.php&option=delete&orderid='.$rowview["order_id"].'"><button class="btn btn-danger btn-grad"><i class="fa fa-trash"></i> Cancel</button></a> ';
													}
													if($usertype=="Admin" || $usertype=="Clerk")
													{
														if($rowview["pay_status"]=="Pending")
														{
															echo '<a href="index.php?page=bill.php&option=payupdate&orderid='.$rowview["order_id"].'&status=Paid"><button class="btn btn-success btn-grad"><i class="fa fa-check"></i> Paid</button></a> ';
															echo '<a href="index.php?page=bill.php&option=payupdate&orderid='.$rowview["order_id"].'&status=NotPaid"><button class="btn btn-danger btn-grad"><i class="fa fa-times"></i> Not Paid</button></a> ';
														}
													}
													$sqldeliverycheck="SELECT order_id FROM delivery WHERE order_id='$rowview[order_id]'";
													$resultdeliverycheck=mysql_query($sqldeliverycheck) or die("sql error in sqldeliverycheck ,".mysql_error());
													if(mysql_num_rows($resultdeliverycheck)==1)
													{
														echo '<button class="btn btn-default btn-grad"><i class="fa fa-gift"></i> Delivered </button>';
													}
													else if($rowview["pay_status"]=="Paid")
													{
														if($usertype=="Admin" || $usertype=="Clerk")
														{
															echo '<a href="index.php?page=delivery.php&option=new&orderid='.$rowview["order_id"].'"><button class="btn btn-success btn-grad"><i class="fa fa-truck"></i> Delivery </button></a> ';
														}
														else
														{
															echo '<button class="btn btn-default btn-grad"> Pending Delivery </button>';
														}
													}
												}
												else
												{
													$sqldeliverycheck="SELECT order_id FROM delivery WHERE order_id='$rowview[order_id]'";
													$resultdeliverycheck=mysql_query($sqldeliverycheck) or die("sql error in sqldeliverycheck ,".mysql_error());
													if(mysql_num_rows($resultdeliverycheck)==1)
													{
														echo '<button class="btn btn-default btn-grad"><i class="fa fa-gift"></i> Delivered </button>';
													}
													else if($rowview["pay_status"]=="Paid")
													{
														if($usertype=="Admin" || $usertype=="Clerk")
														{
															echo '<a href="index.php?page=delivery.php&option=new&orderid='.$rowview["order_id"].'"><button class="btn btn-success btn-grad"><i class="fa fa-truck"></i> Delivery </button></a> ';
														}
														else
														{
															echo '<button class="btn btn-default btn-grad"> Pending Delivery </button>';
														}
													}
												}
											echo '</td>';
										echo '</tr>';
										$x++;
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
			$sqlfullview="SELECT * FROM bill WHERE order_id='$fullvieworderid'";
			$resultfullview=mysql_query($sqlfullview) or die("sql error in sqlfullview ,".mysql_error());
			$rowfullview=mysql_fetch_assoc($resultfullview);
			
			$sqlcustomername="SELECT name FROM customer WHERE customer_id='$rowfullview[customer_id]'";
			$resultcustomername=mysql_query($sqlcustomername) or die("sql error in sqlcustomername ,".mysql_error());
			$rowcustomername=mysql_fetch_assoc($resultcustomername);
			echo '<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Bill Details Full View
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
									echo '<tr><th>Order ID</th><td>'.$rowfullview["order_id"].'</td></tr>';
									echo '<tr><th>Customer Name</th><td>'.$rowcustomername["name"].'</td></tr>';
									echo '<tr><th>Date</th><td>'.$rowfullview["date"].'</td></tr>';
									echo '<tr><th>Time</th><td>'.$rowfullview["time"].'</td></tr>';
									echo '<tr><th>Payment Mode</th><td>'.$rowfullview["payment_mode"].'</td></tr>';
									echo '<tr><th>Pay Status</th><td>'.$rowfullview["pay_status"].'</td></tr>';
									echo '<tr><td colspan="2">'; 
									$sqloredrdetails="SELECT * FROM order_details WHERE order_id='$rowfullview[order_id]'";
									$resultorderdetails=mysql_query($sqloredrdetails) or die("sql error in sqloredrdetails ,".mysql_error());
									echo '<table class="table table-striped table-bordered table-hover">';
									echo '<tr><th> Product Name </th> <th> Quantity </th> </tr>';
									while($roworderdetails=mysql_fetch_assoc($resultorderdetails))
									{
										$productid=$roworderdetails["product_id"];
										$sqlproduct="SELECT Food_Description FROM products WHERE Index_no='$productid'";
										$resultproduct=mysql_query($sqlproduct) or die("SQL error in sqlproduct ".mysql_error());
										$rowproduct=mysql_fetch_assoc($resultproduct);
										echo '<tr><td> '.$rowproduct["Food_Description"].' </td> <td> '.$roworderdetails["number_of_product"].' </td> </tr>';
									}
									echo '</table>';
									echo '</td></tr>';
									echo '<tr><td colspan="2"><center>';
										echo '<a href="index.php?page=bill.php&option=view"><button class="btn btn-default btn-grad"><i class="fa fa-reply"></i> Go Back</button></a> ';
										if($usertype=="Admin" || $usertype=="Clerk" || $usertype=="Customer")
										{
											if($rowfullview["pay_status"]=="Pending")
											{
												//echo '<a href="index.php?page=bill.php&option=edit&orderid='.$rowfullview["order_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-pencil"></i> Edit</button></a> ';
											}
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
			if($usertype=="Admin" || $usertype=="Clerk" || $usertype=="Customer")
			{
				$editorderid=$_GET["orderid"];
					$sqledit="SELECT * FROM bill WHERE order_id='$editorderid'";
					$resultedit=mysql_query($sqledit) or die("sql error in sqledit ,".mysql_error());
					$rowedit=mysql_fetch_assoc($resultedit);
					echo '<form name="bill_add" id="bill_add" action="" method="POST">';
					echo '<div class="row">
						<div class="col-lg-12">
							<div class="panel panel-default">
								<div class="panel-heading">
									Bill Details Edit
								</div>
								<div class="panel-body">
									<div class="table-responsive">
										<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
										echo '<tr><th>Order ID</th><td><input type="text" readonly value="'.$rowedit["order_id"].'" name="txt_order_id" id="txt_order_id" class="form-control"></td></tr>';
										echo '<tr><th>Customer Name</th><td>
										<select required name="txt_customer_id" id="txt_customer_id" class="form-control">';
										$sqlloadcustomer="SELECT customer_id, name FROM customer";
										$resultloadcustomer=mysql_query($sqlloadcustomer) or die("SQL error in sqlloadcustomer ".mysql_error());
										while($rowloadcustomer=mysql_fetch_assoc($resultloadcustomer))
										{
											if($rowedit["customer_id"]==$rowloadcustomer["customer_id"])
											{
												echo '<option selected value="'.$rowloadcustomer["customer_id"].'">'.$rowloadcustomer["name"].'</option>';
											}
											else
											{
												echo '<option value="'.$rowloadcustomer["customer_id"].'">'.$rowloadcustomer["name"].'</option>';
											}											
										}
										echo '</select>';
										echo '</td></tr>
										<tr><th>Date</th><td><input type="date" value="'.$rowedit["date"].'" required name="txt_date" id="txt_date" class="form-control"></td></tr>
										<tr><th>Time</th><td><input type="time" value="'.$rowedit["time"].'" required name="txt_time" id="txt_time" class="form-control"></td></tr>
										<tr><th>Payment Mode</th><td><input type="text" value="'.$rowedit["payment_mode"].'" required name="txt_paymentmode" id="txt_paymentmode" class="form-control"></td></tr>
										<tr><th>Pay Status</th><td><input type="text" value="'.$rowedit["pay_status"].'" required name="txt_pay_status" id="txt_pay_status" class="form-control"></td></tr>
										<tr><td colspan="2"><center>
													<a href="index.php?page=bill.php&option=view"><input class="btn btn-default btn-grad" type="button" name="btn_goback" id="btn_goback" value="Go Back"></a>
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
				header("location:index.php?page=bill.php&option=view");
			}
		}
		else if($_GET["option"]=="delete")
		{
			if($usertype=="Admin" || $usertype=="Clerk" || $usertype=="Customer")
			{
				$deleteorderid=$_GET["orderid"];
					$sqldelete="DELETE FROM bill WHERE order_id='$deleteorderid'";
					$resultdelete=mysql_query($sqldelete) or die("sql error in sqldelete ,".mysql_error());
					if($resultdelete)
					{
						echo '<script>alert("Successfully Deleted!!!");
						window.location.href="index.php?page=bill.php&option=view";</script>';
					}
			}
			else
			{
				header("location:index.php?page=bill.php&option=view");
			}
		}
		else if($_GET["option"]=="payupdate")
		{
			if($usertype=="Admin" || $usertype=="Clerk")
			{
				$statusorderid=$_GET["orderid"];
				$statusstatus=$_GET["status"];
			
					$sqlupdate="UPDATE bill SET pay_status='$statusstatus' WHERE order_id='$statusorderid'";
					$resultupdate=mysql_query($sqlupdate) or die("sql error in sqlupdate ,".mysql_error());
					if($resultupdate)
					{
						echo '<script>alert("Successfully Updated!!!");
						window.location.href="index.php?page=bill.php&option=view";</script>';
					}
			}
			else
			{
				header("location:index.php?page=bill.php&option=view");
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