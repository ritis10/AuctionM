<?php
	session_start();
	$name=$_SESSION['Name'];
	echo "<title> Product Deletion </title>";
	$db=mysqli_connect('localhost','root','','auction') or die("connection failed");
  $pid=$_POST['Delete'];
  $_SESSION['Product_to_delete']=$pid;
?>
<html>
  <head>
  	<style type="text/css">
  		<style>
      *{
        margin:4px;
      }
      body{
      	margin:70px;
        font-family:sans-serif;
        background-color: powderblue;
      }
      table{
        border-collapse: collapse;
      }
      tr,td,th{
        border-style:solid;
      }
      input, button, textarea{
        background: #2196F3;
        border: none;
        left: 0;
        color: #fff;
        bottom: 0;
        border: 0px solid rgba(0, 0, 0, 0.1);
        border-radius:5px;
        transform: rotateZ(0deg);
        transition: all 0.1s ease-out;
      }
      ul{
    	list-style-type: none;
   	 	margin: 0;
  	  	padding: 0;
    	overflow: hidden;
    	background-color: #333;
    	position: fixed;
    	top: 0;
    	width: 100%;
	  }

	li{
    	float: left;
	}

    li a {
    display: block;
    color: white;
    text-align: center;
    padding: 14px 16px;
    text-decoration: none;
 }

   li a:hover:not(.active) {
    background-color: #111;
 }

   .active {
    background-color: #4CAF50;
 }
  	</style>
  </head>
  <body>
  	<ul>
        <li><a href="Seller_portal.php">Add Product</a></li>
        <li><a href="Seller_orders.php">My Orders</a></li>
        <li><a class="active" href="myproducts.php">My Products</a></li>
        <li><a href="index.php">Logout</a><li>
    </ul>
  	<form method="POST" action="landing_page.php">
     <center> <h3> Διαγραφή Προιόντος </h3><center>
      <table>
        <tr>
          <th>Product Name</th>
          <th>Minimum Bid</th>
          <th>Maximum Bid</th>
          <th>Current Bid</th>
          <th>Description</th>
          <th>Stock</th>
          <th>Time Left</th>

        </tr>
        <?php
				$query="SELECT * FROM product inner JOIN auction_types on a_type_id=type
																		  inner JOIN users on id=owner
																		  inner Join fin_del_product on prod_status_id=finished
																		  where username='$name'
																		  and productId=$pid;";
        //mysqli_query($db,$query);
        $result=mysqli_query($db,$query);
        while($row=mysqli_fetch_array($result)){
					echo '<tr>';
					echo '<td>'.$row['productName'].'</td>';
					echo '<td>'.$row['minbid'].'€</td>';
					if($row['currBid']==0)
						echo '<td>'.$row['minbid'].'€</td>';
					else
						echo '<td>'.$row['currBid'].'€</td>';
					echo '<td>'.$row['price_step'].'€</td>';
					echo '<td>'.$row['descp'].'</td>';
					echo '<td>'.$row['a_type_descr'].'</td>';
					echo '<td>'.$row['first_name']. ' ' .$row['last_name'].'</td>';
					if($row['extensions']==1)
						echo '<td>NAI</td>';
					else
						echo '<td>OXI</td>';
						$d1=date_create($row['expiry']);
						$d1->modify("+5 hours");
						$d2=date_create(date("Y-m-d H:i:s"));
						//$d2=strtotime($d2);
						$diff=date_diff($d2,$d1);
						if($diff->format("%R%a")<0)
						{
										echo '<td>Expired</td>';
										$isSaveDisabled = true;
						}
						elseif($d1<$d2)
						{
								if($row['extensions']==1)
								{
												$last="SELECT currBid as \"lastBid\" from product WHERE currBid > (NOW() - INTERVAL 30 MINUTE)";
												$last=mysqli_query($db,$last) or die("query failed");
												$last=mysqli_fetch_array($last);
												if($last == true)
												{
														$d1->modify("+15 minutes");
														mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
														$update="UPDATE product SET Num_of_Extensions = Num_of_Extensions-1";
														$result=mysqli_query($db,$update) or die('Could not update');
												}
								}
								else{
										echo '<td>Expired</td>';
										$isSaveDisabled = true;
								}
						}
						elseif($diff->format("%R%h")>4)
						{
							echo '<td>Last 4 Hours</td>';
						}
						elseif($diff->format("%R%h")>3)
						{
							echo '<td>Last 3 Hours</td>';
						}
						elseif($diff->format("%R%h")>2)
						{
							echo '<td>Last 2 Hours</td>';
						}
						elseif($diff->format("%R%h")>1)
						{
							echo '<td>Last Hour</td>';
						}
						elseif($diff->format("%R%i")>30)
						{
							echo '<td>Last 30 Minutes</td>';
						}
						elseif($diff->format("%R%i")>20)
						{
							echo '<td>Last 20 Minutes</td>';
						}
						elseif($diff->format("%R%i")>10)
						{
							echo '<td>Last 10 Minutes</td>';
						}
						elseif($diff->format("%R%i")>5)
						{
							echo '<td>Last 5 Minutes</td>';
						}
						elseif($diff->format("%R%i")>4)
						{
							echo '<td>Last 4 Minutes</td>';
						}
						elseif($diff->format("%R%i")>3)
						{
							echo '<td>Last 3 Minutes</td>';
						}
						elseif($diff->format("%R%i")>2)
						{
							echo '<td>Last 2 Minutes</td>';
						}
						elseif($diff->format("%R%i")>1)
						{
							echo '<td>Last Minute</td>';
						}

						echo '<td>'.$row['Num_of_Extensions'].'</td>';

						echo '<td>'.$row['Time_of_Extensions'].'</td>';

						echo '<td>'.$row['crucial_time'].'</td>';
						echo '<td>'.$row['prod_status'].'</td>';
						echo '</tr>';}
        echo '</table>';
        mysqli_close($db);
        ?>
        <button type='submit' name='submit' value='5'>Delete</button>
 </body>
</html>
