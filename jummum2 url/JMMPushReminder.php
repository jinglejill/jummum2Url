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
    
    
    
//    // Set autocommit to on
//    mysqli_autocommit($con,TRUE);
//    writeToLog("set auto commit to on");
    
    
    
    $sql = "select * from FFD.Branch where BranchID = '$branchID'";
    $selectedRow = getSelectedRow($sql);
    $dbName = $selectedRow[0]["DbName"];
    $pushSyncDeviceTokenReceiveOrder = $selectedRow[0]["DeviceTokenReceiveOrder"];
    
    
    
    $reminderMinute = array('180','180','180');
//    $reminderMinute = array('10','10','10');
    for($i=0; $i<sizeof($reminderMinute); $i++)
    {
        sleep($reminderMinute[$i]);
        $sql = "select * from $dbName.receiptPrint where receiptID = '$receiptID'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow)==0)
        {
            //push noti
            $sql = "select * from receipt where receiptID = '$receiptID'";
            $selectedRow = getSelectedRow($sql);
            $receiptDate = $selectedRow[0]["ReceiptDate"];
            $receiptNoID = $selectedRow[0]["ReceiptNoID"];
//            date_format($receiptDate,"Y/m/d H:i:s")
            
            
            
            
            
            //receipt
            $sql = "select '$branchID' BranchID, Receipt.*, 1 IdInserted from Receipt where ReceiptID = '$receiptID'";
            $selectedRow = getSelectedRow($sql);
            $receiptList = $selectedRow;
            
            
            
            //orderTaking
            $sql = "select '$branchID' BranchID, OrderTaking.*, 1 IdInserted from OrderTaking where ReceiptID = '$receiptID'";
            $selectedRow = getSelectedRow($sql);
            $orderTakingList = $selectedRow;
            
            
            
            //orderNote
            $sql = "select '$branchID' BranchID, OrderNote.*, 1 IdInserted from OrderNote where OrderTakingID in (select orderTakingID from OrderTaking where ReceiptID = '$receiptID')";
            $selectedRow = getSelectedRow($sql);
            $orderNoteList = $selectedRow;
            
            
            
            //data json
            $arrOfTableArray = array();
            array_push($arrOfTableArray,$receiptList);
            array_push($arrOfTableArray,$orderTakingList);
            array_push($arrOfTableArray,$orderNoteList);
            $paramBody2 = array('receipt'=>$arrOfTableArray);
            $msg = 'New order coming!! receipt No:' . $receiptNoID;
//            $msg = 'New order coming!! receipt No:' . $receiptNoID . ' ,noti time:' . date("Y/m/d H:i:s");
            
            
            
            
//            sendPushNotificationToDeviceWithPath($pushSyncDeviceTokenReceiveOrder,'./../../FFD/MAMARIN5/','jill',$msg,$paramBody2);
            sendPushNotificationToDeviceWithPath($pushSyncDeviceTokenReceiveOrder,'./../../FFD/MAMARIN5/','jill',$msg,$receiptID);
//            writeToLog("test send push, " . $msg);
        }
        else
        {
//            writeToLog("test break");
            break;
        }
    }
    
    
    
    
    
    
    
    mysqli_close($con);
    
    
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
    $response = array('status' => '1', 'sql' => $sql);
    echo json_encode($response);
    exit();
?>
