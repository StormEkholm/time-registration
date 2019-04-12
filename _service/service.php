<?php
header('Content-Type: application/json');
ini_set('display_errors',1);
error_reporting(E_ALL);
error_reporting(0);

include "../_config.php";
include "functions.php";

$action = $_POST["action"];
$errors = [];
$data = [];

// Actions
switch (strtolower($action)) {

    case "getprojects":

        $customerId = $_POST["customerId"];

        $sql = "SELECT id, navn FROM Tidsreg_Projekter WHERE kundeId='".$customerId."' ORDER by navn ASC";

        $stmt = sqlsrv_query( $conn, $sql );
        $result = [];
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        } else {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $result[] = $row;
            }
        }

        //Output
        print json_encode($result);
        break;

    case "getcustomers":
        $sql = "SELECT id, navn FROM Tidsreg_kunder WHERE omraadeId = 59 ORDER by navn ASC";

        $stmt = sqlsrv_query($conn, $sql);
        $result = [];
        if( $stmt === false) {
            die( print_r( sqlsrv_errors(), true) );
        } else {
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $result[] = $row;
            }
        }

        //Output
        print json_encode($result);
        break;

    case "createentry":
        $customer = $_POST['customer'];
        $project = $_POST['project'];
        $description = $_POST['description'];
        $hours = $_POST['hours'];
        $billed = $_POST['billed'];
        $originaldate = $_POST['date'];
        $date = date('Y-m-d H:i:s', strtotime($originaldate));
        $userid = $_SESSION['userId'];
        $areacode = 59;

        if(is_numeric($customer)){
             if(is_numeric($project)){
                 $description = "TEST-".$description;
                 $sql ="INSERT INTO Tidsreg_Registreringer (siteId, omraadeId, brugerId, kundeId, projektId, dato, beskrivelse, timer, fakturerbart) VALUES (50, '$areacode', '$userid', '$customer', '$project', '$date', '$description', '$hours', '$billed')";
                 $stmt = sqlsrv_query($conn, $sql);
                 if( $stmt === false) {
                     die( print_r( sqlsrv_errors(), true) );
                 } else {
                     $data['timereg']['status'] = 'success';
                     $data['timereg']['messsage'] = 'New timereg created';
                 }
             }else{
                 $description = "TEST-".$description;
                 $project .= "-TESTPROJECT";
                 $sql ="INSERT INTO Tidsreg_Projekter (kundeId, navn, oprettet) VALUES ('$customer', '$project', getdate())";
                 $stmt = sqlsrv_query($conn, $sql);
                 if( $stmt === false) {
                     die( print_r( sqlsrv_errors(), true) );
                 } else {
                     $data['project']['status'] = 'success';
                     $data['project']['messsage'] = 'New project created';
                 }

                 $sql = "SELECT TOP 1 id FROM Tidsreg_Projekter ORDER BY id DESC";
                 $stmt = sqlsrv_query($conn, $sql);
                 if( $stmt === false) {
                     die( print_r( sqlsrv_errors(), true) );
                 } else {
                     $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
                 }
                 // New project id
                 $newProjectId = $row['id'];

                 $sql ="INSERT INTO Tidsreg_Registreringer (siteId, omraadeId, brugerId, kundeId, projektId, dato, beskrivelse, timer, fakturerbart) VALUES (50,'$areacode', '$userid', '$customer', '$newProjectId', '$date', '$description', '$hours', '$billed')";
                 $stmt = sqlsrv_query($conn, $sql);
                 if( $stmt === false) {
                     die( print_r( sqlsrv_errors(), true) );
                 } else {
                     $data['timereg']['status'] = 'success';
                     $data['timereg']['messsage'] = 'New timereg created';
                 }
             }


        }else{
            $description = "TEST-".$description;
            $customer .= "-TESTUSER";
            $project .= "-TESTPROJECT";
            // Create new customer
            $sql ="INSERT INTO Tidsreg_Kunder (omraadeId, navn, oprettet) VALUES ('$areacode', '$customer', getdate())";
            $stmt = sqlsrv_query($conn, $sql);
            if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
            } else {
                $data['customer']['status'] = 'success';
                $data['customer']['messsage'] = 'New user created';
            }

            $sql = "SELECT TOP 1 id FROM Tidsreg_Kunder ORDER BY id DESC";
            $stmt = sqlsrv_query($conn, $sql);
            if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
            } else {
                $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
            }
            // New customer id
            $newCustomerid = $row['id'];

            $sql ="INSERT INTO Tidsreg_Projekter (kundeId, navn, oprettet) VALUES ('$newCustomerid', '$project', getdate())";
            $stmt = sqlsrv_query($conn, $sql);
            if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
            } else {
                $data['project']['status'] = 'success';
                $data['project']['messsage'] = 'New project created';
            }

            $sql = "SELECT TOP 1 id FROM Tidsreg_Projekter ORDER BY id DESC";
            $stmt = sqlsrv_query($conn, $sql);
            if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
            } else {
                $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
            }
            // New project id
            $newProjectId = $row['id'];

            $sql ="INSERT INTO Tidsreg_Registreringer (siteId, omraadeId, brugerId, kundeId, projektId, dato, beskrivelse, timer, fakturerbart) VALUES (50, '$areacode', '$userid', '$newCustomerid', '$newProjectId', '$date', '$description', '$hours', '$billed')";
            $stmt = sqlsrv_query($conn, $sql);
            if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
            } else {
                $data['timereg']['status'] = 'success';
                $data['timereg']['messsage'] = 'New timereg created';
            }

        }
        //Output
        print json_encode($data);
        break;

    case "login":

        if(isset($_POST['username']) && isset($_POST['password'])){
            // If username and password isset
            $username = $_POST['username'];
            $password = $_POST['password'];
            $sql = "SELECT id FROM Logins WHERE brugernavn='$username' AND kodeord=HashBytes('md5', '$password')";
            $stmt = sqlsrv_query( $conn, $sql );
            if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
            } else {
                $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
                $result = $row;
                if(!empty($result)){
                    // If user login credentials match a database row.
                    // Generate token as random string, and update that users token infomation.
                    $userid = $result['id'];
                    $token = generateRandomString();
                    $sql = "UPDATE Logins SET token = '$token', sidsteLogin = getdate() WHERE id = '$userid'";
                    $stmt = sqlsrv_query($conn, $sql);
                    if($stmt === false){
                        die( print_r( sqlsrv_errors(), true));
                    }else{
                        // All is good and the user is assign a session and a cookie.
                        $_SESSION['userId'] = $result['id'];
                        setcookie("token", $token, time() + (86400*365), "/"); // 86400 = 1 day
                        $output = array("status"=>"1", "msg"=>"Logged in");
                    }
                }else{
                    //If login fails, delete cookie
                    unset($_COOKIE['userId']);
                    setcookie('token', '', time() - 3600, '/');
                    $output = array("status"=>"0", "msg"=>"Login credentials does not match any user");
                }
            }
        }else{
            $output = array("status"=>"0", "msg"=>"Login credentials does not match any user");
        }
        //Output
        print json_encode($output);
        break;

    default:
        ?>{"status":"-1","msg":"Action not found..."}<?php
}
?>
