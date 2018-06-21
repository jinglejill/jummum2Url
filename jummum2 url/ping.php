<?php
    ini_set('max_execution_time',0);
    
    include_once("dbConnect.php");
    $_POST["dbName"] = "JUMMUM2";
    setConnectionValue($_POST["dbName"]);
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    
    
    
    // Check connection
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    
    for($i=0; $i<168; $i++)
//    for($i=0; $i<1; $i++)
    {
//        $content = file_get_contents('http://www.jummum.co');
        $content = file_get_contents('http://www.minimalist.co.th/index.html');
        if(strpos($content, 'jinglejillTestMini123') !== false)
        {
            writeToLog("string founded");
            sendPushNotificationToDeviceWithMsg('67dfa3d422c5dbe45becb82b3822c3b9108a4a91454f5935e51d5d189945aef4','site live');
            break;
            
           
            
//            echo 'true';
        }
        else
        {
            writeToLog("not found");
            ob_flush();
            flush();
            sleep(60);
            
            ob_flush();
            flush();
            sleep(60);
            
            ob_flush();
            flush();
            sleep(60);
            
            ob_flush();
            flush();
            sleep(60);
            
            ob_flush();
            flush();
            sleep(60);
            
//            echo 'false';
        }
    }
    
    
    
    
    
    
?>

