<form class="container-fluid search-bar panel panel-default">
    <div class='panel-body'>
        <div class='main-search-wrapper'>
            <div class="input-group main-search">
                <input type="text" name='search-query' class="form-control" placeholder="Item name or description" autofocus/>
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
                    <span class="input-group-addon">Min bid</span>
                    <input type="number" name='min-bid' class="form-control" min='0'>
                </div>
                <div class="input-group col-sm-4">
                    <span class="input-group-addon">Max bid</span>
                    <input type="number" name='max-bid' class="form-control" min='0'>
                </div>
                <div class="input-group col-sm-4">
                    <span class="input-group-addon">Type</span>
                    <select class='selectpicker form-control' name='type'>
                        <option selected>All</option>
                        <option>Appliance</option>
                        <option>Book</option>
                        <option>Furniture</option>
                        <option>Tool</option>
                        <option>Others</option>
                    </select>
                </div>
                <div class="input-group col-sm-4">
                    <span class="input-group-addon">Min quantity</span>
                    <input type="number" name='min-quantity' class="form-control" min='0'>
                </div>
                <div class="input-group col-sm-4">
                    <span class="input-group-addon">Max buyout</span>
                    <input type="number" name='max-buyout' class="form-control" min='0'>
                </div>
                <div class="input-group col-sm-12">
                    <span class="input-group-addon">Location</span>
                    <input type="text" name='location' class="form-control" min='0'>
                </div>
            </div>
        </div>
    </div>
</form>
