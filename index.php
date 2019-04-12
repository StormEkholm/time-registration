<?php
include "_config.php";
//header("Location: /test.php");
if (!isset($_SESSION["userId"])) {
    //Check for cookie
    if (isset($_COOKIE["token"])) {
        $cookie = $_COOKIE['token'];
        //Try login with cookie - call DB and see if token matches - get users ID back
        $sql = "SELECT id FROM Logins WHERE token = '$cookie'";
        $stmt = sqlsrv_query($conn, $sql);
        if( $stmt === false) {
            die(print_r(sqlsrv_errors(), true));
        } else {
            $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
            // print_r($row);
            // print_r(count($row));
            if(count($row) > 0){                                //If all good - set Session["userId"]
                $_SESSION['userId'] = $row['id'];
            }else{                                              //If login fails, delete cookie
                unset($_COOKIE['userId']);
                setcookie('token', '', time() - 3600, '/');
            }
        }
    }
}

//print_r($_COOKIE["token"]);
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta charset="utf-8">
        <title></title>

        <link rel="manifest" href="manifest.json">

        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="application-name" content="SnapTime">
        <meta name="apple-mobile-web-app-title" content="SnapTime">
        <meta name="msapplication-starturl" content="/index.php">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">

        <link rel="icon" type="image/png" sizes="405x88" href="/_img/snaptime_logo.png">
        <link rel="apple-touch-icon" type="image/png" sizes="405x88" href="/_img/snaptime_logo.png">

        <!-- CSS -->
        <link rel="stylesheet" type="text/css" href="/_css/style.css" />
        <link rel="stylesheet" type="text/css" href="/_css/select2.min.css" />
        <link rel="stylesheet" type="text/css" href="/_css/jquery-ui.min.css" />

        <!-- Scripts -->
        <!-- <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script> -->
        <script type="text/javascript" src="/_js/sources/jquery-3.3.1.min.js"></script>
        <script type="text/javascript" src="/_js/functions.js"></script>
        <script type="text/javascript" src="/_js/validation.js"></script>
        <script type="text/javascript" src="/_js/sources/select2.full.min.js"></script>
        <script type="text/javascript" src="/_js/sources/jquery-ui.min.js"></script>
    </head>
    <body>
        <div class="success-window">
            <p>Tidsregistrering er nu oprettet</p>
        </div>
        <div class="error-window">
            <p></p>
        </div>
        <div class="container">
            <script type="text/javascript">
                var isIE = /*@cc_on!@*/false || !!document.documentMode;
                if(isIE){
                    $("body").prepend("<a id='ieClose' class='boxclose'></a>");
                }

            </script>

            <?php
            if(isset($_SESSION["userId"])){
            ?>
                <img id="snaptime-logo" src="/_img/snaptime_logo.png" />
                <form class="" id="timemanage-form" method="post" onsubmit="return false;" autocomplete="off">
                    <div class="form-field">
                        <div class="info-box">
                            <label></label><span id="newCustomer"></span>
                        </div>
                        <label for="customer-select">Kunde: </label>
                        <select id="customer-select" class="required" title="Kunde" data-minlength=1>
                        </select>
                    </div>
                    <div class="form-field">
                        <div class="info-box">
                            <label></label><span id="newProject"></span>
                        </div>
                        <label for="project-select">Projekt:</label>
                        <select id="project-select" class="required validateLength" title="Projekt" data-minlength=1>

                        </select>
                    </div>
                    <div class="form-field">
                        <label></label>
                        <input id="form-datepicker" class="required validateDate" title="Dato" type="text" placeholder="Vælg en dato" />
                    </div>
                    <div class="form-field">
                        <label></label>
                        <input id="form-description" class="required validateLength" data-minlength=10 title="Beskrivelse" placeholder="Beskrivelse" />
                    </div>
                    <div class="form-field">
                        <label for="form-hours" class="hour-labels">Timer:</label>
                        <input id="form-hours" class="required validateDecimal" type="text" name="form-hours" value="" placeholder="2.50" title="Timer" />
                    </div>
                    <div class="form-field">
                        <label for="form-hours-billed" class="hour-labels">Fakturerbar:</label>
                        <input id="form-hours-billed" class="required validateDecimal" type="text" name="form-hours-billed" value="" placeholder="2.50" title="Fakturerbar" />
                    </div>
                </form>
                <div class="submit-container">
                    <button class="form-btn" id="form-post">Gem</button>
                </div>
                <script type="text/javascript">
                    var typeTimeout;
                    $(document).ready(function(){
                        // Formate datepicker input to jquery ui datepicker.

                        // ------------ Datepicker control schema -------------

                        // PAGE UP: Move to the previous month.
                        // PAGE DOWN: Move to the next month.
                        // CTRL + PAGE UP: Move to the previous year.
                        // CTRL + PAGE DOWN: Move to the next year.
                        // CTRL + HOME: Open the datepicker if closed.
                        // CTRL/COMMAND + HOME: Move to the current month.
                        // CTRL/COMMAND + LEFT: Move to the previous day.
                        // CTRL/COMMAND + RIGHT: Move to the next day.
                        // CTRL/COMMAND + UP: Move to the previous week.
                        // CTRL/COMMAND + DOWN: Move the next week.
                        // ENTER: Select the focused date.
                        // CTRL/COMMAND + END: Close the datepicker and erase the date.
                        // ESCAPE: Close the datepicker without selection.
                        $( "#form-datepicker" ).datepicker();
                        $( "#form-datepicker" ).datepicker( "option", "dateFormat", "dd-mm-yy" );
                        $("#project-select").select2();

                        // Initial get of customers for selection box
                        getCustomers(function(json){
                            $.each(json, function(key, value){
                                $("#customer-select").append('<option value="'+ value.id +'">'+  value.navn +'</option>')
                            });
                            // Change default selection ui to select2 ui
                            $("#customer-select").select2({
                                tags: true,
                                selectOnClose: true,
                                allowClear: true,
                                dropdownCssClass : 'bigdrop'
                            });
                        });

                        // Validation of inputs live
                        $('#timemanage-form input.required').keyup(function(){
                            if ( validateForm('#timemanage-form',false) ) {

                            } else {

                            }
                        }).trigger('keyup');

                        // Fetching json object with projects
                        $("#customer-select").on('select2:select', function () {
                            $("#project-select").empty();
                            let selected = $('#customer-select').select2('data')[0].id;
                            // If selected value is not a number, signal to the client that a new customer will be created
                            if($.isNumeric(selected) === false){
                                $("#newCustomer").text("En ny kunde oprettes");
                                $("#project-select").select2({
                                    tags: true,
                                    selectOnClose: true,
                                    allowClear: true
                                });
                                $("#newProject").text("Ingen projekter, opret nyt");
                                $('#project-select').val('').trigger('change');
                            }else{
                                $("#newCustomer").text("");
                                loadProjects();
                                $('#project-select').val('').trigger('change');
                            }

                        });

                        // Change default ui on selection for projects to select2 ui
                        $("#project-select").on('select2:select', function (e) {
                            let selected = $('#project-select').select2('data')[0].id;
                            if($.isNumeric(selected) === false){
                                $("#newProject").text("Et nyt projekt oprettes");
                            }else{
                                $("#newProject").text("");
                            }
                        });

                        // Timereg form post submit
                        $("#form-post").click(function(){
                            if (validateForm('#timemanage-form',true)){
                                let customer = $("#customer-select").select2('data')[0].element.value;
                                let project = $("#project-select").select2('data')[0].element.value;
                                let description = $("#form-description").val();
                                let hours = $("#form-hours").val();
                                let billed = $("#form-hours-billed").val();
                                let date = $("#form-datepicker").datepicker("option", "dateFormat", 'yy-mm-dd').val();
                               createEntry(customer, project, date, description, hours, billed, function(json){
                                   //Success
                                   // On success clear all inputs and validation classes.
                                   $('#customer-select').val('').trigger('change');
                                   $('#project-select').val('').trigger('change');
                                   $("#form-description").val("").removeClass("valid");
                                   $("#form-hours").val("").removeClass("valid");
                                   $("#form-hours-billed").val("").removeClass("valid");
                                   $("#form-datepicker").val("").removeClass("valid");
                                   // Success toast for the client.
                                   $(".success-window").slideDown(400);
                                   setTimeout(function(){
                                       $(".success-window").slideUp(400);
                                   }, 2000);
                                   console.log(json);
                                   console.log("Timereg created");
                               });
                            }else{
                                console.log("Validation failed");
                            }
                        });

                        $("#form-anull").click(function(){
                            $('#customer-select').val('').trigger('change');
                            $('#project-select').val('').trigger('change');
                            $('#project-select').empty();
                            $("#form-description").val("").removeClass("valid");
                            $("#form-hours").val("").removeClass("valid");
                            $("#form-hours-billed").val("").removeClass("valid");
                            $("#form-datepicker").val("").removeClass("valid");
                        });
                    });

                </script>

            <?php
            }else{
            ?>
                <img id="snaptime-logo" src="/_img/snaptime_logo.png" />
                <div class="login-form-container">
                    <form class="" id="login-form" method="post" onsubmit="return false;">
                        <input id="form-login-username" class="required validateEmail" type="text" name="form-login-username" value="" title="Brugernavn" placeholder="Brugernavn" />
                        <input id="form-login-password" class="required validateLength" type="password" name="form-login-password" value="" title="Kodeord" placeholder="Kodeord" data-minlength=5 />
                        <button id="form-btn-login">Login</button>
                    </form>
                </div>

                <a href="/" class="logo"><span class="bold">Internet</span><span class="regular">Factory</span><span class="super">®</span></a>

                <script type="text/javascript">
                $(document).ready(function(){
                    // Validation of inputs live
                    $('#login-form input.required').keyup(function(){
                        if ( validateForm('#login-form',false) ) {

                        } else {

                        }
                    }).trigger('keyup');

                    $("#form-btn-login").click(function(){
                        if(navigator.onLine){
                            if (validateForm('#timemanage-form',true)){
                                let username = $("#form-login-username").val();
                                let password = $("#form-login-password").val();
                                login(username, password, function(json){
                                    let t = new Date().getTime();
                                    window.location.href = "/";
                                });
                            }
                        }else{
                            console.log("offline cant log in");
                            $(".error-window").text("Ingen internet adgang, login er deaktiveret");
                            $(".error-window").slideDown(400);
                            setTimeout(function(){
                                $(".error-window").slideUp(400);
                                $(".error-window").text("");
                            }, 5000);
                        }
                    });
                });
                </script>
            <?php
            }
            ?>
        </div>
    </body>
</html>
