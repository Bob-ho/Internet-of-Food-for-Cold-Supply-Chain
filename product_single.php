<?php
include("config.php");

$x=1;
$product=$_GET["product"];
	$sqlproductdetails="SELECT * FROM products WHERE Index_no='$product'";
	$resultproductdetails=mysql_query($sqlproductdetails) or die("sql error in sqlproductdetails ".mysql_error());
	$rowproductdetails=mysql_fetch_assoc($resultproductdetails);
		$sqlcheck="SELECT * FROM order_details WHERE product_id='$rowproductdetails[Index_no]'";
		$resultcheck=mysql_query($sqlcheck) or die("sql error in sqlcheck ".mysql_error());
		
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
$dataarray=array();
$x=0;
$sqlproductprice1="SELECT * FROM product_price WHERE product_id='$product'";
$resultproductprice1=mysql_query($sqlproductprice1) or die("SQL error in sqlproductprice1 ".mysql_error());
while($rowproductprice1=mysql_fetch_assoc($resultproductprice1))
{
	$startdate=$rowproductprice1["start_date"];
	if($rowproductprice1["end_date"]=="0000-00-00")
	{
		$enddate=date("Y-m-d");
	}
	else
	{
		$enddate=$rowproductprice1["end_date"];
	}
	while($startdate<=$enddate)
	{
		if($rowproductprice1["commision"]>0)
		{
			$productprice1=$rowproductprice1["price"]-(($rowproductprice1["price"]*$rowproductprice1["commision"])/100);
		}
		else
		{
			$productprice1=$rowproductprice1["price"];
		}
		$temperature=$rowproductprice1["temperature"];
		$arrayyear=date("Y",strtotime($startdate));
		$arraymonth=date("m",strtotime($startdate));
		$arrayday=date("d",strtotime($startdate));
		$dataarray[$x]="[new Date(".$arrayyear.", ".($arraymonth-1).", ".$arrayday."),  ".$temperature.",  ".$productprice1."]";
		$startdate=date("Y-m-d", strtotime("+1 days",strtotime($startdate)));
		$x++;
	}
}
$displayarray="";
for($y=0;$y<count($dataarray);$y++)
{
	if($y==(count($dataarray)-1))
	{
		$displayarray=$displayarray.$dataarray[$y];
	}
	else
	{
		$displayarray=$displayarray.$dataarray[$y].",";
	}
}
?>	
<script>
  google.charts.load('current', {'packages':['line', 'corechart']});
      google.charts.setOnLoadCallback(drawChart);

    function drawChart() {

      var button = document.getElementById('change-chart');
      var chartDiv = document.getElementById('chart_div');

      var data = new google.visualization.DataTable();
      data.addColumn('date', 'Month');
      data.addColumn('number', "Average Temperature");
      data.addColumn('number', "Average Price");

      data.addRows([
        <?php echo $displayarray; ?>
      ]);

      var materialOptions = {
        chart: {
          title: 'Average Temperatures and Price for product Throughout the date'
        },
        width: 900,
        height: 500,
        series: {
          // Gives each series an axis name that matches the Y-axis below.
          0: {axis: 'Temps'},
          1: {axis: 'Price'}
        },
        axes: {
          // Adds labels to each axis; they don't have to match the axis names.
          y: {
            Temps: {label: 'Temps (Celsius)'},
            Price: {label: 'Price'}
          }
        }
      };

      var classicOptions = {
        title: 'Average Temperatures and Daylight in Iceland Throughout the Year',
        width: 900,
        height: 500,
        // Gives each series an axis that matches the vAxes number below.
        series: {
          0: {targetAxisIndex: 0},
          1: {targetAxisIndex: 1}
        },
        vAxes: {
          // Adds titles to each axis.
          0: {title: 'Temps (Celsius)'},
          1: {title: 'Daylight'}
        },
        hAxis: {
          ticks: [new Date(2014, 0), new Date(2014, 1), new Date(2014, 2), new Date(2014, 3),
                  new Date(2014, 4),  new Date(2014, 5), new Date(2014, 6), new Date(2014, 7),
                  new Date(2014, 8), new Date(2014, 9), new Date(2014, 10), new Date(2014, 11)
                 ]
        },
        vAxis: {
          viewWindow: {
            max: 30
          }
        }
      };

      function drawMaterialChart() {
        var materialChart = new google.charts.Line(chartDiv);
        materialChart.draw(data, materialOptions);
        button.innerText = 'Change to Classic';
        button.onclick = drawClassicChart;
      }

      function drawClassicChart() {
        var classicChart = new google.visualization.LineChart(chartDiv);
        classicChart.draw(data, classicOptions);
        button.innerText = 'Change to Material';
        button.onclick = drawMaterialChart;
      }

      drawMaterialChart();

    }
</script>	
<body>


		<div class="single">
			<div class="container">
						<div class="single-top-main">
	   		<div class="col-md-5 single-top">
	   		<div class="single-w3agile">
							
<div id="picture-frame">
			<img src="product_image/<?php echo $rowproductdetails["photo"]; ?>" data-src="product_image/<?php echo $rowproductdetails["photo"]; ?>" alt="" class="img-responsive"/>
		</div>
										<script src="js/jquery.zoomtoo.js"></script>
								<script>
			$(function() {
				$("#picture-frame").zoomToo({
					magnify: 1
				});
			});
		</script>
		
		
		
			</div>
			</div>
			<div class="col-md-7 single-top-left ">
								<div class="single-right">
				<h3><?php echo $rowproductdetails["Food_Description"]; ?></h3>
				
					
				 <div class="pr-single">
				  <p class="reduced ">$<?php echo $productprice; ?></p>
				</div>
				<div class="block block-w3">
					<div class="starbox small ghosting"> </div>
				</div>
				<br>
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
													<em class="item_price">Iron <?php echo $rowproductdetails["Iron_in_g"]; ?>g</em>	<br>												
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
				
				 
			   
			<div class="clearfix"> </div>
			</div>
		 

			</div>
		   <div class="clearfix"> </div>
	   </div>	
				 
				<div id="chart_div"></div>
	</div>
	
</div>
</body>
</html>