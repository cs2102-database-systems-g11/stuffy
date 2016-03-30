<?php include('./functions.php'); ?>
<html>
<?php include('head.html') ?>
<?php
$dbPassword = getenv("DB_PASSWORD");
$dbconn = pg_connect("host=localhost port=5432 dbname=stuffy_db user=postgres
	password=" . $dbPassword)
or die('Could not connect: ' . pg_last_error());
?>
<?php
$params = array($_POST['owner'],$_POST['itemName']);
$query = "DELETE FROM advertise_item WHERE owner = $1 AND item_name = $2;";
$result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
?>
<body>
	<?php include('header.php') ?>
	<div class='content'>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Successful</h3>
			</div>
			<div class="panel-body">
				<a href="./index.php" class="btn btn-primary" role="button">Home</a>
			</div>
		</div>
	</div>
	<?php
	pg_close($dbconn);
	?>
</body>
</html>
