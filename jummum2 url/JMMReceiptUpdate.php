<?php
    include_once("dbConnect.php");
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    
    

    if(isset($_POST["receiptID"]) && isset($_POST["status"]) && isset($_POST["branchID"]) && isset($_POST["modifiedUser"]) && isset($_POST["modifiedDate"]))
    {
        $receiptID = $_POST["receiptID"];
        $status = $_POST["status"];
        $branchID = $_POST["branchID"];
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
    
    
    

    
    
    
    //receipt
    $sql = "update receipt set status = '$status',statusRoute=concat(statusRoute,',','$status'), modifiedUser = '$modifiedUser', modifiedDate = '$modifiedDate' where receiptID = '$receiptID'";
    $ret = doQueryTask($sql);
    if($ret != "")
    {
        mysqli_rollback($con);
        putAlertToDevice();
        echo json_encode($ret);
        exit();
    }
    
    
    //get pushSync Device in ffd
    $sql = "select DbName,DeviceTokenReceiveOrder,UrlNoti from FFD.branch where branchID = '$branchID'";
    $selectedRow = getSelectedRow($sql);
    $pushSyncDbName = $selectedRow[0]["DbName"];
    $pushSyncDeviceTokenReceiveOrder = $selectedRow[0]["DeviceTokenReceiveOrder"];
    $urlNoti = $selectedRow[0]["UrlNoti"];
    
    
    if($status == 11)
    {
        
        //get pushSync Device in jummum
        $sql = "select * from setting where KeyName = 'DeviceTokenAdmin'";
        $selectedRow = getSelectedRow($sql);
        $pushSyncDeviceTokenAdmin = $selectedRow[0]["Value"];
    sendPushNotificationToDeviceWithPath($pushSyncDeviceTokenAdmin,'','jill','negotiation arrive!',0,'',0);
        //****************send noti to shop (turn on light)
        alarmAdmin();
        //****************
        
        
        $msg = "";
        sendPushNotificationToDeviceWithPath($pushSyncDeviceTokenReceiveOrder,'./../../JMM/JUMMUMSHOP/','jill',$msg,$receiptID,'cancelOrder',0);
        
    }
//    else if($status == 13)
//    {
//        //get pushSync Device in ffd
//        $sql = "select DbName,DeviceTokenReceiveOrder,UrlNoti from FFD.branch where branchID = '$branchID'";
//        $selectedRow = getSelectedRow($sql);
//        $pushSyncDbName = $selectedRow[0]["DbName"];
//        $pushSyncDeviceTokenReceiveOrder = $selectedRow[0]["DeviceTokenReceiveOrder"];
//        $urlNoti = $selectedRow[0]["UrlNoti"];
//    }
    
    
    
    

    
    
    
    
    //do script successful
    mysqli_commit($con);
    if($status == 13)
    {
        $msg = "Review negotiate";
        sendPushNotificationToDeviceWithPath($pushSyncDeviceTokenReceiveOrder,'./../../JMM/JUMMUMSHOP/','jill',$msg,$receiptID,'cancelOrder',0);
        //****************send noti to shop (turn on light)
        alarmShop($urlNoti);
        //****************
    }
    
    
    
    
    
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
    $response = array('status' => '1', 'sql' => $sql);
    echo json_encode($response);
    exit();
?>
