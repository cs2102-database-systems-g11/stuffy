<?php include('/functions.php'); ?>
<html>
    <?php include('head.html') ?>
    <?php
        $dbPassword = getenv("DB_PASSWORD");
        $dbconn = pg_connect("host=localhost port=5432 dbname=stuffy_db user=postgres
        password=" . $dbPassword)
        or die('Could not connect: ' . pg_last_error());
    ?>

    <body>
        <?php include('header.php') ?>
        <div class='content'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Create Advertisement</h3>
                </div>
                <div class="panel-body">
                    <form method='post' class="user-profile">
                        <div class="form-group">
                            <label for="item-name" class="control-label">Item Name</label>
                            <input type="text" name='item-name' class="form-control" value='' placeholder="Item Name">
                        </div>
                        <div class="form-group">
                            <label for="description" class="control-label">Description</label>
                            <textarea type="text" name='description' class="form-control" id="input-description" placeholder="Description"></textarea>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="quantity" class="control-label">Quantity</label>
                                <input type="number" name='quantity' class="form-control" value='1' min='1' placeholder="Quantity">
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="type" class="control-label">Type</label>
                                <select class='selectpicker form-control'>
                                <option>Appliance</option>
                                <option>Book</option>
                                <option>Furniture</option>
                                <option>Tool</option>
                                <option selected>Others</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="form-group col-sm-6">
                                <label for="starting-bid" class="control-label">Starting Bid</label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" name='starting-bid' class="form-control" value='0' min='0' placeholder="Starting Bid">
                                </div>
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="buyout" class="control-label">Buyout <span class="label label-default">optional</span></label>
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number" name='buyout' class="form-control" min='0' placeholder="Buyout">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="bid-deadline" class="control-label">Bid Deadline</label>
                            <input type="text" name='bid-deadline' class="form-control" value='' placeholder="Bid Deadline (DD/MM/YYYY)">
                        </div>

                        <hr>
                        <div class="form-group">
                            <label for="pickup-location" class="control-label">Pickup Location</label>
                            <input type="text" name='pickup-location' class="form-control" value='' placeholder="Pickup Location">
                        </div>
                        <div class="form-group">
                            <label for="return-location" class="control-label">Return Location</label>
                            <div class="input-group">
                                <input type="text" name='return-location' class="form-control" value='' placeholder="Return Location">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id='match-pickup-btn' type="button"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> Match Pickup</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="return-date" class="control-label">Return Date</label>
                            <input type="text" name='return-date' class="form-control" value='' placeholder="Return Date (DD/MM/YYYY)">
                        </div>
                        <button class="btn btn-default" name='create-advertisement-submit' type="submit">Create Advertisement</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
