<?php
    include_once("dbConnect.php");
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    
    
    
    if(isset($_POST["recommendShopID"]) && isset($_POST["userAccountID"]) && isset($_POST["text"]) && isset($_POST["type"]) && isset($_POST["modifiedUser"]) && isset($_POST["modifiedDate"]))
    {
        $recommendShopID = $_POST["recommendShopID"];
        $userAccountID = $_POST["userAccountID"];
        $text = $_POST["text"];
        $type = $_POST["type"];
        $modifiedUser = $_POST["modifiedUser"];
        $modifiedDate = $_POST["modifiedDate"];
    }
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    
    //query statement
    $sql = "INSERT INTO RecommendShop(UserAccountID, Text, Type, ModifiedUser, ModifiedDate) VALUES ('$userAccountID', '$text', '$type', '$modifiedUser', '$modifiedDate')";
    $ret = doQueryTask($sql);
    if($ret != "")
    {
        mysqli_rollback($con);
        putAlertToDevice();
        echo json_encode($ret);
        exit();
    }
    //-----
    
    
    
    //do script successful
    //delete and insert ตัวเอง, insert คนอื่น สำหรับกรณี sync ให้ข้อมูล update เหมือนกันหมด
    mysqli_commit($con);
//    sendPushNotificationToAllDevices($_POST["modifiedDeviceToken"]);
    mysqli_close($con);
    
    
    
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
    $response = array('status' => '1', 'sql' => $sql);
    echo json_encode($response);
    exit();
?>
