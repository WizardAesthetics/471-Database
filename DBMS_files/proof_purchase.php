<!-- Blake Johnson & Nicholas Christian -->
<!DOCTYPE HTML>
<?php
	session_start();
?>
<head>
	<title>Proof purchase</title>
	<header align="center">Proof purchase</header>
</head>

<body>
	<?php
		$pdo = new PDO('sqlite:bbb.db');
		$statement = $pdo->query("INSERT INTO Orders (DateTime, Total_Amount, Username) 
		VALUES (date('now'),
		".$_SESSION['subtotal'][0] .",
		'".$_SESSION['user'] ."');");
	?>

	<table align="center" style="border:2px solid blue;">
		<form id="buy" action="" method="post">
			<tr>
				<td>
					Shipping Address:
				</td>
			</tr>
			<td colspan="2">
				<?php
				$pdo = new PDO('sqlite:bbb.db');
				$statement = $pdo->query("SELECT Fname, Lname FROM User WHERE Username = '" . $_SESSION['user'] . "'");
				$row = $statement->fetchAll(PDO::FETCH_ASSOC);
				print $row[0]['Fname'] . ' ' . $row[0]['Lname'];
				?>
			</td>
			<td rowspan="3" colspan="2">
				<?php
					date_default_timezone_set("America/New_York");
					$pdo = new PDO('sqlite:bbb.db');
					if ($_POST['cardgroup'] == 'new_card' && isset($_POST['credit_card']) && isset($_POST['card_number']) && isset($_POST['card_expiration'])){
						$statement = $pdo->query("UPDATE User
						SET Card_Type = '".$_POST['credit_card']."', Card_Num = '".$_POST['card_number']."',  Expiration = '".$_POST['card_expiration']."'
						WHERE Username = '" . $_SESSION['user'] . "'");
					}
					$statement = $pdo->query("SELECT * FROM User WHERE Username = '" . $_SESSION['user'] . "'");
					$row = $statement->fetchAll(PDO::FETCH_ASSOC);

					print '<b>UserID:</b>'. $row[0]['Username'].'<br />';
					print '<b>Date:</b>'.date("m-d-Y").'<br />';
					print '<b>Time:</b>'.date("h:ia").'<br />';

					print '<b>Card Info:</b>'.$row[0]['Card_Type'].'<br />'.$row[0]['Card_Num'].' - '.$row[0]['Expiration'].'<br />';
				?>
			</td>
			<tr>
				<td colspan="2">
					<?php
					$pdo = new PDO('sqlite:bbb.db');
					$statement = $pdo->query("SELECT Street_Adress FROM User WHERE Username = '" . $_SESSION['user'] . "'");
					$row = $statement->fetchAll(PDO::FETCH_ASSOC);
					print $row[0]['Street_Adress'];
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php
					$pdo = new PDO('sqlite:bbb.db');
					$statement = $pdo->query("SELECT City FROM User WHERE Username = '" . $_SESSION['user'] . "'");
					$row = $statement->fetchAll(PDO::FETCH_ASSOC);
					print $row[0]['City'];
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php
					$pdo = new PDO('sqlite:bbb.db');
					$statement = $pdo->query("SELECT State, zip FROM User WHERE Username = '" . $_SESSION['user'] . "'");
					$row = $statement->fetchAll(PDO::FETCH_ASSOC);
					print $row[0]['State'] . ', ' . $row[0]['zip'];
					?>
				</td>
			</tr>
			<tr>
			<td colspan="3" align="center">
					<div id="bookdetails" style="overflow:scroll;height:180px;width:520px;border:1px solid black;">
						<table border='1'>
							<th>Book Description</th>
							<th>Qty</th>
							<th>Price</th>
							<?php
								try {
									$pdo = new PDO('sqlite:bbb.db');
									$subtotal = 0;
									$tableIndex = 0;
									foreach ($_SESSION['cart'] as $cart) {
										//query cart and get books from database
										$statement = $pdo->query("SELECT ISBN, Title, Author, Publisher, Price, Inventory FROM Books WHERE ISBN = '$cart'");
										$row = $statement->fetchAll(PDO::FETCH_ASSOC);

										//print table
										print "<tr id='table_row'><td>" . $row[0]['Title'] . "<br><b>By: </b>" .$row[0]['Author'] . "<br><b>Publisher: </b>" . $row[0]['Publisher'] . "</td>";
										print "<td>". $_SESSION['quantArr'][0][$tableIndex] . "</td><td>$" . $row[0]['Price'] . "</td></tr>";

										$tableIndex++;
									}
								} catch (PDOException $Exception) {
									die($Exception->getMessage());
								}
							?>
						</table>
					</div>
				</td>
			</tr>
			<tr>
				<td align="left" colspan="2">
					<div id="bookdetails" style="overflow:scroll;height:180px;width:260px;border:1px solid black;background-color:LightBlue">
						<b>Shipping Note:</b> The book will be </br>delivered within 5</br>business days.
					</div>
				</td>
				<td align="right">
					<div id="bookdetails" style="overflow:scroll;height:180px;width:260px;border:1px solid black;">
						<?php	
							print 'SubTotal:$'.$_SESSION['subtotal'][0].'</br>Shipping_Handling:$2</br>_______</br>Total:$'.($_SESSION['subtotal'][0] + 2);
						?>
					</div>
				</td>
			</tr>
			<tr>
				<td align="right">
					<input type="submit" id="buyit" name="btnbuyit" value="Print" disabled>
				</td>
		</form>
		<td align="right">
			<form id="update" action="screen2.php" method="post">
				<input type="submit" id="update_customerprofile" name="update_customerprofile" value="New Search">
			</form>
		</td>
		<td align="left">
			<form id="cancel" action="index.php" method="post">
				<input type="submit" id="exit" name="exit" value="EXIT 3-B.com">
			</form>
		</td>
		</tr>
	</table>
	<?php
		$_SESSION['cart'] = array();
	?>
</body>

</HTML>