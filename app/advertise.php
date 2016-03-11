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
                                <select class='selectpicker form-control' name='type'>
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
                            <input type="text" name='bid-deadline' class="form-control" value='' placeholder="Bid Deadline (YYYY-MM-DD)">
                        </div>

                        <hr>
                        <div class="form-group">
                            <label for="pickup-location" class="control-label">Pickup Location</label>
                            <input type="text" name='pickup-location' class="form-control" value='' placeholder="Pickup Location" id='pl'>
                        </div>
                        <div class="form-group">
                            <label for="return-location" class="control-label">Return Location</label>
                            <div class="input-group">
                                <input type="text" name='return-location' class="form-control" value='' placeholder="Return Location" id='rl'>
                                <span class="input-group-btn">
                                    <button class="btn btn-default" id='match-pickup-btn' type="button" onClick="copy();"><span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> Match Pickup</button>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="return-date" class="control-label">Return Date</label>
                            <input type="text" name='return-date' class="form-control" value='' placeholder="Return Date (YYYY-MM-DD)">
                        </div>
                        <button class="btn btn-default" name='create-advertisement-submit' type="submit">Create Advertisement</button>
                    </form>
                </div>
            </div>
        </div>
		<script>
		function copy()
		{
		  var pickup_loc = document.getElementById('pl');
		  var return_loc = document.getElementById('rl');
		  return_loc.value = pickup_loc.value;
		}
		</script>
		<?php if (isset($_GET['adv_item_success'])) {
            create_notification('success', 'Item advertisement created successfully.');
        } ?>
        <?php if(isset($_POST['create-advertisement-submit']))
        {
			$error = "";
			
            function validate_composite_field_exists($dbconn, $column1, $column2, $value1, $value2) {
                $params = array($value1, $value2);
                $query = "SELECT * FROM advertise_item WHERE " . $column1 . " = $1 AND " . $column2 . " = $2;";
                $result = pg_query_params($dbconn, $query, $params);
                if (pg_num_rows($result) > 0) {
                    return "Item name already exists. ";
                } else {
					return "";
				}
            }
			
			function validate_deadline() {
				$date = $_POST['bid-deadline'];
				$today = date("Y-m-d");
				if (empty($date)){
					return "Bidding deadline cannot be empty. ";
				}
				else if (!($date > $today)) {
					return "Bid deadline should be later than today. ";
				} else {
					return "";
				}
			}
			
			function validate_return_date() {
				$bid_deadline = $_POST['bid-deadline'];
				$return_date = $_POST['return-date'];
				if (empty($return_date)){
					return "Return date cannot be empty. ";
				}
				else if (!($return_date > $bid_deadline)) {
					return "Return date must be later than bidding deadline. ";
				} else {
					return "";
				}
			}
			
			function get_email_from_username($dbconn, $column, $value) {
				if (isset($_SESSION['username'])) {
					$params = array($value);
					$query = "SELECT email FROM users WHERE " . $column . " = $1;";
					return pg_fetch_result(pg_query_params($dbconn, $query, $params), 0, 0);
				} else {
					echo "<script>redirect('/login.php')</script>";
				}
			}
			
			function validate_item_name_not_empty(){
				if (empty($_POST['item-name'])) {
					return "Item name cannot be empty. ";
				} else {
					return "";
				}
			}
			
			$email = get_email_from_username($dbconn, 'username', $_SESSION['username']);
            $error .= validate_composite_field_exists($dbconn, 'owner', 'item_name', $email, $_POST['item-name']);
			$error .= validate_item_name_not_empty();
			$error .= validate_deadline();
			$error .= validate_return_date();
			if (!empty($error)) {
				create_notification('danger', $error);
				die();
			}
			
			// sets buyout to null if not entered
			if (empty($_POST['buyout'])) {
				$buyout = null;
			}
			
            $params = array($email, $_POST["item-name"], $_POST["type"], $_POST["description"], $_POST["starting-bid"], 
							$_POST["bid-deadline"], $buyout, $_POST["quantity"], $_POST["pickup-location"], 
							$_POST["return-location"], $_POST["return-date"]);
            $query = "INSERT INTO advertise_item VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10, $11)";
            $result = pg_query_params($dbconn, $query, $params);
            if ($result) {
                echo "<script>redirect('/advertise.php?adv_item_success=1')</script>";
            } else {
                create_notification('danger', 'Add advertisement error.');
                die("Query failed: " . pg_last_error());
            }
        }
        ?>
    </body>
    <?php
        pg_close($dbconn);
    ?>
    </body>
</html>
