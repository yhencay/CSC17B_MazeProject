<!DOCTYPE html>
<!--
    accessed via ajax
    Push current current progressed
    Fetch opponent's progressed
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Pushing Progress</title>
    </head>
    <body>
        <?php
        $servername = "localhost";
        $username = "username";
        $password = "";
        $dbname = "test_dat";
        
        //check post for current progress
        $return_value=[];
        try{//POST_block
            $prog=json_decode($_POST['g_progress']);
            //https://www.w3schools.com/php/php_mysql_update.asp
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE schemea SET column='value' WHERE id=value";
            // Prepare statement
            $stmt = $conn->prepare($sql);
            // execute the query
            $stmt->execute();
            // echo a message to say the UPDATE succeeded
            array_push($return_value, 'POSTB_success');//updated game status for player
        } catch (Exception $ex) {
            array_push($return_value, 'POSTB_failure');
        }
        //check the other player's progress
        try{//FETCH_block
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT identity_test, entity_testcol FROM entity_test");
            $stmt->execute();

            // set the resulting array to associative
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            array_push($return_value, 'FETCHB_success');
            
        } catch (Exception $ex) {
            array_push($return_value, 'FETCHB_failure');
        }
        echo $return_value;//[post_status, fetch_status, fetch variables]
        ?>
    </body>
</html>
