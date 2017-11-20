<?php
include("includes/header.php");
include("functions/functions.php");
include("includes/settings.php");
?>

    <div class="jumbotron">
        <div class="row text-center">
            <form class="navbar-form">
                <div class="input-group">
                    <div class="input-group add-on">
                        <input type="text" class="form-control" placeholder="Enter core name"
                               id="create" name="create">
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit" name="submit" value="submit">Add Core</button>
                        </div>
                    </div>
                </div>
                <br>
                <small><b>***core name should not contain spaces and special characters.</b></small>
            </form>
        </div>
    </div>


<?php
if (isset($_GET['submit'])) {
    if ($_GET['submit'] == 'submit') {
        echo "<div class='jumbotron'>";
        $keys = array_keys($_GET);
        $coreName = '';
        if (isset($_GET["create"]) && $_GET["create"] != '') {
            $coreName = $_GET["create"];
            if (!file_exists($target_dir . $coreName)) {
                mkdir($target_dir . $coreName, 0777, true);
            }
            $realPath = escapeshellarg(realpath("./pyCalculation"));
            exec("python $realPath\coreOperations.py $keys[0] $coreName 2>&1", $output, $ret_code);
            echo "<pre>";
            foreach ($output as $val) {
                echo $val;
                echo "<br>";
            }
            echo "</pre>";
        } else {
            echo '<script type="text/javascript">alert("Please enter core name!!!");</script>';
        }
        echo "</div>";
    }
}
?>

<?php
include("includes/footer.php");
?>