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
$bidderEmail = '';
$owner = '';
$newBid = '';
$itemName = '';
$timestamp = '';
$username = '';
$exists = true;

if (isset($_GET['user'])) {
	$username = $_GET['user'];
} else {
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	$username = $_SESSION['username'];
}

if (isset($_POST['buyout-submit'])) {
    $newBid = $_POST['buyout'];
} else {
    $newBid = $_POST['newBid'];
}

$params = array($username);
$query = "SELECT * FROM users WHERE username = $1;";
$result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
if (pg_num_rows($result) > 0) {
	$row = pg_fetch_array($result);
	$bidderEmail = $row['email'];
} else {
	$exists = false;
}

$params = array($_POST['owner'], $_POST["itemName"], $newBid, $bidderEmail, date("Y-m-d h:i:s"));
$query = "INSERT INTO bid VALUES ($1, $2, $3, $4, $5)";
$result = pg_query_params($dbconn, $query, $params);

if (!$result) {
// echo $_POST['url'];
// redirect('/advertise.php');
// } else {
	create_notification('danger', 'Add new bid error.');
	die("Query failed: " . pg_last_error());
}
?>
<body>
	<?php include('header.php') ?>
	<div class='content'>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Successful</h3>
			</div>
			<div class="panel-body">
				<p> Your bid is successfully placed</p>
				<a href="./index.php" class="btn btn-primary" role="button">Get Home</a>
				<a href=<?php echo $_POST['url'] ?> class="btn btn-success" role="button">Get Back</a>
			</div>
		</div>
	</div>
	<?php
	pg_close($dbconn);
	?>
</body>
</html>
