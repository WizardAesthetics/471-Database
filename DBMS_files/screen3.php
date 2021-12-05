<!-- Figure 3: Search Result Screen by Prithviraj Narahari, php coding: Alexander Martens -->
<!-- Blake Johnson & Nicholas Christian -->
<html>

<?php
	session_start();
	
	if (ISSET($_GET['cartisbn']) && !in_array($_GET['cartisbn'], $_SESSION['cart']))
		array_push($_SESSION['cart'], $_GET['cartisbn']);

	
?>

<head>
	<title> Search Result - 3-B.com </title>
	<script>
		//redirect to reviews page
		function review(isbn, title, author, searchfor, searchon, category, brah) {
			var rows = document.getElementsByName('btnCart');
			disabled = brah;
			disabled = disabled.split(" ");
			if (disabled.length==1){
				for(i=0; i<rows.length; i++){
					disabled.push(false);
				}
			}
			searchon = searchon.split(" ");
			window.location.href = "screen4.php?isbn=" + isbn + "&title=" + title + "&author=" + author + "&searchfor=" + searchfor + "&searchon%5B%5D=" + searchon.join('&searchon%5B%5D=') + "&category=" + category + "&disabled%5B%5D=" + disabled.join('&disabled%5B%5D=');;
		}
		//add to cart
		function cart(isbn, searchfor, searchon, category, brah) {
			var rows = document.getElementsByName('btnCart');
			disabled = brah;
			disabled = disabled.split(" ");
			if (disabled.length==1){
				for(i=0; i<rows.length; i++){
					disabled.push(false);
					if (rows[i].id == isbn)
						disabled[i] = true;
				}
			} else {
				for(i=0; i<rows.length; i++){
					if (rows[i].id == isbn)
						disabled[i] = true;
				}
			}
			searchon = searchon.split(" ");
			window.location.href = "screen3.php?cartisbn=" + isbn + "&searchfor=" + searchfor + "&searchon%5B%5D=" + searchon.join('&searchon%5B%5D=') + "&category=" + category + "&disabled%5B%5D=" + disabled.join('&disabled%5B%5D=');

		}
	</script>
</head>

