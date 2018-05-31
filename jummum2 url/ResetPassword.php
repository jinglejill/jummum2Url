<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1" /> 
<script type="text/javascript">
function showSuccessMessage()
{
    var resetPasswordSuccess = document.getElementById('resetPasswordSuccess');
    resetPasswordSuccess.style.display = "inline";
}

function validateForm()
{
    var validate = 1;
    var password = document.forms["resetPasswordForm"]["password"].value;
    var re = new RegExp("(?=^.{8,}$)((?=.*\\d)|(?=.*\\W+))(?![.\\n])(?=.*[A-Z])(?=.*[a-z]).*$");
    if(!re.test(password))
    {
        var errorstrongPassword = document.getElementById('errorStrongPassword');
        errorstrongPassword.style.display = "inline";
        validate = 0;
    }
    
    
    var reenterPassword = document.forms["resetPasswordForm"]["reenterPassword"].value;
    if(password != reenterPassword)
    {
        var errorReenterPassword = document.getElementById('errorReenterPassword');
        errorReenterPassword.style.display = "inline";
        validate = 0;
    }
    if(!validate)
    {
        return false;
    }
    
    return true;
}
</script>
</head>
<body>
<p>&nbsp;<img class="" src="http://www.jummum.co/jummum/logo.png" alt="" width="120" /></p>
<table width="100%" style="border-top: 1px solid #F68B1F">
<tbody>
<tr>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
<form name="resetPasswordForm" action="./resetpassword.php?codereset=<?=$_GET['codereset']?>" onsubmit="return validateForm()" method="post">
<p>&nbsp;</p>
<p>รหัสผ่านใหม่:<br /><input name="password" type="password" /></p>
<p><br /> ใส่รหัสผ่านใหม่อีกครั้งหนึ่ง:<br /> <input name="reenterPassword" type="password" /></p>
<p>&nbsp;</p>
<p><input type="submit" value="Submit" name ="submit"/></p>

</form>
<table width="100%">
<tbody>
<tr>
<td id="errorStrongPassword" style="display:none" bgcolor="#ef788c">พาสเวิร์ดต้องประกอบไปด้วย<br />1.อักษรตัวเล็กอย่างน้อย 1 ตัว<br />2.อักษรตัวใหญ่อย่างน้อย 1 ตัว<br />3.ตัวเลขหรืออักษรพิเศษอย่างน้อย 1 ตัว<br />4.ความยาวขั้นต่ำ 8 ตัวอักษร</td>
</tr>
<tr>
<td id="errorReenterPassword" style="display:none" bgcolor="#ef788c">
<p>รหัสผ่านทั้ง 2 อันไม่เหมือนกัน กรุณาตรวจสอบอีกครั้งหนึ่ง</p>
</td>
</tr>
<?php
    include_once("dbConnect.php");
    //    setConnectionValue($_POST["dbName"]);
    setConnectionValue("JUMMUM");
    writeToLog("file: " . basename(__FILE__) . ", user: " . $_POST["modifiedUser"]);
    printAllPost();
    
    
    
    if(isset($_GET["codereset"]))
    {
        $codereset = $_GET["codereset"];
    }
    
    
    
    
    
    if (mysqli_connect_errno())
    {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
    }
    
    
    
    // Set autocommit to off
    mysqli_autocommit($con,FALSE);
    writeToLog("set auto commit to off");
    
    writeToLog("submit: " . $_POST["submit"]);
    writeToLog("isset submit: " . isset($_POST["submit"]));
    if($_POST["submit"])
    {
        if(isset($_POST["password"]))
        {
            $password = $_POST["password"];
        }
        
        $sql = "select * from forgotPassword where codeReset = '$codereset'";
        $selectedRow = getSelectedRow($sql);
        if(sizeof($selectedRow) == 0)
        {
            exit();
        }
        else
        {
            $requestDate = $selectedRow[0]["RequestDate"];
            $email = $selectedRow[0]["Email"];
            
            
            if(time()-StrToTime($requestDate) > 2*60*60)//2 hours
            {
                header("Location: http://www.jinglejill.com/jmm/jummum/ResetPasswordTimeOut.php");
                //                echo "ไม่สามารถรีเซ็ตรหัสผ่านได้ กรุณาส่งคำขอเปลี่ยนรหัสผ่านอีกครั้งหนึ่ง";
            }
            else
            {
                $hashPassword = hash('SHA256',$password.'FvTivqTqZXsgLLx1v3P8TGRyVHaSOB1pvfm02wvGadj7RLHV8GrfxaZ84oGA8RsKdNRpxdAojXYg9iAj');
                $sql = "update useraccount set password = '$hashPassword', modifiedDate = now() where username = '$email'";
                $ret = doQueryTask($sql);
                if($ret != "")
                {
                    mysqli_rollback($con);
                    putAlertToDevice();
                    echo json_encode($ret);
                    exit();
                }
                echo "<tr><td id='resetPasswordSuccess' style='display:inline' bgcolor='#8def7a'>ตั้งค่ารหัสผ่านสำเร็จ</td></tr>";
                
                
                //do script successful
                mysqli_commit($con);
                mysqli_close($con);
                
                
                writeToLog("query commit, file: " . basename(__FILE__) . ", user: " . $_POST['modifiedUser']);
//                exit();
            }
        }
    }
    ?>
</tbody>
</table>
<p>&nbsp;</p>
<table width="100%" style="border-top: 1px solid #F68B1F">
<tbody>
<tr>
<td>&nbsp;</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p>หากคุณต้องการความช่วยเหลือสามารถติดต่อผ่านทางอีเมลล์&nbsp;<a href="mailto:hello@jummum.co">hello@jummum.co</a>&nbsp;หรือโทร 081-307-2993</p>
</body>
</html>

