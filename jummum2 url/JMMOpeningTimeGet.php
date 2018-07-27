<?php
    include_once("dbConnect.php");
    if($_GET["dbName"])
    {
        $_POST["dbName"] = $_GET["dbName"];
    }
    if($_GET["branchID"])
    {
        $_POST["branchID"] = $_GET["branchID"];
    }
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    ini_set("memory_limit","-1");
    


    if(isset($_POST["branchID"]))
    {
        $branchID = $_POST["branchID"];
    }
    

    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    
    $sql = "select * from OM.branch where branchID = '$branchID'";
    $selectedRow = getSelectedRow($sql);
    $selectedDbName = $selectedRow[0]["DbName"];
    
    

    $inOpeningTime = 0;
    $sql = "select * from $selectedDbName.Setting where keyName = 'customerOrderStatus'";
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
        //get today's opening time
        $strDate = date("Y-m-d");
        $currentDate = date("Y-m-d H:i:s");
        $dayOfWeek = date('w', strtotime($strDate));
        $sql = "select * from $selectedDbName.OpeningTime where day = '$dayOfWeek' order by day,shiftNo";
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
                echo "startDate: " . $startDate . "<br>";
                echo "endDate: " . $endDate . "<br>";
                echo "currentDate lala: " . $currentDate . "<br>";
                if($startDate<=$currentDate)
                {
                    echo "compare 1 yes<br>";
                }
                else
                {
                    echo "compare 1 no<br>";
                }
                if($currentDate<=$endDate)
                {
                    echo "compare 2 yes<br>";
                }
                else
                {
                    echo "compare21 no<br>";
                }
                echo "test";
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
    
    /* execute multi query */
    $jsonEncode = executeMultiQuery($sql);
    echo $jsonEncode;
    
    
    
    // Close connections
    mysqli_close($con);
    
?>
