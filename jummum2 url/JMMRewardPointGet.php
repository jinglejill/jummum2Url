<?php
    include_once("dbConnect.php");
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    
    
    
    if(isset($_POST["memberID"]))
    {
        $memberID = $_POST["memberID"];
    }
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    $sql = "SELECT ifnull(sum(Status * Point),0) Point FROM `rewardpoint` WHERE MemberID = '$memberID';";
    $sql .= "select * from rewardRedemption left join (select branchID,count(*) as frequency from receipt where memberID = '$memberID' GROUP BY branchID) a on rewardRedemption.BranchID = a.branchID left join (select branchID,SUM(CashAmount+CreditCardAmount+TransferAmount) sales from receipt where memberID = '$memberID' GROUP BY branchID) b on rewardRedemption.BranchID = b.branchID where status = 1 and date_format(now(),'%Y-%m-%d') between date_format(startDate,'%Y-%m-%d') and date_format(endDate,'%Y-%m-%d') and rewardRedemption.branchID in (select distinct branchID from receipt where memberID = '$memberID') order by a.frequency desc, b.sales DESC, rewardRedemption.modifiedDate desc;";
    
    
    
    /* execute multi query */
    $jsonEncode = executeMultiQuery($sql);
    echo $jsonEncode;
    
    
    
    // Close connections
    mysqli_close($con);
?>
