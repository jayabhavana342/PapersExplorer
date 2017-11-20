<?php
include("includes/header.php");
include("functions/functions.php");
include("includes/settings.php");
?>
    <div class="jumbotron">
        <div class="row text-center">
            <form action="" method="get" class="navbar-form">
                <div class="input-group">
                    <div class="input-group add-on">
                        <?php
                        coreDisplay();
                        ?>
                        <div class="input-group-btn">
                            <button class="btn btn-default" type="submit" name="submit" value="submit">Delete Core
                            </button>
                        </div>
                    </div>
                </div>
                <br>
            </form>
        </div>
    </div>

<?php
function deleteDir($path)
{
    if (is_dir($path) === true) {
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file) {
            deleteDir(realpath($path) . '/' . $file);
        }
        return rmdir($path);
    } else if (is_file($path) === true) {
        return unlink($path);
    }
    return false;
}

if (isset($_GET['submit'])) {
    if ($_GET['submit'] == 'submit') {
        $coreName = '';
        if (isset($_GET["selectCore"]) && $_GET["selectCore"] != '') {
            echo "<div class='jumbotron'>";
            $coreName = $_GET["selectCore"];

            deleteDir($target_dir . $coreName);

            $realPath = escapeshellarg(realpath("./pyCalculation"));
            $operation = "delete";

            exec("py $realPath\coreOperations.py $operation $coreName 2>&1", $output, $ret_code);

            echo "<pre>";
            foreach ($output as $val) {
                echo $val;
                echo "<br>";
            }
            echo "</pre>";

            echo "</div>";
        } else {
            echo '<script type="text/javascript">alert("Please choose core name from dropdown!!!");</script>';
        }
    }
}
?>

<?php
include("includes/footer.php");
?>