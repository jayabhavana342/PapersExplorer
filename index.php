<?php
include("includes/header.php");
include("functions/functions.php");
?>

    <div class="jumbotron">
        <div class="row text-center">
            <form class="navbar-form">
                <div class="input-group">
                    <?php
                    coreDisplay();
                    ?>
                    <div class="input-group add-on">
                        <input type="text" class="form-control" placeholder="Enter Query"
                               id="searchText" name="searchText">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit" name="submit" value="submit">
                                <i class="glyphicon glyphicon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<?php

if (isset($_GET['submit'])) {

    $department = (isset($_GET['selectCore']) && $_GET['selectCore'] != '') ? $_GET['selectCore'] : '';
    $query = (isset($_GET['searchText']) && ($_GET['searchText'] != '')) ? $_GET['searchText'] : "*:*";

    if (preg_match('/\s/', $query)) {
        $query = str_replace(' ', '+', $query);
    }
    if (preg_match('(")', $query)) {
        $query = str_replace('"', '\"', $query);
    }
    searchResults($department, getJSONdata($query, $department));
}


?>

    </div>
<?php
include("includes/footer.php");
?>