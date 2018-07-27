<?php
    include_once("dbConnect.php");
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
    
    
    $sql = "select * from wp_users left join testShopUpMemberNew on wp_users.user_login = testShopUpMemberNew.Email where wp_users.id between 7351 and 7411";
    $selectedRow = getSelectedRow($sql);
    for($i=0; $i<sizeof($selectedRow); $i++)
    {
        $id = $selectedRow[$i]["ID"];
        $firstName = $selectedRow[$i]["FirstName"];
        $lastName = $selectedRow[$i]["LastName"];
        $address = $selectedRow[$i]["Address"];
        $country = $selectedRow[$i]["Country"];
        $province = $selectedRow[$i]["Province"];
        $postCode = $selectedRow[$i]["PostCode"];
        $phoneNo = $selectedRow[$i]["PhoneNo"];
        $sql = "insert into wp_usermeta (`user_id`, `meta_key`, `meta_value`) values ($id,'billing_first_name','$firstName')";
        $sql .= ",($id,'billing_last_name','$lastName')";
        $sql .= ",($id,'billing_address_1','$address')";
        $sql .= ",($id,'billing_city','$province')";
        $sql .= ",($id,'billing_postcode','$postCode')";
        $sql .= ",($id,'billing_country','$country')";
        $sql .= ",($id,'billing_phone','$phoneNo')";
        $sql .= ",($id,'shipping_first_name','$firstName')";
        $sql .= ",($id,'shipping_last_name','$lastName')";
        $sql .= ",($id,'shipping_address_1','$address')";
        $sql .= ",($id,'shipping_city','$province')";
        $sql .= ",($id,'shipping_postcode','$postCode')";
        $sql .= ",($id,'shipping_country','$country')";
        $sql .= ",($id,'shipping_phone','$phoneNo')";
        $ret = doQueryTask($sql);
        if($ret != "")
        {
            mysqli_rollback($con);
            putAlertToDevice();
            echo json_encode($ret);
            exit();
        }
    }
    
    

    
    
    
    
    
    
    
    mysqli_commit($con);
    mysqli_close($con);
    writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
    $response = array('status' => '1', 'sql' => $sql);
    echo json_encode($response);
    exit();
    
//    $emailBody = str_replace("#codereset#",$codeReset,$emailBody);
?>
