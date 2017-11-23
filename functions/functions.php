<?php

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
    if (isset($_GET["del"])){
        $id = $_GET["del"];
        exec("python ./pyCalculation/deleteResearchPaper.py $id $department");
    }
    return json_decode(exec("python ./pyCalculation/searchResultsFromUser.py $query $department"), true);
}


/**
 * @param $department
 * @param $list
 * @param bool $totalResults
 */
function searchResults($department, $list, $totalResults = false)
{
//    echo "<pre>";
//    var_dump($_SERVER);
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

        for ($x = 0; $x < $list['numFound']; $x++) {
            echo '<div class="panel panel-default">';
            echo '<div class = "panel-heading pull-left">';

            if (isset($list['docs'][$x]['title'])) {
                echo "<h4 class='pull-left' style='font-family: \"Comic Sans MS\"'>";
                echo "<b>Title: </b>";
                if ($list['docs'][$x]['title'] != null) {
                    echo $list['docs'][$x]["title"];
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
                echo "<div class='row'>";
                echo "<h6><b>File Path: </b><code>{$_SERVER['DOCUMENT_ROOT']}/ResearchPapers/{$department}/{$l}</code></h6>";
                echo "<a href='ResearchPapers/{$department}/{$l}' class='btn btn-default btn-sm' target='_blank'>";
                echo "<span class='glyphicon glyphicon-open-file'></span>";
                echo " Open";
                echo "</a>";
//                echo "<a href='ResearchPapers/{$l}' download class='btn btn-default btn-sm' name='download'>";
//                echo "<span class='glyphicon glyphicon-download-alt'></span>";
//                echo " Download";
//                echo "</a>";
                echo '<a class="btn btn-default btn-sm annotate" id="annotate" data-id="">';
                echo "<span class='glyphicon glyphicon-edit'></span>";
                echo ' Annotate';
                echo '</a>';
                echo '<div class="annotationDisplay form_group">';
//                echo '<input type="hidden" class="hidden" value="">';
                echo '<textarea id="sub" rows="5" class="form_control"></textarea>';
                echo '<p></p>';
                echo "</div>";
                echo "</div>";
            }
            echo "</div>";

            echo "</div>";
        }
        echo "</div>";
    }
}

?>