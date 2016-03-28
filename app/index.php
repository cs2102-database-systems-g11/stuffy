<?php include('./functions.php'); ?>
<?php
    $dbPassword = getenv("DB_PASSWORD");
    $dbconn = pg_connect("host=localhost port=5432 dbname=stuffy_db user=postgres
    password=" . $dbPassword)
    or die('Could not connect: ' . pg_last_error());
?>
<html>
    <?php include('head.html') ?>
    <body>
        <?php include('header.php') ?>
        <div class='content'>
        	<div class = 'panel panel-default'>
                <div class = 'panel-heading'>
                	<h3 class = 'panel-title'>Popular and recent items</h3>
                </div>
                <div class="panel-body">
                    <?php include('./partials/popular_items.php') ?>
                	<?php include('./partials/recent_items.php') ?>
                </div>
            </div>
            <div class = 'panel panel-default'>
            	<div class = 'panel-heading'>
                	<h3 class = 'panel-title'>Site statistics</h3>
                </div>
                <div class="panel-body">
                	<?php
                		$query = 
                		"SELECT u.num_users,i.num_items,b.num_bids
                		FROM (SELECT COUNT(*) AS num_users FROM users) AS u 
                		,(SELECT COUNT(*) AS num_items FROM advertise_item) AS i 
                		,(SELECT COUNT(*) AS num_bids FROM bid) AS b
                		";

                		$result = pg_query($dbconn, $query) or die('Query failed: '.pg_last_error());
                		$row = pg_fetch_array($result);
                		$num_users = $row[num_users];
                		$num_items = $row[num_items];
                		$num_bids = $row[num_bids];
                	?>
                	<div class="stats">
                		<div class="stats-content">
                		<?php echo $num_users?>
                		<p>Users</p>
                		</div>

                		<div class="stats-content">
                		<?php echo $num_items?>
                		<p>Items Advertised</p>
                		</div>

                		<div class="stats-content">
                		<?php echo $num_bids?> 
                		<p>Bids</p>
                		</div>
                	</div>
                </div>
            </div>
            <?php
            	if (!(isset($_SESSION['username']))){
            		include('./partials/sign_up.html');
            	}
            ?>
        </div>
        <?php if (isset($_GET['logged_out'])) {
            create_notification('success', 'Logged out.');
        } ?>
    </body>
</html>
<?php
    pg_close($dbconn);
?>