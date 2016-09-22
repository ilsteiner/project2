<?php
error_reporting(E_ALL);       # Report Errors, Warnings, and Notices
ini_set('display_errors', 1); # Display errors on page (instead of a log file)
?>
<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="utf-8">
        <title>Project 2</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/bootstrap-toggle.min.css">
        <link rel="stylesheet" href="css/main.css">
        <?php require 'vals.php' ?>;
        <?php require 'logic.php' ?>;
    </head>
    <body>
        <div class="container">
            <div class="<?php //echo (count($_GET) > 0 ? 'hidden' : 'row');?>">
                <form method="GET" action="index.php">
                    <div class="form-group">
                        <label class="control-label" for="num_words">How many words should be included?</label>
                        <input
                            required
                            id="num_words"
                            name="num_words"
                            class="form-control"
                            type="number"
                            value="4"
                            min="<?php echo $MIN['words'] ?>"
                            max="<?php echo $MAX['words'] ?>">
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="max_length">What is the maximum length for the password?</label>
                        <input 
                            id="max_length"
                            name="max_length"
                            class="form-control"
                            type="number"
                            min="<?php echo $MIN['chars'] ?>"
                            max="<?php echo $MAX['chars'] ?>"
                            placeholder="For no maximum length, leave this blank or enter 0">
                    </div>

                    <div class="form-group">
                        <input
                            id="with_number"
                            value="Yes"
                            name="with_number"
                            class="form-control"
                            type="checkbox"
                            data-toggle="toggle"
                            data-size="large"
                            data-offstyle="danger"
                            data-onstyle="success"
                            data-on="Include<br>a number"
                            data-off="Do not<br>include<br>a number"><br>
                    </div>
                    
                    <div class="form-group">
                        <label class="control-label" for="special_chars">How many special characters?</label>
                        <input
                            required
                            id="special_chars"
                            name="special_chars"
                            class="form-control"
                            type="number"
                            value="0"
                            min="<?php echo $MIN['special_chars'] ?>"
                            max="<?php echo $MAX['special_chars'] ?>">
                    </div>

                    <div class="form-group">
                        <label>What case should the password use?</label>
                        <?php
                            foreach ($CASE_TYPES as $case_type) {
                                echo '<label class="control-label radio" for="' . $case_type . '">';

                                echo '<input 
                                type = "radio" 
                                name = "case_type"
                                value = "' . $case_type . '"
                                id = ' . $case_type . '"
                                class = "form-control"' . ($case_type == "lower" ? 'checked' : '') .
                                '/>';

                                echo fix_case($case_type,$case_type);

                                echo '</label>';
                            }
                        ?>
                    </div>                     

                    <input class="btn btn-primary" type="submit" value="Generate!">
                </form>
            </div>
            <div class="row">
                
            </div>
        </div>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
        <script src="js/bootstrap-toggle.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/script.js"></script>
    </body>
</html>