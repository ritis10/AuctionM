<?php
	session_start();
  if($_SESSION["logged"]!="buyer")
    header("location: index.php");
	$name=$_SESSION['Name'];
	echo "<title> Welcome $name </title>";
	$db=mysqli_connect('localhost','root','','auction') or die("connection failed");
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
        background-color: lightgreen;
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
  			<li><a class="active" href="Listings.php">Search Products</a></li>
  			<li><a href="userOrders.php">My Orders</a></li>
        <li><a href="index.php">Logout</a><li>
		</ul>
  	<form method="POST" action="newOrder.php">
      <table>
        <tr>
          <th>Όνομα Προϊόντος ή Υπηρεσίας</th>
          <th>Τιμή Εκκίνησης</th>
          <th>Τωρινή Τιμή</th>
          <th>Λεπτομέρειες</th>
          <th>Τύπος Δημοπρασίας</th>
          <th>Πωλητής</th>
          <th>Επιτρέπονται οι Παρατασεις</th>
		  <th>Ώρα που απομένει</th>
		  <th>Αριθμός επιτρεπόμενων επεκτάσεων</th>
          <th>Χρόνος μιας επέκτασης</th>
		  <th>crucial time</th>
				</tr>
				<p id="demo"></p>

				<script>
				// Set the date we're counting down to
				var countDownDate = new Date("01, 19, 2021, 21:37:25").getTime();
				// Update the count down every 1 second
				var x = setInterval(function() {

  			// Get today's date and time
  			var now = new Date().getTime();
  			// Find the distance between now and the count down date
  			var distance = countDownDate - now;
  			// Time calculations for days, hours, minutes and seconds
  			var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  			var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  			var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  			var seconds = Math.floor((distance % (1000 * 60)) / 1000);
  			// Output the result in an element with id="demo"
  			document.getElementById("demo").innerHTML = hours + "h "
  			+ minutes + "m " + seconds + "s ";
  			// If the count down is over, write some text
  			if (distance < 0) {
    			clearInterval(x);
    			document.getElementById("demo").innerHTML = "ΕΛΗΞΕ";
  				}
				}, 1000);
				</script>

        <?php
        $query="SELECT * FROM product;";
        mysqli_query($db,$query);
        $result=mysqli_query($db,$query);
        while($row=mysqli_fetch_array($result)){
          echo '<tr>';
          echo '<td>'.$row['productName'].'</td>';
          echo '<td>'.$row['minbid'].'</td>';

          if($row['currBid']==0)
          	echo '<td>'.$row['minbid'].'</td>';
          else
          	echo '<td>'.$row['currBid'].'</td>';

          echo '<td>'.$row['descp'].'</td>';

          if($row['type']==0)
          	echo '<td>ΠΛΕΙΟΔΟΤΙΚΗ</td>';
          else
          	echo '<td>ΜΕΙΟΔΟΤΙΚΗ</td>';

          echo '<td>'.$row['owner'].'</td>';

					if($row['extensions']==1)
						echo '<td>NAI</td>';
					else
						echo '<td>OXI</td>';
		  echo '<td>'.$row['Num_of_Extensions'].'</td>';
		  echo '<td>'.$row['Time_of_Extensions'].'</td>';
		  echo '<td>'.$row['crucial_time'].'</td>';

						//$d1=date_create($row['expiry']);
						//$result = $d1->format('Y-m-d H:i:s');
	         // $new_time = date("Y-m-d H:i:s", strtotime('+5 hours', strtotime($result)));
	          //$diff=date_diff($new_time,$d1);
			 // if($diff->format("%R%a")<0){
	          	// '<td>Expired<td>';
	          	//$row['productId']=-1;
	          //}
	         // else if($diff->format("%R%a")==0)
//echo '<td>Last Day<td>';
//else
	          	// '<td>'.$diff->format("%a").' days left<td>';
	          //$_SESSION['timeleft']=$diff->format("%a");

						echo '<td>'.$row['expiry'].'</td>';

          echo "<td> <button type='submit' name='NewBid' value=".$row['productId'].">Bid</button></td>";
          echo '</tr>';
        }
        echo '</table>';
        mysqli_close($db);
        ?>
      </form>
 </body>
</html>
