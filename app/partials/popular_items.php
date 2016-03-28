<div class="popular-items">
    <h4 style= "text-align:center;border-bottom:2px solid #DDD;padding-bottom:20px">Popular Items</h4>
    <div class="popular-items-list">
        <?php
            $query = 
                "SELECT b1.owner, b1.item_name, u.username, COUNT(*) AS num_bids
                FROM bid b1, users u 
                WHERE u.email = b1.owner
                GROUP BY b1.owner, b1.item_name, u.username
                ORDER BY COUNT(*) DESC 
                LIMIT 10";
            
            $result = pg_query($dbconn, $query) or die('Query failed: '.pg_last_error());
            $rowCount = 0;
            for($i=0;$i<pg_num_rows($result);$i++){
                $row = pg_fetch_array($result);
                $num_bids = $row['num_bids'];
                $owner = $row['owner'];
                $item_name = $row['item_name'];
                $owner_username = $row['username'];
                $item_url = '/item.php?user=' . $owner_username . '&name=' . rawurlencode($item_name);

        ?>

        <div class="thumbnail item-content">
            <a href='<?php echo $item_url ?>'><img src="http://placehold.it/300x200" alt="..."></a>
            <div class="caption">
                <h4 class='title'>
                    <a href='<?php echo $item_url ?>'><?php echo $item_name?></a>
                </h4>
                <p style="font-size:12px">Owner id: <?php echo $owner_username?></p>
                <p style="font-size:12px">Number of bids: <?php echo $num_bids?></p>
            </div>
        </div>
        <?php
            }
        ?> 
    </div>
</div>