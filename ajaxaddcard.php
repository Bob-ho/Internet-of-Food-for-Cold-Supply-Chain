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
if(isset($_GET["frompage"]))
{
	if($_GET["frompage"]=="saveorder")
	{
		$productid=$_GET["productid"];
		
		if(isset($_SESSION["addtocardorderid"]))
		{
			$orderid=$_SESSION["addtocardorderid"];
		}
		else
		{
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
		}
		
		$sqlproductprice="SELECT price,commision FROM product_price WHERE product_id='$productid' AND end_date='0000-00-00'";
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
		$sqlinsert="INSERT INTO order_details(order_id,product_id,number_of_product,price)
					VALUES('".mysql_real_escape_string($orderid)."',
						'".mysql_real_escape_string($productid)."',
						'".mysql_real_escape_string(1)."',
						'".mysql_real_escape_string($productprice)."')";
		$resultinsert=mysql_query($sqlinsert) or die("sql error in sqlinsert ".mysql_error());
		if($resultinsert)
		{
			if(!isset($_SESSION["addtocardorderid"]))
			{
				$_SESSION["addtocardorderid"]=$orderid;
			}
		}
	}
	else if($_GET["frompage"]=="deleteorder")
	{
		$productid=$_GET["productid"];
		$orderid=$_SESSION["addtocardorderid"];
		$sqldelete="DELETE FROM order_details 
				WHERE order_id='".mysql_real_escape_string($orderid)."' 
				AND product_id='".mysql_real_escape_string($productid)."'";
		$resultdelete=mysql_query($sqldelete) or die("sql error in sqldelete ".mysql_error());
	}
	else if($_GET["frompage"]=="editorder")
	{
		$productid=$_GET["productid"];
		$quantity=$_GET["quantity"];
		$orderid=$_SESSION["addtocardorderid"];
		$sqlupdate="UPDATE order_details SET number_of_product='".mysql_real_escape_string($quantity)."'
				WHERE order_id='".mysql_real_escape_string($orderid)."' 
				AND product_id='".mysql_real_escape_string($productid)."'";
		$resultupdate=mysql_query($sqlupdate) or die("sql error in sqlupdate ".mysql_error());
	}
}
?>