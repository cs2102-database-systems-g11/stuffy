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
            <?php
                $limit = 30;
                $offset = 0;
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    $offset = $limit * ($page - 1);
                }
                if (isset($_GET['search-submit'])) {
                    $paramCount = 1;
                    $search_query = '%' . strtolower($_GET['search-query']) . '%';
                    $params = array($search_query);
                    $filter = "(lower(a.item_name) LIKE $".$paramCount." or lower(a.description) LIKE $".$paramCount.")";

                    // buyout filter
                    if (isset($_GET['max-buyout']) && $_GET['max-buyout'] != '' && is_numeric($_GET['max-buyout'])
                        && $_GET['max-buyout'] > 0) {
                        $paramCount += 1;
                        $maxBuyout = intval($_GET['max-buyout']);
                        array_push($params, $maxBuyout);
                        $filter = $filter . " and a.buyout <= $".$paramCount;
                    }

                    // location filter
                    $location = '%' . strtolower($_GET['location']) . '%';
                    array_push($params, $location);
                    $paramCount += 1;
                    $filter = $filter . " and (lower(a.pickup_location) LIKE $".$paramCount." or lower(a.return_location) LIKE $".$paramCount.")";

                    // quantity filter
                    if (isset($_GET['min-quantity']) && $_GET['min-quantity'] != '' && is_numeric($_GET['min-quantity'])) {
                        $paramCount += 1;
                        $minQuantity = intval($_GET['min-quantity']);
                        array_push($params, $minQuantity);
                        $filter = $filter . " and a.available_quantity >= $".$paramCount;
                    }

                    // type filter 
                    if (isset($_GET['type']) && $_GET['type'] != '' && $_GET['type'] != 'All') {
                        $paramCount += 1;
                        $type = $_GET['type'];
                        array_push($params, $type);
                        $filter = $filter . " and a.type = $".$paramCount;
                    }

                    // get row count for pagination
                    $query = "SELECT u.username, a.owner, a.item_name, a.bid_deadline 
                        FROM advertise_item a, users u
                        WHERE 
                        (" . $filter . ")
                        AND a.bid_deadline > NOW() 
                        AND a.owner = u.email;";
                    $result = pg_query_params($dbconn, $query, $params);
                    if (pg_num_rows($result) == 0) {
                        $totalRows = 0;
                    } else {
                        $totalRows = pg_fetch_result($result, 0, 0);
                    }

                    // get search results
                    $paramCount += 2;
                    array_push($params, $limit, $offset);
                    $query = "SELECT u.username, a.owner, a.item_name, a.bid_deadline 
                        FROM advertise_item a, users u
                        WHERE 
                        (" . $filter . ")
                        AND a.bid_deadline > NOW() 
                        AND a.owner = u.email
                        ORDER BY a.bid_deadline
                        LIMIT $" . ($paramCount - 1) . "
                        OFFSET $" . $paramCount . ";";

                    $searchResults = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
                } else {
                    $params = array($limit, $offset);
                    $query = "SELECT u.username, a.owner, a.item_name, a.bid_deadline 
                        FROM advertise_item a, users u 
                        WHERE a.bid_deadline > NOW() 
                        AND a.owner = u.email 
						AND NOT EXISTS (SELECT * FROM advertise_item a2, bid b WHERE b.owner = a2.owner AND b.item_name = a2.item_name AND b.bid = a2.buyout AND a.owner = a2.owner AND a.item_name = a2.item_name)
                        ORDER BY a.bid_deadline 
                        LIMIT $1
                        OFFSET $2;";
                    $searchResults = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
                    $query = "SELECT count(*)
                        FROM advertise_item a
                        WHERE a.bid_deadline > NOW()
						AND NOT EXISTS (SELECT * FROM advertise_item a2, bid b WHERE b.owner = a2.owner AND b.item_name = a2.item_name AND b.bid = a2.buyout AND a.owner = a2.owner AND a.item_name = a2.item_name);";
                    $totalRows = pg_fetch_result(pg_query($dbconn, $query), 0, 0);
                }
            ?>

            <?php include('./partials/search_bar.php'); ?>
            <?php include('./partials/item_list.php'); ?>

            <nav class='pagination-nav'>
                <ul class="pagination">
                <?php 
                    function updatePageInURI($uri, $currPage, $newPage) {
                        $currPageStr = 'page=' . $currPage;
                        $newPageStr = 'page=' . $newPage;
                        if (strpos($uri, $currPageStr) !== false) {
                            // found
                            $uri = str_replace($currPageStr, $newPageStr, $uri);
                        } else {
                            if (count($_GET) == 0) {
                                $uri = $uri . '?' . $newPageStr;
                            } else {
                                $uri = $uri . '&' . $newPageStr;
                            }
                        }
                        return $uri;
                    }

                    $uri = $_SERVER["REQUEST_URI"];
                    $page = !isset($_GET['page']) ? 1 : $_GET['page'];
                    $totalPages = ceil($totalRows / $limit);
                    $pagesToDisplay = $totalPages > 10 ? 10 : $totalPages;
                    if ($page > 5) {
                        $start = $page - 4;
                        $end = ($page + 5) > $totalPages ? $totalPages : ($page + 5);
                    } else {
                        $start = 1;
                        $end = $totalPages < 10 ? $totalPages : 10;
                    }
                    if ($page != 1) {
                        $prev = updatePageInURI($uri, $page, $page-1);
                        $first = updatePageInURI($uri, $page, 1);
                        echo '<li><a href="'.$prev.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
                        echo '<li><a href="'.$first.'">First</a></li>';
                    }
                    for ($i=$start; $i<$end+1; $i++) {
                        $className = '';
                        if ($i == $page) {
                            $className = 'active';
                        }
                        $pg = updatePageInURI($uri, $page, $i);
                        echo '<li class="'.$className.'"><a href="'.$pg.'">'. $i .'</a></li>';
                    }
                    if ($totalPages > $page) {
                        $next = updatePageInURI($uri, $page, $page+1);
                        $last = updatePageInURI($uri, $page, $totalPages);
                        echo '<li><a href="'.$last.'">Last</a></li>';
                        echo '<li> <a href="'.$next.'" aria-label="Next"> <span aria-hidden="true">&raquo;</span> </a> </li>';
                    }
                ?>
                </ul>
            </nav>
        </div>
    </body>
</html>
<?php
    pg_close($dbconn);
?>
