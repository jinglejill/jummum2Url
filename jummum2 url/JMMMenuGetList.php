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
    
    
    $sql = "select * from FFD.branch where dbName = '$dbNameBranch'";
    $selectedRow = getSelectedRow($sql);
    if($selectedRow[0]["BranchID"] != $selectedRow[0]["MainBranchID"])
    {
        $mainBranchID = $selectedRow[0]["MainBranchID"];
        $sql = "select * from FFD.branch where branchID = '$mainBranchID'";
        $selectedRow = getSelectedRow($sql);
        $dbNameBranch = $selectedRow[0]["DbName"];
    }
    
    
    $sql = "select * from $dbNameBranch.menu where Status = 1;";
    $sql .= "select * from $dbNameBranch.menuType where Status = 1;";
    $sql .= "select * from $dbNameBranch.menuNote;";
    $sql .= "select * from $dbNameBranch.note where Status = 1;";
    $sql .= "select * from $dbNameBranch.notetype where Status = 1;";
    $sql .= "select * from $dbNameBranch.subMenuType where Status = 1;";
    $sql .= "select * from $dbNameBranch.specialPriceProgram where date_format(now(),'%Y-%m-%d') between date_format(startDate,'%Y-%m-%d') and date_format(endDate,'%Y-%m-%d');";
    writeToLog("sql = " . $sql);
    
    
    
    /* execute multi query */
    $jsonEncode = executeMultiQuery($sql);
    echo $jsonEncode;


    
    // Close connections
    mysqli_close($con);
    
?>
