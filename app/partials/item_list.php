<?php include('/functions.php'); ?>
<?php
    $dbPassword = getenv("DB_PASSWORD");
    $dbconn = pg_connect("host=localhost port=5432 dbname=stuffy_db user=postgres
    password=" . $dbPassword)
    or die('Could not connect: ' . pg_last_error());
?>

<div class='container-fluid grid-list'>
	<div class='row margin-bottom-20'>
		<?php
        $query = "SELECT owner, item_name, bid_deadline FROM advertise_item WHERE bid_deadline > NOW() ORDER BY bid_deadline;";
        $result = pg_query($dbconn, $query) or die("Query failed: " . pg_last_error());
		
		while($row = pg_fetch_array($result)) {
			$bid_deadline = $row['bid_deadline'];
			$owner = $row['owner'];
			$item_name = $row['item_name'];
			
			$params = array($owner, $item_name);
			$query = "SELECT MAX(bid) AS bid FROM bid WHERE owner = $1 AND item_name = $2;";
			$highest_bid = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
			$highest_bid = pg_fetch_array($highest_bid);
			if (empty($highest_bid['bid'])){
				$display_bid = "None";
			} else {
				$display_bid = "$" . $highest_bid['bid'];
			}
		?>
		<div class='grid-list-item col-sm-4'>
			<div class="thumbnail item-content">
				<a href='#'><img src="http://placehold.it/300x200" alt="..."></a>
				<div class="caption">
					<h4 class='title'>
						<a href='#'><?php echo $item_name?></a>
					</h4>
					<p style="font-size:12px">Highest Bid: <?php echo $display_bid?></p>
					<p style="font-size:12px">Bid Deadline: <?php echo $bid_deadline?></p>
				</div>
			</div>
		</div>
		<?php
		}
		?>
	</div>
</div>
<?php
    pg_close($dbconn);
?>