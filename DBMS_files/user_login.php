<!DOCTYPE HTML>
<!-- Nicholas Christian & Blake Johnson -->
<head>
<title>User Login</title>
</head>
<body>
	<table align="center" style="border:2px solid blue;">
	<form action="" method="post" id="login_screen">
		<tr>
			<td align="right">
				Username<span style="color:red">*</span>:
			</td>
			<td align="left">
				<input type="text" name="username" id="username">
			</td>
			<td align="right">
				<input type="submit" name="login" id="login" value="Login" onsubmit="logmein()">
			</td>
		</tr>
		<tr>
			<td align="right">
				PIN<span style="color:red">*</span>:
			</td>
			<td align="left">
				<input type="password" name="pin" id="pin">
			</td>
			</form>
			<form action="index.php" method="post" id="login_screen">
			<td align="right">
				<input type="submit" name="cancel" id="cancel" value="Cancel">
			</td>
			</form>
		</tr>
	</table>
</body>
<?php
	//disables warnings 
	//Used for hiding null username/pin warnings from user if user enters invalid credentials
	error_reporting(0);

	try {
		$pdo = new PDO('sqlite:bbb.db');

		//get attribute fields, null if not set
		$username = $_POST["username"] ?? "";
		$pin = $_POST["pin"] ?? "";

		//check if login submitted
		if (isset($_POST["login"])) {
			$statement = $pdo->query("SELECT Username, Pin FROM User WHERE Username = '$username'"); 
			$rows = $statement->fetchAll(PDO::FETCH_ASSOC);

			//check if pin matches
			if ($rows[0]['Pin'] == $pin) {
				session_start();
				$_SESSION['user'] = $rows[0]['Username'];
				?><script>
				alert("You have logged in!");
				window.location = "screen2.php";
				</script><?php
			} else {
				?><script>
				alert("Username and pin combination does not exist.");
				</script><?php
			}
		}
			

	} catch (PDOException $Exception) {
		die($Exception->getMessage());
	}
?>
	


</html>

