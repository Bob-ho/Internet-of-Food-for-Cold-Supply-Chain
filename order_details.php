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
if($usertype=="Admin" || $usertype=="Clerk" || $usertype=="DeliveryBoy" || $usertype=="OtherBusiness")
{
include("config.php");
if(isset($_POST["btn_submit_new"]))
{
	$sqlinsert="INSERT INTO order_details(order_id,product_id,number_of_product)
				VALUES('".mysql_real_escape_string($_POST["txt_order_id"])."',
					'".mysql_real_escape_string($_POST["txt_product_id"])."',
					'".mysql_real_escape_string($_POST["txt_number_of_product"])."')";
	$resultinsert=mysql_query($sqlinsert) or die("sql error in sqlinsert ".mysql_error());
	if($resultinsert)
	{
		echo '<script>alert("Successfully Insert!!!"); </script>';
	}
}
if(isset($_POST["btn_submit_edit"]))
{
	$sqlupdate="UPDATE order_details SET
							number_of_product='".mysql_real_escape_string($_POST["txt_number_of_product"])."'
							WHERE order_id='".mysql_real_escape_string($_POST["txt_order_id"])."' AND product_id='".mysql_real_escape_string($_POST["txt_product_id"])."'";
	$resultupdate=mysql_query($sqlupdate) or die("sql error in sqlupdate ".mysql_error());
	
	if($resultupdate)
	{
		echo '<script>alert("Successfully Update!!!");
		window.location.href="index.php?page=order_details.php&option=view";</script>';
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
			if($usertype=="Admin" || $usertype=="Clerk"  || $usertype=="Customer")
			{
		?>
			<form name="oreder_details_add" id="order_details_add" action="" method="POST">
				<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Add New Order
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover">
									<tr><th>Order ID</th><td>
									<?php
										$sqlgenerateid="SELECT order_id FROM order_details ORDER BY order_id DESC LIMIT 1";
										$resultgenerateid=mysql_query($sqlgenerateid) or die("sql error in sqlgenerateid ".mysql_error());
										if(mysql_num_rows($resultgenerateid)>0)
										{
											$rowgenerateid=mysql_fetch_assoc($resultgenerateid);
											$orderid=++$rowgenerateid["order_id"];
										}
										else
										{
											$orderid="OR000001";
										}
										?>
									<input type="text" readonly value="<?php echo $orderid; ?>" name="txt_order_id" id="txt_order_id" class="form-control"></td></tr>
									
									<tr><th>Product ID</th><td>
									<select required name="txt_product_id" id="txt_product_id" class="form-control">
									<option value="select_product">Select The product</option>
									<?php  
									$sqlloadproduct="SELECT product_id, product_name FROM product";
									$resultloadproduct=mysql_query($sqlloadproduct) or die("SQL error in sqlloadproduct ".mysql_error());
									while($rowloadproduct=mysql_fetch_assoc($resultloadproduct))
									{
										echo '<option value="'.$rowloadproduct["product_id"].'">'.$rowloadproduct["product_name"].'</option>';
									}
									?>
									</select
									</td></tr>
									<tr><th>Number of Product</th><td><input type="text" onkeypress="return isNumberKey(event)" required name="txt_number_of_product" id="txt_number_of_product" class="form-control"></td></tr>
									<tr><td colspan="2"><center>
													<a href="index.php?page=order_details.php&option=view"><input class="btn btn-default btn-grad" type="button" name="btn_goback" id="btn_goback" value="Go Back"></a>
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
				header("location:index.php?page=order_details.php&option=view");
			}
		}
		else if($_GET["option"]=="view")
		{
			echo '<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Order Details
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
									//echo '<a href="index.php?page=order_details.php&option=new"><button class="btn btn-primary btn-grad">Add New Order</button></a>';
									echo '<thead><tr><th></th><th>Order ID</th><th>Product Name </th><th>Number of Product</th>';
									
									//echo '<th>Action</th>';
									
									echo '</tr></thead><tbody>';
									if($usertype=="OtherBusiness")
									{
										$sqlview="SELECT od.order_id,od.product_id,od.number_of_product FROM order_details as od, product as pp WHERE od.product_id=pp.product_id AND pp.shop_id='$username' ORDER BY order_id DESC";
									}
									else
									{
										$sqlview="SELECT order_id,product_id,number_of_product FROM order_details ORDER BY order_id DESC";
									}
									
									$resultview=mysql_query($sqlview) or die("sql error in sqlview ".mysql_error());
									$x=1;
									while($rowview=mysql_fetch_assoc($resultview))
									{
										$sqlproductname="SELECT product_name FROM product WHERE product_id='$rowview[product_id]'";
										$resultproductname=mysql_query($sqlproductname) or die("sql error in sqlproductname ,".mysql_error());
										$rowproductname=mysql_fetch_assoc($resultproductname);
										echo '<tr>';
											echo '<td>'.$x.'</td>';
											echo '<td>'.$rowview["order_id"].'</td>';
											echo '<td>'.$rowproductname["product_name"].'</td>';
											echo '<td>'.$rowview["number_of_product"].'</td>';
											
											if($usertype=="Admin" || $usertype=="Clerk"  || $usertype=="Customer")
											{
											/*	echo '<td>';
												echo '<a href="index.php?page=order_details.php&option=edit&orderid='.$rowview["order_id"].'"><button class="btn btn-info btn-grad"><i class="fa fa-pencil"></i> Edit</button></a> ';
												echo '<a onclick="return deletedata()" href="index.php?page=order_details.php&option=delete&orderid='.$rowview["order_id"].'"><button class="btn btn-danger btn-grad"><i class="fa fa-trash"></i> Delete</button></a> ';
												echo '</td>';
											*/}
											
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
		else if($_GET["option"]=="edit")
		{
			if($usertype=="Admin" || $usertype=="Clerk"  || $usertype=="Customer")
			{
				$editorderid=$_GET["orderid"];
				$sqledit="SELECT * FROM order_details WHERE order_id='$editorderid'";
				$resultedit=mysql_query($sqledit) or die("sql error in sqledit ,".mysql_error());
				$rowedit=mysql_fetch_assoc($resultedit);
				echo '<form name="order_details_add" id="order_details_add" action="" method="POST">';
				echo '<div class="row">
					<div class="col-lg-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								Order Details Edit
							</div>
							<div class="panel-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="dataTables-example">';
									echo '<tr><th>Order ID</th><td><input type="text" readonly value="'.$rowedit["order_id"].'" name="txt_order_id" id="txt_order_id" class="form-control"></td></tr>';
									echo '<tr><th>Product Name</th><td>
									<select required name="txt_product_id" id="txt_product_id" class="form-control">';
									$sqlloadproduct="SELECT product_id, product_name FROM product";
									$resultloadproduct=mysql_query($sqlloadproduct) or die("SQL error in sqlloadproduct ".mysql_error());
									while($rowloadproduct=mysql_fetch_assoc($resultloadproduct))
									{
										if($rowedit["product_id"]==$rowloadproduct["product_id"])
										{
											echo '<option selected value="'.$rowloadproduct["product_id"].'">'.$rowloadproduct["product_name"].'</option>';
										}
									}
									echo '</select>';
									echo '</td></tr>
									
									<tr><th>No of Product</th><td><input type="text" value="'.$rowedit["number_of_product"].'" onkeypress="return isNumberKey(event)" required name="txt_number_of_product" id="txt_number_of_product" class="form-control"></td></tr>
									<tr><td colspan="2"><center>
												<a href="index.php?page=order_details.php&option=view"><input class="btn btn-default btn-grad" type="button" name="btn_goback" id="btn_goback" value="Go Back"></a>
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
				header("location:index.php?page=order_details.php&option=view");
			}
		}
		else if($_GET["option"]=="delete")
		{
			if($usertype=="Admin" || $usertype=="Clerk"  || $usertype=="Customer")
			{
				$deleteorderid=$_GET["orderid"];
				$sqldelete="DELETE FROM order_details WHERE order_id='$deleteorderid'";
				$resultdelete=mysql_query($sqldelete) or die("sql error in sqldelete ,".mysql_error());
				if($resultdelete)
				{
					echo '<script>alert("Successfully Deleted!!!");
					window.location.href="index.php?page=order_details.php&option=view";</script>';
				}
			}
			else
			{
				header("location:index.php?page=order_details.php&option=view");
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