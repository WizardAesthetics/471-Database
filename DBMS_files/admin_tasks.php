
<!DOCTYPE HTML>
<head>
	<title>ADMIN TASKS</title>
	<style>
		table{
			padding: 10px;
			background-color: cadetblue;
			width:  30%;
			margin-top: 10px;
		}
		td, th{
			padding: 10px;
			border: solid black;
			width: 50%
		}
		tr:hover{
			background-color: lightblue;
		}
		h1{
			text-align: center;
		}
	</style>
</head>
<body>
	<h1>Admin Report</h1>
	<?php
	
		$pdo = new PDO('sqlite:bbb.db');
		
		// Total number of registered customers in the system at the time and date of inquiry. 
		$statement = $pdo->query("SELECT COUNT(Username)
		FROM User;");
		$row = $statement->fetchAll(PDO::FETCH_ASSOC);

		// Displaying results in a table 
		print '	<table align="center" style="border:2px solid black;">';
		print '<tr><th># of Users</th></tr>';
		for ($i = 0; $i<count($row); $i++){
			print '<tr><td>' . $row[$i]['COUNT(Username)'].'</td></tr>';
		}
		print '</table>';


		// Total number of book titles available in each category, in descending order. 
		$statement = $pdo->query("SELECT Category, COUNT(Title)
			FROM Books 
			GROUP BY Category
			ORDER BY COUNT(Title) DESC;");
		$row = $statement->fetchAll(PDO::FETCH_ASSOC);

		// Displaying results in a table 
		print '	<table align="center" style="border:2px solid black;">';
		print '<tr><th>Category</th><th># of Titles</th></tr>';
		for ($i = 0; $i<count($row); $i++){
			print '<tr><td>'.$row[$i]['Category']. '</td><td>' . $row[$i]['COUNT(Title)'].'</td></tr>';
		}
		print '</table>';


		//  Average monthly sales, in dollars, for the current year, ordered by month. 
		$statement = $pdo->query("select SUM(Total_Amount), strftime(\"%m-%Y\", DateTime) 
		FROM Orders
		GROUP by strftime(\"%m-%Y\", DateTime) 
		Having strftime(\"%Y\", DateTime) == strftime(\"%Y\", date('now'))
		ORDER BY strftime(\"%m\", DateTime) DESC;");
		$row = $statement->fetchAll(PDO::FETCH_ASSOC);

		// Displaying results in a table 
		print '	<table align="center" style="border:2px solid black;">';
		print '<tr><th>Date</th><th>Total Sales</th></tr>';
		for ($i = 0; $i<count($row); $i++){
			print '<tr><td>' . $row[$i]['strftime("%m-%Y", DateTime)'].'</td><td>$'.$row[$i]['SUM(Total_Amount)']. '</td></tr>';
		}
		print '</table>';


		// All book titles and the number of reviews for that book. 
		$statement = $pdo->query("SELECT Title, COUNT(Title)
			FROM Books,  Reviews 
			WHERE Books.ISBN = Reviews.ISBN
			GROUP BY Title;");
		$row = $statement->fetchAll(PDO::FETCH_ASSOC);

		// Displaying results in a table 
		print '	<table align="center" style="border:2px solid black;">';
		print '<tr><th>Title</th><th># of Reviews</th></tr>';
		for ($i = 0; $i<count($row); $i++){
			print '<tr><td>'.$row[$i]['Title']. '</td><td>' . $row[$i]['COUNT(Title)'].'</td></tr>';
		}
		print '</table>';


	?>
</body>


</html>