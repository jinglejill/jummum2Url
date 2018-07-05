<?php
    include_once("dbConnect.php");
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    
    

    if(isset($_POST["branchID"]))
    {
        $branchID = $_POST["branchID"];
    }
    if(isset($_POST["disputeID"]) && isset($_POST["receiptID"]) && isset($_POST["disputeReasonID"]) && isset($_POST["refundAmount"]) && isset($_POST["detail"]) && isset($_POST["phoneNo"]) && isset($_POST["type"]) && isset($_POST["modifiedUser"]) && isset($_POST["modifiedDate"]))
    {
        $disputeID = $_POST["disputeID"];
        $receiptID = $_POST["receiptID"];
        $disputeReasonID = $_POST["disputeReasonID"];
        $refundAmount = $_POST["refundAmount"];
        $detail = $_POST["detail"];
        $phoneNo = $_POST["phoneNo"];
        $type = $_POST["type"];
        $modifiedUser = $_POST["modifiedUser"];
        $modifiedDate = $_POST["modifiedDate"];
    }
    else if(isset($_GET["admin"]))//admin
    {
        $branchID = $_GET["branchID"];
        
        
        $disputeID = 0;
        $receiptID = $_GET["receiptID"];
        $disputeReasonID = '';
        $refundAmount = $_GET["refundAmount"];
        $detail = $_GET["detail"];
        $phoneNo = '';
        $type = '5';
        $modifiedUser = 'admin';
        $modifiedDate = date('Y-m-d H:i:s');
    }
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    
    
    //dispute
    //query statement
    $sql = "INSERT INTO Dispute(ReceiptID, DisputeReasonID, RefundAmount, Detail, PhoneNo, Type, ModifiedUser, ModifiedDate) VALUES ('$receiptID', '$disputeReasonID', '$refundAmount', '$detail', '$phoneNo', '$type', '$modifiedUser', '$modifiedDate')";
    $ret = doQueryTask($sql);
    $disputeID = mysqli_insert_id($con);
    if($ret != "")
    {
        mysqli_rollback($con);
//        putAlertToDevice();
        echo json_encode($ret);
        exit();
    }
    
    
    switch($type)
    {
        case 1:
        {
            $status = 7;
        }
            break;
        case 2:
        {
            $status = 8;
        }
            break;
        case 3:
        {
            $status = 9;
        }
            break;
        case 4:
        {
            $status = 10;
        }
            break;
        case 5:
        {
            $status = 12;//12=jummum admin feedback to customer
        }
            break;
    }
    
    
    
    
    //receipt
    $sql = "update receipt set status = '$status',statusRoute=concat(statusRoute,',','$status'), modifiedUser = '$modifiedUser', modifiedDate = '$modifiedDate' where receiptID = '$receiptID'";
    $ret = doQueryTask($sql);
    if($ret != "")
    {
        mysqli_rollback($con);
//        putAlertToDevice();
        echo json_encode($ret);
        exit();
    }
    
    
    
    if($type == 1 || $type == 2 || $type == 5)
    {
        //get pushSync Device in ffd
        $sql = "select DbName,DeviceTokenReceiveOrder,UrlNoti from FFD.branch where branchID = '$branchID'";
        $selectedRow = getSelectedRow($sql);
        $pushSyncDbName = $selectedRow[0]["DbName"];
        $pushSyncDeviceTokenReceiveOrder = $selectedRow[0]["DeviceTokenReceiveOrder"];
        $urlNoti = $selectedRow[0]["UrlNoti"];
        

    }
    
    
    
    

    
    
    
    
    
    
    
    //do script successful
    mysqli_commit($con);
    if($type == 1 || $type == 2 || $type == 5)
    {
        $msg = $type == 1?"Order cancel request":$type == 2?"Open dispute request":"Review negotiation";
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
