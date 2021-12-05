<!-- screen 4: Book Reviews by Prithviraj Narahari, php coding: Alexander Martens-->
<!-- Blake Johnson & Nicholas Christian -->
<!DOCTYPE html>
<html>

<head>
	<title>Book Reviews - 3-B.com</title>
	<style>
		.field_set {
			border-style: inset;
			border-width: 4px;
		}
	</style>
</head>

<body>
	<table align="center" style="border:1px solid blue;">
		<tr>
			<td align="center">
				<h5> Reviews For:</h5>
			</td>
			<td align="left">
				<h5>
					<?php
						$title = $_GET["title"];
						$author = $_GET["author"];
						if(!isSet($title)|| !isSet($author)){
							?>
							<script>
								window.location = "screen2.php";
							</script>
							<?php
						}
						print "<p>" . $title . "</br> &nbsp" . $author . "</p>";
					?>
				</h5>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<div id="bookdetails" style="overflow:scroll;height:200px;width:300px;border:1px solid black;">
					<table>
						<?php
							$pdo = new PDO('sqlite:bbb.db');
							$title = $_GET["title"];
							$author = $_GET["author"];
							$ISBN = $_GET["isbn"];

							if(!isSet($title)|| !isSet($author)){
								?>
								<script>
									window.location = "screen2.php";
								</script>
								<?php
							}
							$statement = $pdo->query("SELECT * FROM Reviews WHERE ISBN = '$ISBN' ");
							$rows = $statement->fetchAll(PDO::FETCH_ASSOC);

							foreach ($rows as $results) {
								print "<tr><td>" . $results['Review'] . "<hr></td></tr>";
							}
						?>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center">
				<input type="submit" value="Done" onClick="recalc()">
			</td>
		</tr>
	</table>

</body>
<script>

	function recalc() {
		var isbn = <?php echo '\''.$_GET["isbn"].'\''; ?>;
		var category = <?php echo '\''.$_GET["category"].'\''; ?>;
		var searchfor = <?php echo '\''.$_GET["searchfor"].'\''; ?>;
		var searchon = <?php echo '\''.implode(" ",$_GET["searchon"]).'\''; ?>;
		var disabled = <?php echo '\''.implode(" ",$_GET["disabled"]).'\''; ?>;
		disabled = disabled.split(" ");
		searchon = searchon.split(" ");
		window.location.href = "screen3.php?&searchfor=" + searchfor + "&searchon%5B%5D=" + searchon.join('&searchon%5B%5D=') + "&category=" + category + "&disabled%5B%5D=" + disabled.join('&disabled%5B%5D=');
	}
</script>
</html>