<?php
    include_once("dbConnect.php");
//    setConnectionValue($_POST["dbName"]);
    setConnectionValue("FFD");
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    ini_set("memory_limit","-1");
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    //select table -> branch, customerTable
    $sql = "SELECT * FROM FFD.Branch where status = 1 and customerApp = 1;";

    

    //build sql statement for table
    $selectedRow = getSelectedRow($sql);
    if(sizeof($selectedRow)>0)
    {
        $eachDbName = $selectedRow[0]["DbName"];
        $branchID = $selectedRow[0]["BranchID"];
        $sqlCustomerTable = "select $branchID as BranchID, $eachDbName.CustomerTable.* from $eachDbName.CustomerTable";
        for($i=1; $i<sizeof($selectedRow); $i++)
        {
            $eachDbName = $selectedRow[$i]["DbName"];
            $branchID = $selectedRow[$i]["BranchID"];
            $sqlCustomerTable .= " union select $branchID as BranchID , $eachDbName.CustomerTable.* from $eachDbName.CustomerTable";
        }
    }
    else
    {
        $sqlCustomerTable = "select * from CustomerTable where 0";
    }
    $sql .= $sqlCustomerTable . ";";
    $sql .= "select * from JUMMUM4.setting;";
    
    
    
    /* execute multi query */
    $jsonEncode = executeMultiQuery($sql);
    echo $jsonEncode;


    
    // Close connections
    mysqli_close($con);
    
?>
