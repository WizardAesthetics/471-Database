
<!-- Figure 2: Search Screen by Alexander -->
<!-- Blake Johnson & Nicholas Christian -->
<html>
<?php
	session_start();

	if (!ISSET($_SESSION['cart'])) 
		$_SESSION['cart'] = array();

	if (ISSET($_SESSION['isClicked'])) 
		session_destroy();

?>
	
<head>
	<title>SEARCH - 3-B.com</title>
</head>
<body>
	<table align="center" style="border:1px solid blue;">
		<tr>
			<td>Search for: </td>
				<td><input id ="searchfor" name="searchfor" /></td>
				<td><input type="submit" name="search" value="Search" onclick="page3()" />
		</tr>
		<tr>
			<td>Search In: </td>
				<td>
					<select name="searchon[]" multiple>
						<option value="anywhere" selected='selected'>Keyword anywhere</option>
						<option value="title">Title</option>
						<option value="author">Author</option>
						<option value="publisher">Publisher</option>
						<option value="isbn">ISBN</option>				
					</select>
				</td>
				<td><a href="shopping_cart.php"><input type="button" name="manage" value="Manage Shopping Cart" /></a>
			</td>
		</tr>
		<tr>
			<td>Category: </td>
				<td>
					<select id ="category" name="category">
						<?php
							$pdo = new PDO('sqlite:bbb.db');
							$statement = $pdo->query("SELECT Category FROM Books GROUP By Category ");
							$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
							print '<option id = \'All\'; value=\'All\'>All</option>';
							for ($i = 0; $i<count($rows); $i++){
								$results = $rows[$i];
								
								print '<option id = \''.$results['Category'].'\'; value=\''.$results['Category'].'\'>'.$results['Category'].'</option>';
							}
						?>	
					</select>
				</td>
				</form>
			<form action="index.php" method="post">	
				<td><input type="submit" name="exit" value="EXIT 3-B.com"?></td>
			</form>
		</tr>
	</table>
</body>
<script>
	function page3(){
		searchfor = document.getElementById("searchfor").value
		if(searchfor>''){
			temp = '';
			searchon = document.getElementsByName("searchon[]");
			category = document.getElementById("category").value;
			for(i=0; i<searchon.length; i++){
				temp +=  '&searchon%5B%5D='+searchon[i].value
			}
			window.location = "screen3.php?&searchfor=" + searchfor +  temp+ "&category=" + category;
		} 
	}
</script>
</html>
