<?php include('/functions.php'); ?>
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
            <?php
                if (isset($_POST['search-submit'])) {
                    $query = '%' . $_POST['search-query'] . '%';
                    $params = array($query);
                    $query = "SELECT u.username, a.owner, a.item_name, a.bid_deadline 
                        FROM advertise_item a, users u
                        WHERE 
                        (a.item_name LIKE $1 OR a.description LIKE $1)
                        AND a.bid_deadline > NOW() 
                        AND a.owner = u.email
                        ORDER BY a.bid_deadline;";
                    $searchResults = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
                } else {
                    $query = "SELECT u.username, a.owner, a.item_name, a.bid_deadline FROM advertise_item a, users u WHERE a.bid_deadline > NOW() AND a.owner = u.email ORDER BY a.bid_deadline;";
                    $searchResults = pg_query($dbconn, $query) or die("Query failed: " . pg_last_error());
                }
            ?>

            <?php include('./partials/search_bar.php'); ?>
            <?php include('./partials/item_list.php'); ?>
        </div>
    </body>
</html>
<?php
    pg_close($dbconn);
?>
