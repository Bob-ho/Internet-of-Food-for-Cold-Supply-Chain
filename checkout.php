<?php
if(!isset($_SESSION))
{
	session_start();
}
if(isset($_SESSION["login_usertype"]))
{
	$usertype=$_SESSION["login_usertype"];
}
else
{
	$usertype="Guest";
}
include("config.php");
?>
<head>

	<!--//tags -->
	<link href="css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
	<link href="css/font-awesome.css" rel="stylesheet">
	<!--pop-up-box-->
	<link href="css/popuo-box.css" rel="stylesheet" type="text/css" media="all" />
	<!--//pop-up-box-->
	<!-- price range -->
	<link rel="stylesheet" type="text/css" href="css/jquery-ui1.css">
	<!-- fonts -->
	<link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800" rel="stylesheet">
</head>

<body>
<script>
function addcarddeletedata(p)//ask delete confirm
{
	var x=confirm("Are you sure do you want to delete this data?");
	if(x)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{
				window.location.href="index.php?page=checkout.php";
			}
		};
		xmlhttp.open("GET", "ajaxaddcard.php?frompage=deleteorder&productid=" + p, true);
		xmlhttp.send();
	}
	else
	{
		return false;	
	}
}

function addcardeditdata(p)
{
	var quantity=document.getElementById("checkoutquantity").value;
	var quantitymax=document.getElementById("checkoutquantity").max;
	if (+quantity>0 && +quantity<=+quantitymax)
	{
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() 
		{
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) 
			{
				window.location.href="index.php?page=checkout.php";
			}
		};
		xmlhttp.open("GET", "ajaxaddcard.php?frompage=editorder&productid=" + p+"&quantity="+quantity, true);
		xmlhttp.send();
	}
	else
	{
		var msg="Quantity No More Than "+ quantitymax;
		alert(msg);		
	}
}
</script>

<?php
if(isset($_SESSION["addtocardorderid"]))
{
	$orderid=$_SESSION["addtocardorderid"];
	$sqlordercard="SELECT * FROM order_details WHERE order_id='$orderid'";
	$resultordercard=mysql_query($sqlordercard) or die("SQL error in sqlordercard ".mysql_error());
	$n=mysql_num_rows($resultordercard);
						
?>
<body>
	<!-- checkout page -->
	<div class="privacy">
		<div class="container">
			<div class="spec ">
				<h3>Checkout</h3>
					<div class="ser-t">
						<b></b>
						<span><i></i></span>
						<b class="line"></b>
					</div>
			</div>
			<div class="checkout">
				<h4>Your shopping cart contains:
					<span><?php echo $n; ?> Products</span>
				</h4>
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th class="t-head">No.</th>
								<th class="t-head">Product</th>
								<th class="t-head">Product Name</th>
								<th class="t-head">Unit Price</th>
								<th class="t-head">Quantity</th>
								<th class="t-head">Sub Total</th>
								<th class="t-head">Remove</th>
							</tr>
						</thead>
						<tbody>
						
						<?php
						$x=1;
						$totalprice=0;
						while($rowordercard=mysql_fetch_assoc($resultordercard))
						{
							$productid=$rowordercard["product_id"];
							$sqlproduct="SELECT * FROM products WHERE Index_no='$productid'";
							$resultproduct=mysql_query($sqlproduct) or die("SQL error in sqlproduct ".mysql_error());
							$rowproduct=mysql_fetch_assoc($resultproduct);
							
							$sqlproductprice="SELECT price,commision FROM product_price WHERE product_id='$rowproduct[Index_no]' AND end_date='0000-00-00'";
							$resultproductprice=mysql_query($sqlproductprice) or die("SQL error in sqlproductprice ".mysql_error());
							$rowproductprice=mysql_fetch_assoc($resultproductprice);
							
							if($rowproductprice["commision"]>0)
							{
								$productprice=$rowproductprice["price"]-(($rowproductprice["price"]*$rowproductprice["commision"])/100);
							}
							else
							{
								$productprice=$rowproductprice["price"];
							}
						?>
							<tr class="rem1">
								<td class="invert"><?php echo $x; ?></td>
								<td class="invert-image">
									
										<img src="product_image/<?php echo $rowproduct["photo"]; ?>" alt=" " class="img-responsive">
									
								</td>
								<td class="invert">
									<?php echo $rowproduct["Food_Description"]; ?>
								</td>
								<td class="invert">								
									<?php echo $productprice; ?>									
								</td>
								<td class="invert">
								<input type="number" min="0" class="form-control" onkeypress="return isNumberKey(event)" onblur="addcardeditdata('<?php echo $productid; ?>')" name="checkoutquantity" id="checkoutquantity" readonly value="<?php echo $rowordercard["number_of_product"]; ?>">
								</td>
								<td class="invert">
									<?php echo $productprice*$rowordercard["number_of_product"];
										$totalprice=$totalprice+($productprice*$rowordercard["number_of_product"]);
									?>
								</td>
								<td class="invert">
									<a onclick="return addcarddeletedata('<?php echo $productid; ?>')" ><button class="btn btn-danger btn-grad"><i class="fa fa-trash"></i> Delete</button></a>
								</td>
							</tr>
						<?php
						$x++;
						}
						?>						
							<tr><td class="invert"><?php echo $x; ?></td>
							<td class="invert">Total</td>
							<td class="invert"></td><td class="invert"></td><td class="invert"></td>
							<td class="invert"><?php echo $totalprice; 
							$_SESSION["addtocardorderid_totalprice"]=$totalprice;
							?></td>
							<td class="invert"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="checkout-left">
				<div class="address_form_agile">					
					<div class="checkout-right-basket">
					<?php
					if($n>0)
					{
						if(isset($_SESSION["usertype"]))
						{
							echo '<a class=" add-1" href="index.php?page=bill.php&option=new">Make a Payment
								<span class="fa fa-hand-o-right" aria-hidden="true"></span>
							</a> ';
						}
						else
						{
							echo '<a class=" add-1" href="index.php?page=login.php">Make a Payment
								<span class="fa fa-hand-o-right" aria-hidden="true"></span>
							</a> ';
						}
					}
					?>
						
					&nbsp;
						<a href="index.php" class=" add-1">Buy Another Product
							<span class="fa fa-hand-o-right" aria-hidden="true"></span>
						</a>
					</div>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
	
	
</body>

</html>

<?php
}
else
{
	header("location:index.php");
}
?>