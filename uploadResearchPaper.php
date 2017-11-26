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
                    <input type="file" class="filestyle" data-placeholder="No file" name="fileToUpload[]"
                           id="fileToUpload"
                           multiple="multiple">
                    <button class="btn btn-default pull-right" type="submit" name="uploadSubmit" value="Upload">
                        <i class="glyphicon glyphicon-upload"> Upload</i>
                    </button>
                </div>
            </div>
        </form>

        <?php

        if (isset($_POST['uploadSubmit'])) {
            $department = (isset($_POST['selectCore']) && $_POST['selectCore'] != '') ? $_POST['selectCore'] : '';
            if (!file_exists($target_dir . $department)) {
                mkdir($target_dir . $department, 0777, true);
            }
            $target_dir = $target_dir . $department . "/";
            echo "<div class='well well-sm'>";
            $total = 0;
            $items = $_FILES["fileToUpload"]["name"];
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

                $uploadOk = 1;
                $pdfFileType = pathinfo($target_file, PATHINFO_EXTENSION);

                if (isset($_POST['uploadSubmit'])) {
                    if (file_exists($target_file)) {
                        echo "<span class='text-danger'>Sorry, file <b>" . $item . "</b> already exists.</span>";
                        echo "<br>";
                        $uploadOk = 0;
                    } else {
                        if ($pdfFileType != "pdf") {
                            echo "<span class='text-warning'>Sorry, only PDF files are allowed.</span>";
                            echo "<br>";
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 0) {
                            echo "<span class='text-warning'>Sorry, your file <b>" . $item . "</b> was not uploaded.</span>";
                            echo "<br>";
                        } else {
                            $item = preg_replace("/[^a-zA-Z0-9.]/","_",$item);
                            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$i], $target_dir . $item)) {
                                echo "<span class='text-success'>File: " . $item . " uploaded successfully.</span>";
                                echo "<br>";
                                $list = exec("python ./pyCalculation/uploadResearchPaper.py $item $department 2>&1", $output, $ret_code);

                                echo "<pre>";
                                foreach ($output as $val) {
                                    echo $val;
                                    echo "<br>";
                                }
                                echo "</pre>";
                                shell_exec('kill -KILL ProcessID');
                            } else {
                                echo "<span class='text-danger'>Sorry, there was an error uploading your file.</span>";
                                echo "<br>";
                            }

                        }
                    }
                }
            }
            echo "</div>";
        }



        ?>

    </div>

<?php
include("includes/footer.php");
?>