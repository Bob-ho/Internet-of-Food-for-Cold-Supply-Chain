<?php
include("config.php");
if(isset($_GET["frompage"]))
{
	if($_GET["frompage"]=="common")
	{
		$getproduct=$_GET["product"];
		$sqlproductdetails="SELECT * FROM products WHERE Index_no='$getproduct'";
		$resultproductdetails=mysql_query($sqlproductdetails) or die("sql error in sqlproductdetails ".mysql_error());
		$rowproductdetails=mysql_fetch_assoc($resultproductdetails);
		
		$sqlproductprice="SELECT price,commision FROM product_price WHERE product_id='$rowproductdetails[Index_no]' AND end_date='0000-00-00'";
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
		
		$Date_of_Storage=$rowproductdetails["Date_of_Storage_in_retailer"];
		if($rowproductdetails["Shelf_Life_in_months"]=="Temp Abuse")
		{
			$expiredate=$rowproductdetails["Shelf_Life_in_months"];
		}
		else
		{
			$expiredate1=explode(".",$rowproductdetails["Shelf_Life_in_months"]);
			$monthadd=$expiredate1[0];
			if(isset($expiredate1[1]))
			{
				$day=$expiredate1[1];
				$dayadd=(30*$day)/10;
			}
			else
			{
				$dayadd=0;
			}
			$startdate=strtotime($Date_of_Storage);
			$expiredate=date("Y-m-d", strtotime("+".$monthadd."months +".$dayadd." days",$startdate));
		}
		?>
		<div class="modal-dialog" role="document">
					<div class="modal-content modal-info">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>						
						</div>
						<div class="modal-body modal-spa">
								<div class="col-md-5 span-2">
											<div class="item">
												<img src="product_image/<?php echo $rowproductdetails["photo"]; ?>" class="img-responsive" alt="">
											</div>
								</div>
								<div class="col-md-7 span-1 ">
									<h3><?php echo $rowproductdetails["Food_Description"]; ?></h3>
									<!--<p class="in-para"> There are many variations of passages of Lorem Ipsum.</p>-->
									<div class="price_single">
									  <span class="reducedfrom "><!--<del>$2.00</del>-->$<?php echo $productprice; ?></span>
									
									 <div class="clearfix"></div>
									</div>
									<h4 class="quick">Quick Overview:</h4>
													<p >Exp. Date : <em class="item_price"><?php echo $expiredate; ?></em></p><br>
													<p >Temp : <em class="item_price"><?php echo $rowproductdetails["Temperature_Stored_in_Celcsius"]; ?></em></p><br>
													<p >Nutritional : 
													<em class="item_price">calories <?php echo $rowproductdetails["calories"]; ?>g, </em><br>
													<em class="item_price">cholestrol <?php echo $rowproductdetails["cholestrol_in_g"]; ?>g, </em><br>
													<em class="item_price">pottasium <?php echo $rowproductdetails["pottasium_in_g"]; ?>g, </em><br>
													<em class="item_price">protiens <?php echo $rowproductdetails["protiens_in_g"]; ?>g, </em><br>
													<em class="item_price">vitamin A <?php echo $rowproductdetails["vitamin_A_in_IU"]; ?>g, </em><br>
													<em class="item_price">vitamin C <?php echo $rowproductdetails["vitamin_C_in_g"]; ?>g, </em><br>
													<em class="item_price">Calcium <?php echo $rowproductdetails["Calcium_in_g"]; ?>g, </em><br>
													<em class="item_price">Iron <?php echo $rowproductdetails["Iron_in_g"]; ?>g</em><br>										
													</p>
									 <?php
									$numeroforder=0;
									if(isset($_SESSION["addtocardorderid"]))
									{
										$orderid=$_SESSION["addtocardorderid"];
										$sqlordercard="SELECT * FROM order_details WHERE order_id='$orderid' AND product_id='$rowproductdetails[Index_no]'";
										$resultordercard=mysql_query($sqlordercard) or die("SQL error in sqlordercard ".mysql_error());
										$n=mysql_num_rows($resultordercard);
										if($n==1)
										{
											$numeroforder=1;
										}
										else
										{
											$numeroforder=0;
										}														
									}
									else
									{
										$numeroforder=0;
									}
									if($numeroforder>0)
									{
									?>
										<div class="add-to">
										   <a href="index.php?page=checkout.php"><button class="btn btn-danger my-cart-btn my-cart-btn1 " data-image="images/of.png">Add to Cart</button></a>
										</div>
									<?php
									}
									else
									{
									?>
										<div class="add-to">
										   <button class="btn btn-danger my-cart-btn my-cart-btn1 " onclick="addcarddetails('<?php echo $rowproductdetails["Index_no"]; ?>')" data-image="images/of.png">Add to Cart</button>
										</div>
									<?php
									}
									?>
								</div>
								<div class="clearfix"> </div>
							</div>
						</div>
					</div>
		
		<?php
	}
}
?>