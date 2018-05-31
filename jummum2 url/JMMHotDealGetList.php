<?php
    include_once("dbConnect.php");
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    ini_set("memory_limit","-1");
    
    
    
    
    if(isset($_POST["memberID"]))
    {
        $memberID = $_POST["memberID"];
    }
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    //select table -> branch, customerTable
    $sql = "SELECT * FROM HotDeal left join (select branchID,count(*) as frequency from receipt where memberID = '$memberID' GROUP BY branchID) a on hotdeal.BranchID = a.branchID left join (select branchID,SUM(CashAmount+CreditCardAmount+TransferAmount) sales from receipt where memberID = '$memberID' GROUP BY branchID) b on hotdeal.BranchID = b.branchID where status = 1 and date_format(now(),'%Y-%m-%d') between date_format(startDate,'%Y-%m-%d') and date_format(endDate,'%Y-%m-%d') and (hotdeal.branchID in (select distinct branchID from receipt where memberID = '$memberID') or hotdeal.branchID = 0) order by HotDeal.branchID, a.frequency desc, b.sales DESC,hotDeal.modifiedDate desc;";
    
    
    /* execute multi query */
    $jsonEncode = executeMultiQuery($sql);
    echo $jsonEncode;


    
    // Close connections
    mysqli_close($con);
    
?>
