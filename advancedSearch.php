<?php
include("includes/header.php");
include("functions/functions.php");
?>

    <div class="jumbotron">
        <form method="get">
            <div class="input-group">
                <?php
                coreDisplay();
                ?>
                <span class="input-group-addon success beautiful">
                            <input type="checkbox" name="contentCheckBox" id="contentCheckBox"
                                   value="content" <?php if (isset($_GET['contentCheckBox'])) if ($_GET['contentCheckBox'] == 'content') echo 'checked="checked"'; ?>>
                </span>
                <!--                        <span class="input-group-addon danger beautiful">-->
                <!--                            <input type="checkbox" name="contentNotCheckBox" id="contentNotCheckBox" value="contentNot" -->
                <?php //if (isset($_GET['contentNotCheckBox']))if ($_GET['contentNotCheckBox'] == 'contentNot') echo 'checked="checked"';?><!-- onclick="//changeTextBox00()"/>-->
                <!--                        </span>-->
                <input type="text" class="form-control" placeholder="Enter Content Search Term" name="contentSearchText"
                       id="contentSearchText"
                       value="<?php if (isset($_GET['contentSearchText'])) echo $_GET["contentSearchText"]; ?>" <?php //if (!($_GET['contentCheckBox'] == 'content')) echo 'disabled="disabled"'; if (!($_GET['contentNotCheckBox'] == 'contentNot')) echo 'disabled="disabled"';?>>
            </div>

            <br>

            <button type="button" class="btn btn-default" data-toggle="collapse" data-target="#demo">Advanced Search
            </button>

            <div id="demo" class="collapse">
                <br>
                <div class="input-group">
                    <span class="input-group-addon success beautiful">
                        <input type="checkbox" name="titleCheckBox" id="titleCheckBox"
                               value="title" <?php if (isset($_GET['titleCheckBox'])) if ($_GET['titleCheckBox'] == 'title') echo 'checked="checked"'; ?>/>
                    </span>
                    <span class="input-group-addon danger beautiful">
                        <input type="checkbox" name="titleNotCheckBox" id="titleNotCheckBox"
                               value="titleNot" <?php if (isset($_GET['titleNotCheckBox'])) if ($_GET['titleNotCheckBox'] == 'titleNot') echo 'checked="checked"'; ?>/>
                    </span>

                    <input type="text" class="form-control" placeholder="Enter Title Search Term" name="titleSearchText"
                           id="titleSearchText"
                           value="<?php if (isset($_GET['titleSearchText'])) echo $_GET["titleSearchText"]; ?>" <?php //if (!($_GET['titleCheckBox'] == 'title')) echo 'disabled="disabled"';?>>
                </div>

                <br/>
                <div class="row">
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4 text-center">
                        <div class="btn-group" data-toggle="buttons">
                            <label class="btn btn-default">
                                <input type="radio" name="options" id="options1" value="AND"> AND
                            </label>
                            <label class="btn btn-default">
                                <input type="radio" name="options" id="options2" value="OR"> OR
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4"></div>
                </div>

                <br/>
                <div class="input-group">
                    <span class="input-group-addon success beautiful">
                        <input type="checkbox" name="authorCheckBox" id="authorCheckBox"
                               value="author" <?php if (isset($_GET['authorCheckBox'])) if ($_GET['authorCheckBox'] == 'author') echo 'checked="checked"'; else echo " "; ?> />
                    </span>
                    <span class="input-group-addon danger beautiful">
                        <input type="checkbox" name="authorNotCheckBox" id="authorNotCheckBox"
                               value="authorNot" <?php if (isset($_GET['authorNotCheckBox'])) if ($_GET['authorNotCheckBox'] == 'authorNot') echo 'checked="checked"'; else echo " "; ?> />
                    </span>
                    <input type="text" class="form-control" placeholder="Enter Author Search Term"
                           name="authorSearchText" id="authorSearchText"
                           value="<?php if (isset($_GET['authorSearchText'])) echo $_GET["authorSearchText"]; ?>" <?php //if (!($_GET['authorCheckBox'] == 'author')) echo 'disabled="disabled"';?>>
                </div>
            </div>
            <br/>

            <div class="row text-center">
                <button class="btn btn-default" type="submit" name="searchSubmit" value="Submit">
                    <i class="glyphicon glyphicon-search"> Search</i>
                </button>
            </div>
        </form>
    </div>


