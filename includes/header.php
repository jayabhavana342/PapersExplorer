<!DOCTYPE html>
<html lang="en">

<title>
    Papers Explorer
</title>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css">
    <link rel="stylesheet" href="css/searchStyles.css">
    <link rel="stylesheet"
          href="https://raw.githubusercontent.com/ysoftaoglu/GradientButtons/master/gradient-buttons/gradient-buttons.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-filestyle/1.2.1/bootstrap-filestyle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script>
        $(function () {
            $('#sub').redactor();
        });
        $(document).ready(function () {
            $(document).on('click', 'a.annotate', function () {
                var closestDiv = $(this).closest('a.annotate'); // also you can use $(this).parent()
                //closestDiv.fadeOut();
//                $('.annotationDisplay').not(closestDiv.next('.annotationDisplay')).hide();
                $('.annotationDisplay').not(closestDiv.next('.annotationDisplay')).hide();
//                $('.annotationDisplay').closest('div').not(closestDiv).show()
                closestDiv.next('.annotationDisplay').slideToggle(1000);


                $.ajax({
                    type: 'GET',
                    url: 'test.php',
                    success: function (data) {
                        //console.log(data);
                        $("#sub").text(data);
                        document.getElementById('annotate').style.color = 'Green';

                    },
                    error: function (request, status, error) {
                        document.getElementById('annotate').style.color = 'Red';
//                        alert(request.responseText);
                    }
                });
            });
        });


    </script>

    <style>
        .annotationDisplay {
            display: none;
        }

        .input-group-addon.success {
            color: rgb(255, 255, 255);
            background-color: rgb(92, 184, 92);
            border-color: rgb(76, 174, 76);
        }

        .input-group-addon.danger {
            color: rgb(255, 255, 255);
            background-color: rgb(217, 83, 79);
            border-color: rgb(212, 63, 58);
        }
    </style>
</head>

<body>

<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php" style="font-size:36px">
                    <b>
                        <i class="fa fa-newspaper-o"></i>
                        Papers Explorer
                    </b>
                </a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav navbar-left">
                    <?php
                    $urls = array(
//                        'Advanced Search' => './advancedSearch.php',
                        'Upload Document' => './uploadResearchPaper.php',
//                        'Delete Document' => './deleteResearchPaper.php',
                    );

                    foreach ($urls as $name => $url) {
                        print '<li><a href="' . $url . '">';
                        if ($name == 'Upload Document')
                            print '<span class="glyphicon glyphicon-upload"></span> ';
                        elseif ($name == 'Delete Document') {
                            print '<span class="glyphicon glyphicon-trash"></span> ';
                        } else
                            print '<span class="glyphicon glyphicon-search"></span> ';
                        print $name;
                        print '</a></li>';
                    }
                    ?>
                </ul>
                <ul class="nav navbar-nav navbar-right pager">
                    <li>
                        <a class="btn icon-btn btn-default" href="./addCore.php">
                            <span class="glyphicon btn-glyphicon glyphicon-plus img-circle text-primary"></span>
                            Add Core
                        </a>
                    </li>
                    <li>
                        <a class="btn icon-btn btn-default" href="./deleteCore.php">
                            <span class="glyphicon btn-glyphicon glyphicon-minus img-circle text-primary"></span>
                            Delete Core
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
