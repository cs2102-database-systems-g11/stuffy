<?php
    $searchQuery = '';
    $type = '';
    $minQuantity = '';
    $location = '';

    if (isset($_GET['search-query'])) {
        $searchQuery = htmlspecialchars($_GET['search-query']);
    }
    if (isset($_GET['type'])) {
        $type = htmlspecialchars($_GET['type']);
    }
    if (isset($_GET['min-quantity'])) {
        $minQuantity = htmlspecialchars($_GET['min-quantity']);
    }
    if (isset($_GET['location'])) {
        $location = htmlspecialchars($_GET['location']);
    }
?>
<form class="container-fluid search-bar panel panel-default">
    <div class='panel-body'>
        <div class='main-search-wrapper'>
            <div class="input-group main-search">
            <input type="text" name='search-query' class="form-control" placeholder="Item name or description" value="<?php echo $searchQuery; ?>" autofocus/>
                <span class="input-group-btn">
                    <button class="btn btn-default" name='search-submit' type="submit">Search!</button>
                </span>
            </div>
            <div class='adv-search-toggle'>
                <span class='adv-search-text'><a onClick='toggle_advanced_search(event)' href='#'>Show advanced search</a></span>
            </div>
        </div>
        <div class='advanced-search container-fluid'>
            <div class='row'>
                <div class="input-group col-sm-4">
                    <span class="input-group-addon">Type</span>
                    <select class='selectpicker form-control' name='type'>
                        <option selected>All</option>
                        <option <?php echo $type == 'Appliance' ? 'selected' : '' ?>>Appliance</option>
                        <option <?php echo $type == 'Book' ? 'selected' : '' ?>>Book</option>
                        <option <?php echo $type == 'Furniture' ? 'selected' : '' ?>>Furniture</option>
                        <option <?php echo $type == 'Tool' ? 'selected' : '' ?>>Tool</option>
                        <option <?php echo $type == 'Others' ? 'selected' : '' ?>>Others</option>
                    </select>
                </div>
                <div class="input-group col-sm-4">
                    <span class="input-group-addon">Min quantity</span>
                    <input type="number" name='min-quantity' value="<?php echo $minQuantity; ?>" class="form-control" min='0'>
                </div>
                <div class="input-group col-sm-4">
                    <span class="input-group-addon">Max buyout</span>
                    <input type="number" name='max-buyout' class="form-control" value="<?php echo $maxBuyout; ?>" min='0'>
                </div>
                <div class="input-group col-sm-12">
                    <span class="input-group-addon">Location</span>
                    <input type="text" name='location' class="form-control" value="<?php echo $location ?>" min='0'>
                </div>
            </div>
        </div>
    </div>
</form>