<?php
//        print_r($_GET);

$titleWord = '';
$titleWordNot = '';
$andorWord = '';
$authorWord = '';
$authorWordNot = '';
$searchString = '';

if (isset($_GET['titleCheckBox'])) {
    if ($_GET['titleCheckBox'] == 'title') {
        global $titleWord;
        $titleWord = $_GET['titleSearchText'];
        if (preg_match('/\s/', $titleWord)) {
            $titleWord = str_replace(' ', '*', $titleWord);
        } elseif (strpos($titleWord, '"')) {
            $titleWord = str_replace('"', '\"', $titleWord);
        }
        $titleWord = 'title:' . $titleWord;
    }
} elseif (isset($_GET['titleNotCheckBox'])) {
    if ($_GET['titleNotCheckBox'] == 'titleNot') {
        global $titleWord;
        $titleWord = $_GET['titleSearchText'];
        if (preg_match('/\s/', $titleWord)) {
            $titleWord = str_replace(' ', '\*', $titleWord);
        } elseif (strpos($titleWord, '"')) {
            $titleWord = str_replace('"', '\"', $titleWord);
        }
        $titleWordNot = $titleWord;
        $titleWordNot = 'NOT\(title:' . $titleWord;
        $titleWord = 'NOT\(title:' . $titleWord . '\)';
    }
}

if (isset($_GET['options'])) {
    if ($_GET['options'] == 'AND') {
        global $andorWord;
        $andorWord = '+AND+';
    } elseif ($_GET['options'] == 'OR') {
        global $titleWord;
        $andorWord = '+OR+';
    }
}

if (isset($_GET['authorCheckBox'])) {
    if ($_GET['authorCheckBox'] == 'author') {
        global $authorWord;
        $authorWord = $_GET['authorSearchText'];
        if ($authorWord != '') {
            if (preg_match('/\s/', $authorWord)) {
                $authorWord = str_replace(' ', '\*', $authorWord);
            } elseif (strpos($authorWord, '"')) {
                $authorWord = str_replace('"', '\"', $authorWord);
            }
            $authorWord = '\(author:' . $_GET['authorSearchText'] . '\)';
        }
    }
} elseif (isset($_GET['authorNotCheckBox'])) {
    if ($_GET['authorNotCheckBox'] == 'authorNot') {
        global $authorWord;
        $authorWord = $_GET['authorSearchText'];
        if ($authorWord != '') {
            if (preg_match('/\s/', $authorWord)) {
                $authorWord = str_replace(' ', '\*', $authorWord);
            } elseif (strpos($authorWord, '"')) {
                $authorWord = str_replace('"', "\"", $authorWord);
            }
            $authorWordNot = $authorWord;
            $authorWord = '+NOT+\(author:' . $_GET['authorSearchText'] . '\)';
            $authorWordNot = 'NOT+author:' . $_GET['authorSearchText'] . '\)';
        }
    }
}

if (isset($_GET['authorNotCheckBox']) && $_GET['titleNotCheckBox']) {
    if ($_GET['authorNotCheckBox'] && $_GET['titleNotCheckBox']) {
        $searchString = $titleWordNot . '+' . $authorWordNot;
    }
} elseif (isset($_GET['titleNotCheckBox'])) {
    if ($_GET['titleNotCheckBox']) {
        $searchString = $titleWord . $andorWord . $authorWord;
    }
} elseif (isset($_GET['authorNotCheckBox'])) {
    if ($_GET['authorNotCheckBox']) {
        $searchString = $titleWord . $authorWord;
    }
} else {
    $searchString = $titleWord . $andorWord . $authorWord;
}

if (isset($_GET['contentCheckBox'])) {
    global $searchString;
    $searchString = $_GET['contentSearchText'];
}

        echo "<br>Title String-  $titleWord <br>";
//        echo "<br>And/OR String-  $andorWord <br>";
//        echo "<br>Author String-  $authorWord<br>";
        echo "<br>Original String-  $searchString<br>";

$department = (isset($_GET['selectCore']) && $_GET['selectCore'] != '') ? $_GET['selectCore'] : '';
echo $department;
$query = $searchString;
echo $query;

searchResults($department, getJSONdata($query, $department));

?>

    </div>

<?php
include("includes/footer.php");
?>