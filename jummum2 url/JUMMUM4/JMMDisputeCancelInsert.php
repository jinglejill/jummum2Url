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

    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    
    $sql = "select * from receipt where receiptID = '$receiptID';";
    $selectedRow = getSelectedRow($sql);
    $receiptStatus = $selectedRow[0]["Status"];
    if($receiptStatus == 2)
    {
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
        $status = 7;
        
        
        
        
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
        
        
        

        //get pushSync Device in ffd
        $pushSyncDeviceTokenReceiveOrder = array();
        $sql = "select * from FFD.device left join FFD.Branch on FFD.device.DbName = FFD.Branch.DbName where branchID = '$branchID';";
        $selectedRow = getSelectedRow($sql);
        for($i=0; $i<sizeof($selectedRow); $i++)
        {
            $deviceToken = $selectedRow[$i]["DeviceToken"];
            array_push($pushSyncDeviceTokenReceiveOrder,deviceToken);
        }
        
        


        
        //do script successful
        mysqli_commit($con);
        
        
        /* execute multi query */
        $sql = "select * from receipt where receiptID = '$receiptID';";
        $sql .= "Select * from Dispute where receiptID = '$receiptID' and disputeID = '$disputeID';";
        $dataJson = executeMultiQueryArray($sql);
        {
            if($type == "1")
            {
                $msg = "Order cancel request";
            }
            else if($type == "2")
            {
                $msg = "Open dispute request";
            }
            else
            {
                $msg = "Review negotiation";
            }

            sendPushNotificationToDeviceWithPath($pushSyncDeviceTokenReceiveOrder,'./../../JMM/JUMMUMSHOP4/','jill',$msg,$receiptID,'cancelOrder',1);
            //****************send noti to shop (turn on light)
            //alarmShop
            //query statement
            $ledStatus = 1;
            $sql = "update FFD.Branch set LedStatus = '$ledStatus', ModifiedUser = '$modifiedUser', ModifiedDate = '$modifiedDate' where branchID = '$branchID';";
            $ret = doQueryTask($sql);
            if($ret != "")
            {
                mysqli_rollback($con);
                //        putAlertToDevice();
                echo json_encode($ret);
                exit();
            }
            //****************
        }
    }
    else
    {
        mysqli_commit($con);
        
        
        /* execute multi query */
        $sql = "select * from receipt where receiptID = '$receiptID';";
        $sql .= "select * from dispute where 0";
        $dataJson = executeMultiQueryArray($sql);
    }
    
    
    
    
    
    
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
    $response = array('status' => '1', 'sql' => $sql, 'tableName' => 'Receipt', 'dataJson' => $dataJson);
    echo json_encode($response);
    exit();
?>
