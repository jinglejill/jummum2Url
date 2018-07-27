<?php
    include_once("dbConnect.php");
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();



    if(isset($_POST["receiptDate"]) && isset($_POST["receiptID"]) && isset($_POST["userAccountID"]))
    {
        $receiptDate = $_POST["receiptDate"];
        $receiptID = $_POST["receiptID"];
        $userAccountID = $_POST["userAccountID"];
    }
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
   
    
    
    $sql = "select * from receipt where memberID = '$userAccountID' and receiptDate <= '$receiptDate' and receiptID < '$receiptID' order by receipt.ReceiptDate DESC, receipt.ReceiptID DESC limit 10;";
    $selectedRow = getSelectedRow($sql);
    
    
    $receiptIDList = array();
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        array_push($receiptIDList,$selectedRow[$i]["ReceiptID"]);
    }
    if(sizeof($receiptIDList) > 0)
    {
        $receiptIDListInText = $receiptIDList[0];
        for($i=1; $i<sizeof($receiptIDList); $i++)
        {
            $receiptIDListInText .= "," . $receiptIDList[$i];
        }
        
        
        $sql3 = "select * from OrderTaking where receiptID in ($receiptIDListInText);";
        $selectedRow = getSelectedRow($sql3);
        //menu
        if(sizeof($selectedRow)>0)
        {
            
            $menuID = $selectedRow[0]["MenuID"];
            $branchID = $selectedRow[0]["BranchID"];
            $sql2 = "select * from OM.branch where branchID = '$branchID'";
            $selectedRow2 = getSelectedRow($sql2);
            $eachDbName = $selectedRow2[0]["DbName"];
            $sql4 = "select '$branchID' BranchID, Menu.* from $eachDbName.Menu where menuID = '$menuID'";
            for($i=1; $i<sizeof($selectedRow); $i++)
            {
                $menuID = $selectedRow[$i]["MenuID"];
                $branchID = $selectedRow[$i]["BranchID"];
                $sql2 = "select * from OM.branch where branchID = '$branchID'";
                $selectedRow2 = getSelectedRow($sql2);
                $eachDbName = $selectedRow2[0]["DbName"];
                $sql4 .= " union select '$branchID' BranchID, Menu.* from $eachDbName.Menu where menuID = '$menuID'";
            }
        }
        
        
        $sql .= "select * from OrderTaking where receiptID in ($receiptIDListInText);";
        $sql .= "select * from OrderNote where orderTakingID in (select orderTakingID from OrderTaking where receiptID in ($receiptIDListInText));";
        $sql .= $sql4;
    }
    else
    {
        $sql .= "select * from OrderTaking where 0;";
        $sql .= "select * from OrderNote where 0;";
        $sql .= "select 0 as BranchID, Menu.* from Menu where 0;";
    }
    
    
    
    
    
    
    
    writeToLog("sql = " . $sql);
    
    
    
    /* execute multi query */
    $jsonEncode = executeMultiQuery($sql);
    echo $jsonEncode;
    
    
    
    // Close connections
    mysqli_close($con);
?>
