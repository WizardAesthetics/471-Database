<!-- UI: Prithviraj Narahari, php code: Alexander Martens -->
<!-- Blake Johnson & Nicholas Christian -->
<?php
	session_start();
?>
<head>
<title> CUSTOMER REGISTRATION </title>
</head>
<body>
	<table align="center" style="border:2px solid blue;">
		<tr>
			<form id="register" action="" method="post">
			<td align="right">
				Username<span style="color:red">*</span>:
			</td>
			<td align="left" colspan="3">
				<input type="text" id="username" name="username" placeholder="Enter your username">
			</td>
		</tr>
		<tr>
			<td align="right">
				PIN<span style="color:red">*</span>:
			</td>
			<td align="left">
				<input type="password" id="pin" name="pin">
			</td>
			<td align="right">
				Re-type PIN<span style="color:red">*</span>:
			</td>
			<td align="left">
				<input type="password" id="retype_pin" name="retype_pin">
			</td>
		</tr>
		<tr>
			<td align="right">
				Firstname<span style="color:red">*</span>:
			</td>
			<td colspan="3" align="left">
				<input type="text" id="firstname" name="firstname" placeholder="Enter your firstname">
			</td>
		</tr>
		<tr>
			<td align="right">
				Lastname<span style="color:red">*</span>:
			</td>
			<td colspan="3" align="left">
				<input type="text" id="lastname" name="lastname" placeholder="Enter your lastname">
			</td>
		</tr>
		<tr>
			<td align="right">
				Address<span style="color:red">*</span>:
			</td>
			<td colspan="3" align="left">
				<input type="text" id="address" name="address">
			</td>
		</tr>
		<tr>
			<td align="right">
				City<span style="color:red">*</span>:
			</td>
			<td colspan="3" align="left">
				<input type="text" id="city" name="city">
			</td>
		</tr>
		<tr>
			<td align="right">
				State<span style="color:red">*</span>:
			</td>
			<td align="left">
				<select id="state" name="state">
				<option selected disabled>select a state</option>
				<option>Michigan</option>
				<option>California</option>
				<option>Tennessee</option>
				</select>
			</td>
			<td align="right">
				Zip<span style="color:red">*</span>:
			</td>
			<td align="left">
				<input type="text" id="zip" name="zip">
			</td>
		</tr>
		<tr>
			<td align="right">
				Credit Card<span style="color:red">*</span>
			</td>
			<td align="left">
				<select id="credit_card" name="credit_card">
				<option selected disabled>select a card type</option>
				<option>VISA</option>
				<option>MASTER</option>
				<option>DISCOVER</option>
				</select>
			</td>
			<td colspan="2" align="left">
				<input type="text" id="card_number" name="card_number" placeholder="Credit card number">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="right">
				Expiration Date<span style="color:red">*</span>:
			</td>
			<td colspan="2" align="left">
				<input type="text" id="expiration" name="expiration" placeholder="MM/YY">
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"> 
				<input type="submit" id="register_submit" name="register_submit" value="Register">
			</td>
			</form>
			<form id="no_registration" onsubmit="donot_Register()" action="screen2.php">
			<td colspan="2" align="center">
				<input type="submit" id="donotregister" name="donotregister" value="Don't Register">
			</td>
			</form>
		</tr>
	</table>

	<script>
		function donot_Register() {
   			alert("In order to proceed with the payment, you need to register first.");	
		}
	</script>

	<?php

		try {

			$pdo = new PDO('sqlite:bbb.db');

			//init user false for unique username check
			$user = false;

			//get attribute fields, null if not set
			$username = $_POST["username"] ?? "";
			$pin = $_POST["pin"] ?? "";
			$retype_pin = $_POST["retype_pin"] ?? "";
			$fname = $_POST["firstname"] ?? "";
			$lname = $_POST["lastname"] ?? "";
			$street_address = $_POST["address"] ?? "";
			$city = $_POST["city"] ?? "";
			$state = $_POST["state"] ?? "";
			$zip = $_POST["zip"] ?? "";
			$cc_type = $_POST["credit_card"] ?? "";
			$cc_num = $_POST["card_number"] ?? "";
			$cc_exp = $_POST["expiration"] ?? "";
			
		
			//check if pins match
			if (strcmp($pin, $retype_pin) != 0) {
				echo("PIN does not match");
			} else {

				//run when submit button clicked
				if (isset($_POST["register_submit"])) {
					
					//check if username exists
					$statement = $pdo->query("SELECT Username FROM User");
                    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

					foreach ($rows as $results){
                        if(strcmp($username, $results['Username']) == 0){
                            $user = true;
                            break;
                        }
                    }
					
					if ($user) {
						?> <script>alert("That Username already exists. Please choose another.") </script> <?php
					} else {
						
						//set placeholders into insert
						$sql = "INSERT INTO User VALUES (:Username, :Pin, :Fname, :Lname, :Street_Address, :City, :State, :zip, :Card_Type, :Card_Num, :Expiration);";

						$stmt = $pdo->prepare($sql);

						//execute insert
						$results = $stmt->execute(array( ':Username' => $username, ':Pin' => $pin, ':Fname' => $fname, ':Lname' => $lname,
						':Street_Address' => $street_address, ':City' => $city, ':State' => $state, ':zip' => $zip, ':Card_Type' => $cc_type, ':Card_Num' => $cc_num, ':Expiration' => $cc_exp));

						if ($results) {
							$statement = $pdo->query("SELECT Username, Pin FROM User WHERE Username = '$username'"); 
							$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
							$_SESSION['user'] = $_SESSION['user'] = $rows[0]['Username'];
							?>
							<script type="text/javascript">
   								alert("Registration Complete!");
   								window.location = "screen2.php";
							</script>
							<?php
						} else {
							echo("Something went wrong...");
							echo($results);
						}
					}
				}
			}

		} catch (PDOException $Exception) {
			// echo("Something went wrong, please try again.");
			die($Exception->getMessage());
		}

		
	?>
</body>
</HTML>