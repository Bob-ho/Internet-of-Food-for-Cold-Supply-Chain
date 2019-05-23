<?php
include("config.php");
?>
						<div class=" con-w3l">
							<?php
							$x=1;
							$product=$_GET["product"];
							$sqlproductname="SELECT DISTINCT Food_Description FROM products WHERE Food_Description='$product'";
							$resultproductname=mysql_query($sqlproductname) or die("sql error in sqlproductname ".mysql_error());
							while($rowproductname=mysql_fetch_assoc($resultproductname))
							{
								echo $rowproductname["Food_Description"];
								$sqlproductdetails="SELECT * FROM products WHERE Food_Description='$rowproductname[Food_Description]'";
								$resultproductdetails=mysql_query($sqlproductdetails) or die("sql error in sqlproductdetails ".mysql_error());
								while($rowproductdetails=mysql_fetch_assoc($resultproductdetails))
								{
									$sqlcheck="SELECT * FROM order_details WHERE product_id='$rowproductdetails[Index_no]'";
									$resultcheck=mysql_query($sqlcheck) or die("sql error in sqlcheck ".mysql_error());
									if(mysql_num_rows($resultcheck)==0)
									{
									$sqlshopname="SELECT * FROM shop WHERE shop_id='$rowproductdetails[shop_id]'";
									$resultshopname=mysql_query($sqlshopname) or die("sql error in sqlshopname ".mysql_error());
									$rowshopname=mysql_fetch_assoc($resultshopname);
									
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
									<div class="col-md-3 m-wthree">
										<div class="col-m">								
											<a href="#" data-toggle="modal" onclick="moredetails('<?php echo $rowproductdetails["Index_no"]; ?>')" data-target="#myModal1" class="offer-img">
												<img src="product_image/<?php echo $rowproductdetails["photo"]; ?>" class="img-responsive" alt="">
												<div class="offer"><p><span><?php echo $rowshopname["name"]; ?></span></p></div>
											</a>
											<div class="mid-1">
												<div class="women">
													<h6><a href="#"><?php echo $rowproductdetails["Food_Description"]; ?></a></h6>	
												</div>
												<div class="mid-2">
													<!--<p ><label>$2.00</label><em class="item_price">$1.50</em></p>-->
													<p >Price : <em class="item_price">$<?php echo $productprice; ?></em></p><br>
													<p >Exp. Date : <em class="item_price"><?php echo $expiredate; ?></em></p><br>
													<p >Temp : <em class="item_price"><?php echo $rowproductdetails["Temperature_Stored_in_Celcsius"]; ?></em></p><br>
													<p >Nutritional : 
													<em class="item_price">calories <?php echo $rowproductdetails["calories"]; ?>g, </em>
													<em class="item_price">cholestrol <?php echo $rowproductdetails["cholestrol_in_g"]; ?>g, </em>
													<em class="item_price">pottasium <?php echo $rowproductdetails["pottasium_in_g"]; ?>g, </em>
													<em class="item_price">protiens <?php echo $rowproductdetails["protiens_in_g"]; ?>g, </em>
													<em class="item_price">vitamin A <?php echo $rowproductdetails["vitamin_A_in_IU"]; ?>g, </em>
													<em class="item_price">vitamin C <?php echo $rowproductdetails["vitamin_C_in_g"]; ?>g, </em>
													<em class="item_price">Calcium <?php echo $rowproductdetails["Calcium_in_g"]; ?>g, </em>
													<em class="item_price">Iron <?php echo $rowproductdetails["Iron_in_g"]; ?>g</em>													
													</p>
													  <div class="block">
														<div class="starbox small ghosting"> </div>
													</div>
													<div class="clearfix"></div>
												</div>
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
													<div class="add">
													   <a href="index.php?page=checkout.php"><button class="btn btn-danger my-cart-btn my-cart-b " data-image="images/of.png">Add to Cart</button></a>
													</div>
												<?php
												}
												else
												{
												?>
													<div class="add">
													   <button class="btn btn-danger my-cart-btn my-cart-b " onclick="addcarddetails('<?php echo $rowproductdetails["Index_no"]; ?>')" data-image="images/of.png">Add to Cart</button>
													</div>
												<?php
												}
												?>
												<div class="add">
												   <a href="index.php?page=product_single.php&product=<?php echo $rowproductdetails["Index_no"]; ?>"><button class="btn btn-danger my-cart-btn my-cart-b " data-image="images/of.png">More Details</button></a>
												</div>
											</div>
										</div>
									</div>	
							<?php
								$x++;
									}
								}
							}
							?>
							<div class="clearfix"></div>
						 </div>