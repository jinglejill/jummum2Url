<?php
    include_once("dbConnect.php");
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    
    
    
    if(isset($_POST["branchID"]) && isset($_POST["receiptID"]))
    {
        $branchID = $_POST["branchID"];
        $receiptID = $_POST["receiptID"];
    }


    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    
    //get pushSync Device in OM
    $pushSyncDeviceTokenReceiveOrder = array();
    $sql = "select * from OM.device left join OM.Branch on OM.device.DbName = OM.Branch.DbName where branchID = '$branchID';";
    $selectedRow = getSelectedRow($sql);
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        $deviceToken = $selectedRow[$i]["DeviceToken"];
        array_push($pushSyncDeviceTokenReceiveOrder,$deviceToken);
    }
    
    
    
    
    //reminder 3 times ทุก 3 นาที
    $reminderMinute = array('180','180','180');
//    $reminderMinute = array('10','10','10');
    for($i=0; $i<sizeof($reminderMinute); $i++)
    {
        sleep($reminderMinute[$i]);
//        $sql = "select * from $dbName.receiptPrint where receiptID = '$receiptID'";
        $sql = "select * from receipt where receiptID = '$receiptID' and status = 2";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)==1)
        {
            //push noti
            $sql = "select * from receipt where receiptID = '$receiptID'";
            $selectedRow = getSelectedRow($sql);
            $receiptDate = $selectedRow[0]["ReceiptDate"];
            $receiptNoID = $selectedRow[0]["ReceiptNoID"];
            
            
            
            $msg = 'Reminder: New order coming!! receipt No:' . $receiptNoID;
//            $msg = 'New order coming!! receipt No:' . $receiptNoID . ' ,noti time:' . date("Y/m/d H:i:s");
            
            
            
            

            sendPushNotificationToDeviceWithPath($pushSyncDeviceTokenReceiveOrder,'./../../JMM/JUMMUMSHOP4/','jill',$msg,$receiptID,'reminder',1);
        }
        else
        {
            break;
        }
    }
    
    
    
    
    
    
    
    mysqli_close($con);
    
    
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
    $response = array('status' => '1', 'sql' => $sql);
    echo json_encode($response);
    exit();
?>
