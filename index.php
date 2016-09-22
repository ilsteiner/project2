<?php
error_reporting(E_ALL);       # Report Errors, Warnings, and Notices
ini_set('display_errors', 1); # Display errors on page (instead of a log file)
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <title>sad-boar-leaps</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/bootstrap-toggle.min.css">
        <link rel="stylesheet" href="css/main.css">
        <?php require 'vals.php' ?>
    </head>
    <body>
        <header id="header">
            <h1>sad-boar-leaps</h1>
            <h4>An XKCD-style password generator</h2>
        </header>        
        <div class="container">
            <div class="row">
                <div class="well well-lg" id="password-ouput">
                    <?php require 'logic.php' ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <form method="GET" action="index.php">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="num_words">How many words should be included?</label>
                                    <input
                                        required
                                        id="num_words"
                                        name="num_words"
                                        class="form-control"
                                        type="number"
                                        value="<?php echo isset($_GET["num_words"]) ? $_GET["num_words"] : 4;?>"
                                        min="<?php echo $MIN['words'] ?>"
                                        max="<?php echo $MAX['words'] ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="special_chars">How many special characters?</label>
                                    <input
                                        required
                                        id="special_chars"
                                        name="special_chars"
                                        class="form-control"
                                        type="number"
                                        value="<?php echo isset($_GET["special_chars"]) ? $_GET["special_chars"] : 0;?>"
                                        min="<?php echo $MIN['special_chars'] ?>"
                                        max="<?php echo $MAX['special_chars'] ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="with_number">Include a number?</label>
                                    <input
                                        id="with_number"
                                        value="Yes"
                                        name="with_number"
                                        class="form-control<?php echo isset($_GET["with_number"]) && $_GET["with_number"] == 'Yes' ? ' toggle-me' : ''?>"
                                        type="checkbox"
                                        data-toggle="toggle"
                                        data-width="70%"
                                        data-height="100px"
                                        data-offstyle="danger"
                                        data-onstyle="success"
                                        data-on="Yes"
                                        data-off="No"
                                        >
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label">What case should the password use?</label>
                                        <?php
                                            foreach ($CASE_TYPES as $index => $case_type) {
                                                echo '<label class="control-label radio" for="' . $case_type . '">';

                                                echo '<input 
                                                type = "radio" 
                                                name = "case_type"
                                                value = "' . $case_type . '"
                                                id = ' . $case_type . '"
                                                class = "case-type form-control"';

                                                if(isset($_GET["case_type"])) {
                                                    if(!in_array($_GET["case_type"], $CASE_TYPES)){
                                                        if($case_type == "lower"){
                                                            echo 'checked';
                                                        }
                                                    }
                                                    else if($case_type == $_GET["case_type"]) {
                                                        echo 'checked';
                                                    }
                                                }
                                                else {
                                                    if($case_type == "lower"){
                                                        echo 'checked';
                                                    }
                                                }

                                                echo '/>';

                                                echo fix_case($case_type,$case_type);

                                                echo '</label>';
                                            }
                                        ?>
                                        </div>
                                </div>
                            </div>       

                            <div class="col-md-12">
                                <input class="btn btn-primary btn-lg btn-block" type="submit" value="Generate!">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="js/bootstrap-toggle.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/script.js"></script>
    </body>
</html>