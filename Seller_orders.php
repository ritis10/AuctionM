<?php
	session_start();
    if($_SESSION["logged"]!="seller")
    header("location: index.php");
	$name=$_SESSION['Name'];
	echo "<title> Welcome $name </title>";
  $db=mysqli_connect('localhost','root','','auction');
?>
<html>
  <head>
  	<style type="text/css">
  		<style>
      *{
        margin:4px;
      }
      body{
      	margin: 70px;
        font-family:sans-serif;
        background-color: powderblue;
      }
      table{
        border-collapse: collapse;
      }
      tr,td,th{
        border-style:solid;
      }
      input, button{
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
			<li><a href="Seller_portal.php">Πρόσθεσε προίόν</a></li>
			<li><a class="active" href="Seller_orders.php">Οι παραγγελίες για τα προϊόντα μου</a></li>
			<li><a href="productstofin.php">Οριστικοποίηση πλειστηριασμού</a></li>
			<li><a href="MyProducts.php">Διαγραφή πλειστηριασμού</a></li>
			<li><a href="index.php">Αποσύνδεση</a><li>
		</ul>
        <fieldset>
        <form method="post" action="Seller_orders.php">
        <select name='filter'>
         <option  value="ALL">όλες οι παραγγελίες</option>
         <option  value="Sat">Σε ισχύ</option>
         <option  value="UnSat">Ακυρώθηκαν</option>
        </select>
        <input type="Submit" value='filter'>
        </form>
       </fieldset>
      <fieldset>
 			<form name='myorders' method="POST" action="" >
        <table>
        <tr>
					<th>Κωδικός Παραγγελίας</th>
          <th>Προϊόν</th>
          <th>Πωλητής</th>
          <th>Τωρινή Προσφορά</th>
					<th>Πελάτης</th>
					<th>Χρόνος παραγγελίας</th>
          <th>Διεύθυνση Πελάτη</th>
          <th>Κατάσταση Παραγγελίας</th>
        </tr>
        <?php
        $filter="";
        if(isset($_POST['filter']))
          $filter=$_POST['filter'];

        if($filter=="ALL" || !isset($_POST['filter']))
				  $query="SELECT * FROM orders inner Join users on WhoDoes=id
																		   inner Join product on productId=auctionId
																		   WHERE SellerUsr='$name';";

        else if($filter=="MY")
          $query="SELECT * FROM product where owner='$name';";

      else if($filter=="Sat"){
        $query="SELECT * FROM orders inner Join users on WhoDoes=id
																		 inner Join product on productId=auctionId
																		 WHERE SellerUsr='$name'
																		 and status_del=1;";
      }

      else if($filter=="UnSat")
			$query="SELECT * FROM orders inner Join users on WhoDoes=id
																	 inner Join product on productId=auctionId
																	 WHERE SellerUsr='$name'
																	 and status_del=0;";

        mysqli_query($db,$query) or die("Query Failed");
        $result=mysqli_query($db,$query);
        while($row=mysqli_fetch_array($result)){
          echo '<tr>';
          echo '<td>'.$row['OrderId'].'</td>';
					echo '<td>'.$row['productName'].'</td>';
          echo '<td>'.$row['SellerUsr'].'</td>';
          echo '<td>'.$row['Amount'].'</td>';
					echo '<td>'.$row['first_name'].' '.$row['last_name'].'</td>';
					echo '<td>'.$row['when_'].'</td>';
					echo '<td>'.$row['Address'].'</td>';
          if ($row['status_del']==1)
            echo '<td> Σε ισχύ </td>';
          else
            echo '<td> Ακυρώθηκε </td>';
          //echo "<td> <button type='submit' name='Final' value=".$row['OrderId'].">Finalize</button></td>";
          echo '</tr>';
        }
        echo '</table>';
        mysqli_close($db);
        ?>
      </form>
    </fieldset>
  	</body>
</html>
