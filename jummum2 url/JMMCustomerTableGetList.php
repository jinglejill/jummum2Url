<?php
    include_once("dbConnect.php");
    setConnectionValue($_POST["dbNameBranch"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    ini_set("memory_limit","-1");
    $dbNameBranch = $_POST["dbNameBranch"];

    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    $sql = "select * from OM.branch where DbName = '$dbNameBranch'";
    $selectedRow = getSelectedRow($sql);
    $branchID = $selectedRow[0]["BranchID"];
    $sql = "select '$branchID' BranchID, customerTable.* from customerTable where Status = 1;";
    writeToLog("sql = " . $sql);
    
    
    
    /* execute multi query */
    $jsonEncode = executeMultiQuery($sql);
    echo $jsonEncode;


    
    // Close connections
    mysqli_close($con);
    
?>
