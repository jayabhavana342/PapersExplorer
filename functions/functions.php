<?php

include("./includes/settings.php");

function coreDisplay()
{
    echo '<select class="selectpicker show-tick" title="Select Core" name="selectCore" data-live-search="true">';
    $keys = array_keys(json_decode(exec("python ./pyCalculation/getCores.py"), true));
    $i = 1;
    foreach ($keys as $r) {
        echo '<option data-icon="glyphicon glyphicon-folder-close pull-right" value="';
        echo $r;
        echo '">';
        echo $i . ".";
        echo $r;
        echo '</option>';
        $i++;
    }
    echo '</select>';
}

function getJSONdata($query, $department)
{
    if (isset($_GET["del"])) {
        $id = $_GET["del"];
        exec("python ./pyCalculation/deleteResearchPaper.py $id $department");
    }
    return json_decode(exec("python ./pyCalculation/searchResultsFromUser.py $query $department"), true);
}


function searchResults($department, $list, $totalResults = false)
{
    global $target_dir;
//    echo "<pre>";
//    var_dump($_SERVER);
//    var_dump($list);
//    echo "</pre>";
    if ($totalResults)
        echo $list["numFound"];
    else {
        echo "<div class='jumbotron'>";
        echo '<p class="text-right">';
        echo "<b>";
        echo "<span class='badge'> ";
        echo $list['numFound'];
        echo " Results Found</span>";
        echo "</b>";
        echo "</p>";
        $i = 0;

        for ($x = 0; $x < $list['numFound']; $x++) {
            echo '<div class="panel panel-default">';
            echo '<div class = "panel-heading pull-left">';

            if (isset($list['docs'][$x]['title'])) {
                echo "<h4 class='pull-left' style='font-family: \"Comic Sans MS\"'>";
                echo "<b>Title: </b>";
                if ($list['docs'][$x]['title'] != null) {
                    echo "<a href=''>" . $list['docs'][$x]["title"] . "</a>";
                } else {
                    echo "No Title";
                }
                echo "</h4>";
                $request = $_SERVER['REQUEST_URI'];
                echo '<a href="' . $request . '&del=' . $list['docs'][$x]['id'] . '" class="close pull-right" aria-label="Close"><span aria-hidden="true">&times;</span></a>';
            }
            echo "<br>";

            if (isset($list['docs'][$x]["author"])) {
                echo "<h5 class='pull-left' style='font-family: \"Comic Sans MS\"' align='justify'><b>";
                echo "Author: ";
                echo "</b></small>";
                if ($list['docs'][$x]["author"] != null) {
                    echo $list['docs'][$x]["author"];
                } else {
                    echo "No Author";
                }
                echo "</h5>";
            }
            echo "<br>";

            if (isset($list['docs'][$x]["abstract"])) {
                echo "<h5 class='pull-left' style='font-family: \"Comic Sans MS\"' align='justify'><b>";
                echo "Abstract: ";
                echo "</b>";
                if ($list['docs'][$x]["abstract"] != null) {
                    echo $list['docs'][$x]["abstract"];
                } else {
                    echo "No Abstract";
                }
                echo "</h5>";
            }
            echo "<br>";
            echo "</div>";

            echo '<div class="panel-body">';

            foreach ($list['docs'][$x]["_id"] as $l) {
                echo "<div class='row'></div>";
                echo "<h5 style='font-family: \"Comic Sans MS\"'><b>File Path: </b><a href='ResearchPapers/{$department}/{$l}' class='btn btn-default btn-sm' target='_blank'>{$target_dir}{$department}/{$l}</a></h5>";

                $annotation = '';
                if (isset($list['docs'][$x]["annotation"]))
                    $annotation = $list['docs'][$x]["annotation"];

                echo "<div class='panel panel-default' id='accordion" . $i . "'>" .
                        "<div class='panel-heading'>" .
                            "<h4 class='panel-title'>" .
                                "<a data-toggle='collapse' data-parent='#accordion" . $i . "' href='#collapse" . $i . "'>" .
                                "Annotate" .
                                "</a>" .
                            "</h4>" .
                        "</div>" .
                        "<div id='collapse" . $i . "' class='panel-collapse collapse'>" .
                            "<div class='panel-body'>" .
                                "<p>{$annotation}</p>" .
                                '<input type="hidden" name="id" id="id" value="'.$list['docs'][$x]["id"].'" />' .
                                '<input type="hidden" name="core" id="core" value="'.$department.'" />' .
                                "<a href='#' class='editAnnotation pull-right'><span class='glyphicon glyphicon-edit'></span></a>" .
                                "<br><hr>" .
                                "<button type=\"button\" class=\"btn btn-default btn-sm center-block\">" .
                                    "<span class=\"glyphicon glyphicon-import\"></span> Import Data to JSON File" .
                                "</button>" .
                            "</div>" .
                        "</div>" .
                    "</div>";

                echo "</div>";
                $i++;
            }
            echo "</div>";
        }
        echo "</div>";
    }
}

?>