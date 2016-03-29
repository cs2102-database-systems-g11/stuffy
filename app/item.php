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
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$owner = '';
$item_name = '';
$type = '';
$description = '';
$starting_bid = '';
$bid_deadline = '';
$buyout = '';
$buyout_int = '';
$quantity = '';
$pickup_location = '';
$return_location = '';
$return_date = '';
$exists = true;

$params = array($_GET['user']);
$query = "SELECT email FROM users WHERE username = $1;";
$result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
$row = pg_fetch_array($result);
$owner = $row['email'];

$params = array($owner, rawurldecode($_GET['name']));
$query = "SELECT * FROM advertise_item WHERE owner = $1 AND item_name = $2;";
$result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());

if (pg_num_rows($result) > 0) {
    $row = pg_fetch_array($result);
    $owner = $row['owner'];
    $item_name = $row['item_name'];
    $type = $row['type'];
    $description = $row['description'] == '' ? 'None' : $row['description'];
    $starting_bid = '$' . $row['starting_bid'];
    $bid_deadline = $row['bid_deadline'];
    $buyout_int = $row['buyout'];
    $buyout = $buyout_int == NULL ? 'None' : '$' . $buyout_int;
    $quantity = $row['available_quantity'];
    $pickup_location = $row['pickup_location'] == '' ? 'None' : $row['pickup_location'];
    $return_location = $row['return_location'] == '' ? 'None' : $row['return_location'];
    $return_date = $row['return_date'];
} else {
    $exists = false;
}

$query = "SELECT MAX(bid) AS bid FROM bid WHERE owner = $1 AND item_name = $2;";
$result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
$row = pg_fetch_array($result);
$highest_bid = $row['bid'];
$highest_bid_int = $highest_bid;
$next_highest_bid2 = $highest_bid+1;

$params = array($owner, $item_name, $highest_bid);
$query = "SELECT bidder, created FROM bid WHERE owner = $1 AND item_name = $2 AND bid = $3;";
$result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
$row = pg_fetch_array($result);
$created = $row['created'];
$bidder = $row['bidder'];
$highest_bid = $highest_bid == '' ? 'None' : '$' . $highest_bid;

$params = array($bidder);
$query = 'SELECT username, first_name, last_name FROM users where email = $1';
$result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
$row = pg_fetch_array($result);
$username = '';
if (pg_num_rows($result) > 0) {
    $username = pg_fetch_result($result, 0, 0);
}
$bidder = trim($row['first_name'] . ' ' . $row['last_name']);
if ($bidder) {
    $bidder = $username . ' (' . $bidder . ')';
} else {
    $bidder = $username;
}

$params = array($owner);
$query = 'SELECT username, first_name, last_name FROM users where email = $1';
$result = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
$row = pg_fetch_array($result);
$username = '';
if (pg_num_rows($result) > 0) {
    $username = pg_fetch_result($result, 0, 0);
}
$name = trim($row['first_name'] . ' ' . $row['last_name']);
if ($name) {
    $name = $username . ' (' . $name . ')';
} else {
    $name = $username;
}
?>

<body>
    <?php include('header.php') ?>
    <div class='content'>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Item Information</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal item-info">
                    <div class="form-group">
                        <div class="col-sm-4 form-group-content" align='center'>
                            <a href="#" class="thumbnail">
                                <img src="http://placehold.it/300x200" alt="...">
                            </a>
                        </div>
                        <div class="col-sm-8">
                            <div class="form-group">
                                <label for="item_name" class="control-label">Item Name: </label>
                                <div class="form-group-content">
                                    <?php echo $item_name ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="owner_name" class="">Owner: </label>
                                <div class="form-group-content"><?php echo $name ?></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="type" class="col-sm-3 control-label">Type: </label>
                        <div class="col-sm-9 form-group-content">
                            <?php echo $type ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-sm-3 control-label">Description: </label>
                        <div class="col-sm-9 form-group-content">
                            <?php echo $description ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="col-sm-3 control-label">Quantity: </label>
                        <div class="col-sm-9 form-group-content">
                            <?php echo $quantity ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="pickup_location" class="col-sm-3 control-label">Pickup Location: </label>
                        <div class="col-sm-9 form-group-content">
                            <?php echo $pickup_location ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="return_location" class="col-sm-3 control-label">Return Location: </label>
                        <div class="col-sm-9 form-group-content">
                            <?php echo $return_location ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="return-date" class="col-sm-3 control-label">Return Date: </label>
                        <div class="col-sm-9 form-group-content">
                            <?php echo $return_date ?>
                        </div>
                    </div>						
                </div>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Bidding Information</h3>
            </div>
            <div class="panel-body">
                <div class="form-horizontal item-info">
                    <div class="form-group">
                        <label for="starting_bid" class="col-sm-3 control-label">Starting Bid: </label>
                        <div class="col-sm-9 form-group-content">
                            <?php echo $starting_bid ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="highest_bid" class="col-sm-3 control-label">Highest Bid: </label>
                        <div class="col-sm-9 form-group-content">
                            <?php echo $highest_bid; 
                             if ($highest_bid != 'None') {
                             ?>
                             <label for="bidder">BY </label>
                             <?php echo $bidder;
                                    };
                                    if ($highest_bid != 'None') {
                                    ?>
                                    <label for="created">ON </label>
                                    <?php echo $created;
                                           };
                                           ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="buyout" class="col-sm-3 control-label">Buyout: </label>
                        <div class="col-sm-9 form-group-content">
                            <?php echo $buyout ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bid_deadline" class="col-sm-3 control-label">Bid Deadline: </label>
                        <div class="col-sm-9 form-group-content">
                            <?php echo $bid_deadline ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($buyout_int != $highest_bid_int && isset($_SESSION['username']) && strtotime($bid_deadline) > time()) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Place Your Bid</h3>
            </div>
            <div class="panel-body">
                <form namd = "bidForm" action="bid.php" method="post">
                    <div class="form-group">
                        <label for="pickup-location" class="control-label">Your Bid</label>
                        <input type="number" name='newBid' class="form-control" min = "<?php echo $next_highest_bid2 ?>" autofocus required> 
                        <input type="hidden" name = "owner" value = "<?php echo $owner ?>">
                        <input type="hidden" name = "itemName" value = "<?php echo $item_name ?>">
                        <input type="hidden" name = "url" value = "<?php echo $_SERVER['REQUEST_URI'] ?>">
                    </div>
                    <button class="btn btn-default" name='bid-submit' type="submit">Submit</button>
                </form>
            </div>
        </div>
        <?php } ?>
    <?php 
    if (!$exists) {
        echo "<script>document.querySelector('.panel').remove();</script>";
        create_notification('danger', 'Item does not exist.');
    }
    ?>
    </div> <!-- content -->
    <?php
    pg_close($dbconn);
    ?>
</body>
</html>
