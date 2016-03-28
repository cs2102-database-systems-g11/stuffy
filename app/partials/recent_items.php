<div  class='recent-items'>
    <h4 style= "text-align:center;border-bottom:2px solid #DDD;padding-bottom:20px">Recent Items</h4>
    <div class='recent-items-list'>    
    <?php
        $query = 
            "SELECT a.owner, a.item_name, u.username 
            FROM advertise_item a, users u
            WHERE a.owner = u.email";
            $result = pg_query($dbconn, $query) or die('Query failed: '.pg_last_error());
            $resultsArray = pg_fetch_all($result);
            $numRows = pg_num_rows($result);
            if($numRows < 10){
                $var = $numRows;
            }else{
                $var = 10;
            }
            for($i=0;$i<$var;$i++){

                $num_bids = $resultsArray[$numRows-$i-1][num_bids];
                $owner = $resultsArray[$numRows-$i-1][owner];
                $item_name = $resultsArray[$numRows-$i-1][item_name];
                $owner_username = $resultsArray[$numRows-$i-1][username];
                $item_url = '/item.php?user=' . $owner_username . '&name=' . rawurlencode($item_name);

        ?>

        <div class="thumbnail item-content">
            <a href='<?php echo $item_url ?>'><img src="http://placehold.it/300x200" alt="..."></a>
            <div class="caption">
                <h4 class='title'>
                    <a href='<?php echo $item_url ?>'><?php echo $item_name?></a>
                </h4>
                <p style="font-size:12px">Owner id: <?php echo $owner_username?></p>
            </div>
        </div>
        <?php
            }
        ?> 
    </div> 
</div>   