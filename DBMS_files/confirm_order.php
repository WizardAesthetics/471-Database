<!-- Blake Johnson & Nicholas Christian -->
<!DOCTYPE HTML>
<?php
	session_start();
	$_SESSION['subtotal'] = array();
?>

<head>
	<title>CONFIRM ORDER</title>
	<header align="center">Confirm Order</header>
</head>

<body>
	<table align="center" style="border:2px solid blue;">
		<form id="buy" action="proof_purchase.php" method="post">
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
				$pdo = new PDO('sqlite:bbb.db');
				$statement = $pdo->query("SELECT * FROM User WHERE Username = '" . $_SESSION['user'] . "'");
				$row = $statement->fetchAll(PDO::FETCH_ASSOC);
				if(count($row)>0)
					print '<input type="radio" name="cardgroup" value="profile_card" checked>Use Credit card on file<br />' . $row[0]['Card_Type'] . ' - ' . $row[0]['Card_Num'] . ' - ' . $row[0]['Expiration'] . '<br />';
				?>
				<input type="radio" name="cardgroup" value="new_card">New Credit Card<br />
				<select id="credit_card" name="credit_card">
					<option selected disabled>select a card type</option>
					<option>VISA</option>
					<option>MASTER</option>
					<option>DISCOVER</option>
				</select>
				<input type="text" id="card_number" name="card_number" placeholder="Credit card number">
				<br />Exp date<input type="text" id="card_expiration" name="card_expiration" placeholder="mm/yyyy">
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
									$tableArr = array();
									$tableIndex = 0;
									$inventArr = array();
									$quantArr = array();

									//check if cart is empty
									if (empty($_SESSION['cart'])) {
										?> <script> alert("Your cart is empty.") </script> <?php
									} else {

										//if quantity change has occurred, set new quantities
										if(isset($_SESSION['quantArr'])){
											for ($i = 0; $i <= count($_SESSION['cart']); $i++) {
												$quantArr[$i] = $_SESSION['quantArr'][0][$i];
											}
										} else {
											//else if no quantity change has occurred, set quantity to 1
											for ($i = 0; $i <= count($_SESSION['cart']); $i++) {
												$quantArr[$i] = 1;
											}
										}

										foreach($_SESSION['cart'] as $cart) {
											//query cart and get books from database
											$statement = $pdo->query("SELECT ISBN, Title, Author, Publisher, Price, Inventory
											FROM Books
											WHERE ISBN = '$cart'");
											$row = $statement->fetchAll(PDO::FETCH_ASSOC);

											//add returned row to local array
											array_push($tableArr, $row[0]);
											
											//calculat subtotal
											$subtotal += intval($tableArr[$tableIndex]['Price']) * intval($quantArr[$tableIndex]);

											//store inventory into array
											array_push($inventArr, $tableArr[$tableIndex]['Inventory']);
											
											//print table
											print "<tr id='table_row'><td>" . $row[0]['Title'] . "<br><b>By: </b>" .$row[0]['Author'] . "<br><b>Publisher: </b>" . $row[0]['Publisher'] . "</td>";
											print "<td>".$quantArr[$tableIndex] ."</td> <td>$" . $row[0]['Price'] . "</td></tr>";
											
											$tableIndex++;
										}
										if(!isset($_SESSION['quantArr']))
											$_SESSION['quantArr'] =array();
										array_push($_SESSION['quantArr'], $quantArr);
										array_push($_SESSION['subtotal'], $subtotal);
										//check if delete button is pressed
										if(isset($_GET['deleteIndex'])) {
											if (count($_SESSION['cart']) == 1) {
												unset($_SESSION['cart']);
											} else {
												unset(($_SESSION['cart'])[$_GET['deleteIndex']]);
												$_SESSION['cart'] = array_values($_SESSION['cart']);
											}
											header("Location: shopping_cart.php");
										}
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
					<input type="submit" id="buyit" name="btnbuyit" value="BUY IT!">
				</td>
		</form>
		<td align="right">
			<form id="update" action="update_customerprofile.php" method="post">
				<input type="submit" id="update_customerprofile" name="update_customerprofile" value="Update Customer Profile">
			</form>
		</td>
		<td align="left">
			<form id="cancel" action="screen2.php" method="post">
				<input type="submit" id="cancel" name="cancel" value="Cancel">
			</form>
		</td>
		</tr>
	</table>
</body>

</HTML>