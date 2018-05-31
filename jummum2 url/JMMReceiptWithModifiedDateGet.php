<?php
    include_once("dbConnect.php");
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    ini_set("memory_limit","-1");
    

    
    
    
    if(isset($_POST["receiptID"]) && isset($_POST["modifiedDate"]))
    {
        $receiptID = $_POST["receiptID"];
        $modifiedDate = $_POST["modifiedDate"];
//        $status = $_POST["status"];
    }
    
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    
    

    $sql = "select * from receipt where receiptID = '$receiptID';";
    $selectedRow = getSelectedRow($sql);
    $status = $selectedRow[0]["Status"];
    
    
    //dispute get
    switch($status)
    {
        case 7:
        case 9:
        {
            $type = 1;
        }
            break;
        case 8:
        case 10:
        case 11:
        {
            $type = 2;
        }
            break;
        case 12:
        case 13:
        case 14:
        {
            $type = 5;
        }
            break;
    }
    
    
    
    //receipt get
    $sql = "select * from receipt where receiptID = '$receiptID' and modifiedDate > '$modifiedDate';";
    //dispute get
    $sql .= "Select * from Dispute where receiptID = '$receiptID' and type = '$type';";
    
    
    
    
    
    /* execute multi query */
    $jsonEncode = executeMultiQuery($sql);
    echo $jsonEncode;


    
    // Close connections
    mysqli_close($con);
    
?>
