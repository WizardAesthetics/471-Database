<!-- Blake Johnson & Nicholas Christian -->
<!DOCTYPE HTML>
<head>
<title>UPDATE CUSTOMER PROFILE</title>
<?php 
session_start();
$username = $_SESSION["user"]; 

		try {

			$pdo = new PDO('sqlite:bbb.db');

			$fillQuery = $pdo->query("SELECT * FROM User WHERE Username = '$username'");
			$fill = $fillQuery->fetchAll(PDO::FETCH_ASSOC);

			//get attribute fields, null if not set
			$pin = $_POST["new_pin"] ?? "";
			$retype_pin = $_POST["retypenew_pin"] ?? "";
			$fname = $_POST["firstname"] ?? "";
			$lname = $_POST["lastname"] ?? "";
			$street_address = $_POST["address"] ?? "";
			$city = $_POST["city"] ?? "";
			$state = $_POST["state"] ?? "";
			$zip = $_POST["zip"] ?? "";
			$cc_type = $_POST["credit_card"] ?? "";
			$cc_num = $_POST["card_number"] ?? "";
			$cc_exp = $_POST["expiration_date"] ?? "";
			
		
			//check if pins match
			if (strcmp($pin, $retype_pin) != 0) {
				?> <script>alert("PIN does not match") </script> <?php
			} else {

				//run when submit button clicked
				if (isset($_POST["update_submit"])) {

					//Update user's row with new data
					$sql = "UPDATE User SET Pin=?, Fname=?, Lname=?, Street_Adress=?, City=?, State=?, zip=?, Card_Type=?, Card_Num=?, Expiration=? WHERE Username=?";
					$stmt = $pdo->prepare($sql);
					$stmt->execute([$pin, $fname, $lname, $street_address, $city, $state, $zip, $cc_type, $cc_num, $cc_exp, $username]);

					if ($stmt) {
						?>
						<script type="text/javascript">
							   alert("Your information has been updated!");
							   window.location = "confirm_order.php";
						</script>
						<?php
					} else {
						echo("Something went wrong...");
					}
				}
			}
		} catch (PDOException $Exception) {
			// echo("Something went wrong, please try again.");
			die($Exception->getMessage());
		}
?>
</head>
<body>
	<form id="update_profile" action="" method="post">
	<table align="center" style="border:2px solid blue;">
		<tr>
			<td align="right">
				Username: <?php echo $username ?>
			</td>
			<td colspan="3" align="center">
							</td>
		</tr>
		<tr>
			<td align="right">
				New PIN<span style="color:red">*</span>:
			</td>
			<td>
				<input type="text" id="new_pin" name="new_pin" value="<?php echo $fill[0]['Pin'] ?>">
			</td>
			<td align="right">
				Re-type New PIN<span style="color:red">*</span>:
			</td>
			<td>
				<input type="text" id="retypenew_pin" name="retypenew_pin" value="<?php echo $fill[0]['Pin'] ?>">
			</td>
		</tr>
		<tr>
			<td align="right">
				First Name<span style="color:red">*</span>:
			</td>
			<td colspan="3">
				<input type="text" id="firstname" name="firstname" value="<?php echo $fill[0]['Fname'] ?>">
			</td>
		</tr>
		<tr>
			<td align="right"> 
				Last Name<span style="color:red">*</span>:
			</td>
			<td colspan="3">
				<input type="text" id="lastname" name="lastname" value="<?php echo $fill[0]['Lname'] ?>">
			</td>
		</tr>
		<tr>
			<td align="right">
				Address<span style="color:red">*</span>:
			</td>
			<td colspan="3">
				<input type="text" id="address" name="address" value="<?php echo $fill[0]['Street_Adress'] ?>">
			</td>
		</tr>
		<tr>
			<td align="right">
				City<span style="color:red">*</span>:
			</td>
			<td colspan="3">
				<input type="text" id="city" name="city" value="<?php echo $fill[0]['City'] ?>">
			</td>
		</tr>
		<tr>
			<td align="right">
				State<span style="color:red">*</span>:
			</td>
			<td>
				<select id="state" name="state">
				<option selected><?php echo $fill[0]['State'] ?></option>
				<option>Michigan</option>
				<option>California</option>
				<option>Tennessee</option>
				</select>
			</td>
			<td align="right">
				Zip<span style="color:red">*</span>:
			</td>
			<td>
				<input type="text" id="zip" name="zip" value="<?php echo $fill[0]['zip'] ?>">
			</td>
		</tr>
		<tr>
			<td align="right">
				Credit Card<span style="color:red">*</span>:
			</td>
			<td>
				<select id="credit_card" name="credit_card">
				<option selected><?php echo $fill[0]['Card_Type'] ?></option>
				<option>VISA</option>
				<option>MASTER</option>
				<option>DISCOVER</option>
				</select>
			</td>
			<td align="left" colspan="2">
				<input type="text" id="card_number" name="card_number" placeholder="Credit card number" value="<?php echo $fill[0]['Card_Num'] ?>">
			</td>
		</tr>
		<tr>
			<td align="right" colspan="2">
				Expiration Date<span style="color:red">*</span>:
			</td>
			<td colspan="2" align="left">
				<input type="text" id="expiration_date" name="expiration_date" placeholder="MM/YY" value="<?php echo $fill[0]['Expiration'] ?>">
			</td>
		</tr>
		<tr>
			<td align="right" colspan="2">
				<input type="submit" id="update_submit" name="update_submit" value="Update">
			</td>
			</form>
		<form id="cancel" action="index.php" method="post">	
			<td align="left" colspan="2">
				<input type="submit" id="cancel_submit" name="cancel_submit" value="Cancel">
			</td>
		</tr>
	</table>
	</form>
</body>
</html>