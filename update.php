<?php
$sql="SELECT * FROM products";
$result=mysql_query($sql);
while($row=mysql_fetch_assoc($result))
{
	$price=rand(400,500);
	$temp=rand(-50,50);
	$sqlupdate="INSERT INTO product_price(product_id, start_date, end_date, price, commision, temperature)
	VALUES('".$row["Index_no"]."','2019-05-20','2019-05-20','$price',0,'$temp')";
	$resultupdate=mysql_query($sqlupdate);
	
	$price=rand(300,400);
	$temp=rand(-30,50);
	$sqlupdate="INSERT INTO product_price(product_id, start_date, end_date, price, commision, temperature)
	VALUES('".$row["Index_no"]."','2019-05-21','2019-05-21','$price',0,'$temp')";
	$resultupdate=mysql_query($sqlupdate);
	
	$price=rand(200,300);
	$temp=rand(-20,50);
	$sqlupdate="INSERT INTO product_price(product_id, start_date, end_date, price, commision, temperature)
	VALUES('".$row["Index_no"]."','2019-05-22','2019-05-22','$price',0,'$temp')";
	$resultupdate=mysql_query($sqlupdate);
	
	$price=rand(200,300);
	$temp=rand(-20,50);
	$sqlupdate="INSERT INTO product_price(product_id, start_date, end_date, price, commision, temperature)
	VALUES('".$row["Index_no"]."','2019-05-23','0000-00-00','$price',0,'$temp')";
	$resultupdate=mysql_query($sqlupdate);
}
?>