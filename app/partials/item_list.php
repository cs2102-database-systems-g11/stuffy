<div class='container-fluid grid-list'>
    <div class='row margin-bottom-20'>
        <?php
        while($searchResults && $row = pg_fetch_array($searchResults)) {
            $bid_deadline = $row['bid_deadline'];
            $owner = $row['owner'];
            $item_name = $row['item_name'];
            $owner_username = $row['username'];
            $item_url = '/item.php?user=' . $owner_username. '&name=' . $item_name;

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
                        <a href='<?php echo $item_url ?>'><?php echo $item_name?></a>
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
