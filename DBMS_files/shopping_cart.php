<!-- Nicholas Christian & Blake Johnson -->

<!DOCTYPE HTML>
<?php
	session_start();

	$_SESSION['quantArr'] = array();
	$_SESSION['subtotal'] = array();


?>
<head>
	<title>Shopping Cart</title>
</head>
<body>
	<table align="center" style="border:2px solid blue;">
		<tr>
			<td align="center">
				<form id="checkout" action="" method="get">
					<input type="button" name="checkout_submit" id="checkout_submit" value="Proceed to Checkout" onClick="check_submit()">
				</form>
			</td>
			<td align="center">
				<form id="new_search" action="screen2.php" method="post">
					<input type="submit" name="search" id="search" value="New Search">
				</form>								
			</td>
			<td align="center">
				<form id="exit" action="index.php" method="post">
					<input type="submit" name="exit" id="exit" value="EXIT 3-B.com">
				</form>					
			</td>
		</tr>
		<tr>
				<form id="recalculate" name="recalculate" action="" method="post">
			<td  colspan="3">
				<div id="bookdetails" style="overflow:scroll;height:300px;width:750px;border:1px solid black;">
					<table align="center" BORDER="2" CELLPADDING="2" CELLSPACING="2" WIDTH="100%" id="tableCart">
						<th width='10%'>Remove</th><th width='60%'>Book Description</th><th width='10%'>Qty</th><th width='10%'>Price</th>
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
										if(isset($_GET['quantityChange'])) {
											$quantString = $_GET['quantityChange'];
											$quantArr = explode(",",$quantString);
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
											?><tr id='table_row'><td><button name='delete' id='delete' onClick="deleteJS(this.parentNode.parentNode.rowIndex)" >Delete Item</button></td>
											<?php print "<td>". $tableArr[$tableIndex]['Title'] ."<br><b>By: </b>". $tableArr[$tableIndex]['Author'] ."<br><b>Publisher: </b>". $tableArr[$tableIndex]['Publisher'] ."</td>";
											print "<td><input type='number' name='quantity' min='1' value=". $quantArr[$tableIndex] ."></td> "?>
											<?php print "<td>$". $tableArr[$tableIndex]['Price'] ."</td></tr>";
											
											$tableIndex++;
										}
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
			<td align="center">		
					<input type="button" name="recalculate_payment" id="recalculate_payment" value="Recalculate Payment" onClick="recalc()">
				</form>
			</td>
			<td align="center">
				&nbsp;
			</td>
			<td align="center">			
				<?php print "Subtotal: $". $subtotal?></td>
		</tr>
	</table>
</body>

<script>
	function deleteJS(index) {
		index = index - 1;
		document.write(index);
		window.location.href = "shopping_cart.php?deleteIndex=" + index;
	}

	function check_submit() {
		var qchange = invent_check();
		if (qchange != false) {
			<?php
				if (isset($_SESSION['user'])){
					?> window.location.href = "confirm_order.php?"; <?php
				} else {
					?> alert("You must register to continue with your purchase.");
					window.location = "customer_registration.php"; <?php
				}
			?>
		}
	}

	function recalc() {
		var qchange = invent_check();
		if (qchange != false) {
			window.location.href = "shopping_cart.php?quantityChange=" + qchange;
		}
	}

	function invent_check() {
		var invent = <?php echo json_encode($inventArr); ?>;
		var qchange = "";
        const tagname = document.getElementsByName('quantity');
		var refresh;

        for (i = 0; i < tagname.length; i++) {
            if (tagname[i].type == 'number') {
				if (parseInt(tagname[i].value) > parseInt(invent[i])) {
					alert("Insufficient inventory for quantity: " + tagname[i].value);
					refresh = false;
				} else {
                	qchange += tagname[i].value + ",";
					if (refresh != false) {
						refresh = true;
					}
				}
            }
        }
		if (refresh == true) {
			return qchange;
		}
		else {
			return false;
		}
	}
</script>