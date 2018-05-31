<?php
    include_once("dbConnect.php");
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    
    for($i=141; $i<=141; $i++)
    {
        $memberContent = file_get_contents('./m/member' . $i . '.html');
        $arrText = explode('<table cellspacing="0" cellpadding="0" class="t1">',$memberContent);
//        echo $arrText[1];
        
        
        $sql = "INSERT INTO `TestMemberMini`(`Content`) VALUES ('$arrText[1]')";
        $ret = doQueryTask($sql);
        if($ret != "")
        {
            mysqli_rollback($con);
            putAlertToDevice();
            echo json_encode($ret);
            exit();
        }
    }
    
    
    
    
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
    $response = array('status' => '1', 'sql' => $sql);
    echo json_encode($response);
    exit();
    
//    $emailBody = str_replace("#codereset#",$codeReset,$emailBody);
?>
