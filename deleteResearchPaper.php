<?php
include("includes/header.php");
include("functions/functions.php");
include("includes/settings.php");
?>

    <div class="jumbotron">
        <form method="post" enctype="multipart/form-data">
            <br>
            <?php
            coreDisplay();
            ?>
            <div class="input-group">
                <div class="input-group-btn">
                    <input type="file" class="filestyle" data-placeholder="No file" name="fileToDelete[]"
                           id="fileToDelete"
                           multiple="multiple">
                    <button class="btn btn-default pull-right" type="submit" name="deleteSubmit" value="Delete">
                        <i class="glyphicon glyphicon-trash"> Delete</i>
                    </button>
                </div>
            </div>
        </form>

        <?php
        if (isset($_POST['deleteSubmit'])) {
            $department = (isset($_POST['selectCore']) && $_POST['selectCore'] != '') ? $_POST['selectCore'] : '';
            if (file_exists($target_dir . $department)) {
                $target_dir = $target_dir . $department . "/";
                echo "<div class='well well-sm'>";
                $total = 0;
                $items = $_FILES["fileToDelete"]["name"];
                if (isset($items)) {
                    if ($items[0] == '') {
                        echo "<span class='text-danger'>Please select the files.</span>";
                    } else {
                        if (count($items) > 0)
                            $total = count($items);
                    }
                }
                for ($i = 0; $i < $total; $i++) {
                    $target_file = '';
                    $item = $items[$i];
                    if (isset($item))
                        $target_file = $target_dir . basename($item);

                    if (file_exists($target_file)) {
                        unlink($target_file);
                        $list = exec("python ./pyCalculation/deleteResearchPaper.py $item $department");
                        echo "<span class='text-success'>File $item has been deleted.</span>";
                    }

                }
                echo "</div>";
            }else{
                echo "<span class='text-danger'>File doesn't exist.</span>";
            }

        }


        ?>

    </div>

<?php
include("includes/footer.php");
?>