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
    
    
    $sql = "select * from OM.branch where dbName = '$dbNameBranch'";
    $selectedRow = getSelectedRow($sql);
    if($selectedRow[0]["BranchID"] != $selectedRow[0]["MainBranchID"])
    {
        $mainBranchID = $selectedRow[0]["MainBranchID"];
        $sql = "select * from OM.branch where branchID = '$mainBranchID'";
        $selectedRow = getSelectedRow($sql);
        $dbNameBranch = $selectedRow[0]["DbName"];
    }
    
    

    
    $inOpeningTime = 0;
    $sql = "select * from $dbNameBranch.Setting where keyName = 'customerOrderStatus'";
    $selectedRow = getSelectedRow($sql);
    $customerOrderStatus = $selectedRow[0]["CustomerOrderStatus"];
    if($customerOrderStatus == 1)
    {
        $inOpeningTime = 1;
    }
    else if($customerOrderStatus == 2)
    {
        $inOpeningTime = 0;
    }
    else
    {
        //get today's opening time**********
        $strDate = date("Y-m-d");
        $currentDate = date("Y-m-d H:i:s");
        $dayOfWeek = date('w', strtotime($strDate));
        $sql = "select * from $dbNameBranch.OpeningTime where day = '$dayOfWeek' order by day,shiftNo";
        $selectedRow = getSelectedRow($sql);
        
        for($i=0; $i<sizeof($selectedRow); $i++)
        {
            $day = $selectedRow[$i]["Day"];
            $startTime = $selectedRow[$i]["StartTime"];
            $endTime = $selectedRow[$i]["EndTime"];
            
            
            
            $intStartTime = intVal(str_replace(":","",$startTime));
            $intEndTime = intVal(str_replace(":","",$endTime));
            if($intStartTime < $intEndTime)
            {
                $startDate = date($strDate . " " . $startTime . ":00");
                $endDate = date($strDate . " " . $endTime . ":00");
                if($startDate<=$currentDate && $currentDate<=$endDate)
                {
                    $inOpeningTime = 1;
                }
            }
            else
            {
                $nextDate = date("Y-m-d", strtotime($strDate. ' + 1 days'));
                $startDate = date($strDate . " " . $startTime . ":00");
                $endDate = date($nextDate . " " . $endTime . ":00");
                if($startDate<=$currentDate && $currentDate<=$endDate)
                {
                    $inOpeningTime = 1;
                }
            }
        }
    }
    
    if($inOpeningTime)
    {
        $sql = "select 1 as Text;";
    }
    else
    {
        $sql = "select 0 as Text;";
    }
    //*********
    
    $sql .= "select * from $dbNameBranch.menu where Status = 1;";
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