<body>
	<table align="center" style="border:1px solid blue;">
		<tr>
			<td align="left">

				<h6>
					<fieldset>
						<?php 
							if(!isset($_SESSION['cart']))
								print 'Your Shopping Cart has 0 items';
							else 
								print 'Your Shopping Cart has '.count($_SESSION['cart']).' items';
						?>
					</fieldset>
				</h6>

			</td>
			<td>
				&nbsp
			</td>
			<td align="right">
				<form action="shopping_cart.php" method="post">
					<input type="submit" value="Manage Shopping Cart">
				</form>
			</td>
		</tr>
		<tr>
			<td style="width: 350px" colspan="3" align="center">
				<div id="bookdetails" style="overflow:scroll;height:300px;width:400px;border:1px solid black;background-color:LightBlue">
					<table id = "bookdetailsTable" >
						<?php
							if (isset($_GET["checkout"])) {
								if (isset($_SESSION['user'])){
									?><script>
										window.location = "confirm_order.php";
									</script><?php
								} else {
									?><script>
										alert("You must register to continue with your purchase.");
										window.location = "customer_registration.php";
									</script><?php
								}
							}
							try{
								$pdo = new PDO('sqlite:bbb.db');
								$category = $_GET["category"];
								$searchfor = $_GET["searchfor"];
								$searchon = $_GET["searchon"];
								$serchIn = '';
								$str_arr = preg_split ("/\,/", $searchfor); 
								//Making the string for the where statement based on the $searchfor and $searchon
								if($searchon[0] != 'anywhere'){
									foreach ($str_arr as $temp) {
										foreach ($searchon as $results) {
											$results = ucfirst($results);
											$serchIn .= $results . ' LIKE  ' .'\'%'.$temp .'%\''.' OR ';
										}
									}
									$serchIn = substr($serchIn,0,strlen($serchIn) -4);
								} else {
									foreach ($str_arr as $temp) {
										$serchIn .= 'Title LIKE  ' .'\'%'.$temp .'%\''.' OR Author LIKE  ' .'\'%'.$temp .'%\''.' OR ' .
													'Publisher LIKE  ' .'\'%'.$temp .'%\''.' OR Price LIKE  ' .'\'%'.$temp .'%\''.' OR ' .
													'Price LIKE  ' .'\'%'.$temp .'%\''.' OR Category LIKE  ' .'\'%'.$temp .'%\''.' OR ' .
													'Inventory LIKE  ' .'\'%'.$temp .'%\''.' OR ISBN LIKE  ' .'\'%'.$temp .'%\''.' OR ';
									}
									$serchIn = substr($serchIn,0,strlen($serchIn) -4);
								}

								//Checking the catagory
								if($category != 'All') $temp = 'where (Category = \''.$category. '\' ) AND  ('.$serchIn. ') AND Inventory > 0';
								else $temp = 'where ('.$serchIn. ') AND Inventory > 0';

								//making the slect statment
								$statement = $pdo->query("SELECT * 
								FROM Books
								$temp");
								$rows = $statement->fetchAll(PDO::FETCH_ASSOC);

								if (!ISSET($_GET['disabled'])){
									$disabled = [];
								} else {
									$disabled = $_GET['disabled'];
								}
								for ($i = 0; $i<count($rows); $i++){
									$results = $rows[$i];
									
									if(count($disabled) > 0){
										if($disabled[$i] == 'true'){
											print '<tr  ><td align=\'left\'><button id=\''. $results['ISBN'] .'\'; disabled name=\'btnCart\'; onClick=\'cart("'. $results['ISBN'] .'", "'. $searchfor .'", "'. implode(" ",$searchon) .'","'. $category .'", "'. implode(" ",$disabled) .'");\'> Add to Cart</button></td>	
												<td rowspan=\'2\' align=\'left\'>'. $results['Title'] .'</br>'. $results['Author'] .'</br><b>Publisher: </b>'. $results['Publisher'] .',</br><b>ISBN: </b> '. $results['ISBN'] .'</t> <b>Price:</b> $'. $results['Price'] .'</td></tr>';
											print '<tr><td align=\'left\'><button name=\'review\' id=\'review\' onClick=\'review("'. $results['ISBN'] .'", "'. $results['Title'] .'", "By'. $results['Author'] .'", "'. $searchfor .'", "'. implode(" ",$searchon) .'","'. $category .'", "'. implode(" ",$disabled) .'");\'>Reviews</button></td></tr>';
											print '<tr><td colspan=\'2\'><p>_______________________________________________</p></td></tr>';
										} else{
											print '<tr ><td align=\'left\'><button id=\''. $results['ISBN'] .'\'; name=\'btnCart\'; onClick=\'cart("'. $results['ISBN'] .'", "'. $searchfor .'", "'. implode(" ",$searchon) .'","'. $category .'", "'. implode(" ",$disabled) .'");\'> Add to Cart</button></td>	
												<td rowspan=\'2\' align=\'left\'>'. $results['Title'] .'</br>'. $results['Author'] .'</br><b>Publisher: </b>'. $results['Publisher'] .',</br><b>ISBN: </b> '. $results['ISBN'] .'</t> <b>Price:</b> $'. $results['Price'] .'</td></tr>';
											print '<tr><td align=\'left\'><button name=\'review\' id=\'review\' onClick=\'review("'. $results['ISBN'] .'", "'. $results['Title'] .'", "By'. $results['Author'] .'", "'. $searchfor .'", "'. implode(" ",$searchon) .'","'. $category .'", "'. implode(" ",$disabled) .'");\'>Reviews</button></td></tr>';
											print '<tr><td colspan=\'2\'><p>_______________________________________________</p></td></tr>';
										}
									} else {
										print '<tr ><td align=\'left\'><button id=\''. $results['ISBN'] .'\'; name=\'btnCart\'; onClick=\'cart("'. $results['ISBN'] .'", "'. $searchfor .'", "'. implode(" ",$searchon) .'","'. $category .'", "'. implode(" ",$disabled) .'");\'>Add to Cart</button></td>
											<td rowspan=\'2\' align=\'left\'>'. $results['Title'] .'</br>'. $results['Author'] .'</br><b>Publisher: </b>'. $results['Publisher'] .',</br><b>ISBN: </b> '. $results['ISBN'] .'</t> <b>Price:</b> $'. $results['Price'] .'</td></tr>';
										print '<tr><td align=\'left\'><button name=\'review\' id=\'review\' onClick=\'review("'. $results['ISBN'] .'", "'. $results['Title'] .'", "By'. $results['Author'] .'", "'. $searchfor .'", "'. implode(" ",$searchon) .'", "'. $category .'", "'. implode(" ",$disabled) .'");\'>Reviews</button></td></tr>';
										print '<tr><td colspan=\'2\'><p>_______________________________________________</p></td></tr>';
									}
								}

							} catch (Exception $Exception) {
								print($temp);
								?>
								<script>
									window.location = "screen2.php";
									alert('No Books Found');
								</script>

								<?php
							}
						?>
					</table>
				</div>

			</td>
		</tr>
		<tr>
			<td align="center">
				<form action="" method="get">
					<input type="submit" value="Proceed To Checkout" id="checkout" name="checkout">
				</form >
			</td>
			
			<td align="center">
				<form action="screen2.php" method="post">
					<input type="submit" value="New Search">
				</form>
			</td>
			<td align="center">
				<form action="index.php" method="post">
					<input type="submit" name="exit" value="EXIT 3-B.com">
				</form>
			</td>
		</tr>
	</table>
</body>
</html>