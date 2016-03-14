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
                $limit = 30;
                $offset = 0;
                if (isset($_GET['page'])) {
                    $page = $_GET['page'];
                    $offset = $limit * ($page - 1);
                }
                if (isset($_GET['search-submit'])) {
                    $search_query = '%' . $_GET['search-query'] . '%';
                    $params = array($search_query);
                    $query = "SELECT u.username, a.owner, a.item_name, a.bid_deadline 
                        FROM advertise_item a, users u
                        WHERE 
                        (a.item_name LIKE $1 OR a.description LIKE $1)
                        AND a.bid_deadline > NOW() 
                        AND a.owner = u.email;";
                    $result = pg_query_params($dbconn, $query, $params);
                    if (pg_num_rows($result) == 0) {
                        $totalRows = 0;
                    } else {
                        $totalRows = pg_fetch_result($result, 0, 0);
                    }
                    array_push($params, $limit, $offset);
                    $query = "SELECT u.username, a.owner, a.item_name, a.bid_deadline 
                        FROM advertise_item a, users u
                        WHERE 
                        (a.item_name LIKE $1 OR a.description LIKE $1)
                        AND a.bid_deadline > NOW() 
                        AND a.owner = u.email
                        ORDER BY a.bid_deadline
                        LIMIT $2
                        OFFSET $3;";
                    $searchResults = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
                } else {
                    $params = array($limit, $offset);
                    $query = "SELECT u.username, a.owner, a.item_name, a.bid_deadline 
                        FROM advertise_item a, users u 
                        WHERE a.bid_deadline > NOW() 
                        AND a.owner = u.email 
                        ORDER BY a.bid_deadline 
                        LIMIT $1
                        OFFSET $2;";
                    $searchResults = pg_query_params($dbconn, $query, $params) or die("Query failed: " . pg_last_error());
                    $query = "SELECT count(*)
                        FROM advertise_item
                        WHERE bid_deadline > NOW();";
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
