<?php
    include_once("dbConnect.php");
//    alarmAdmin();
    
    echo intVal("10:30")."<br>";
    echo intVal("14:00")."<br>";
    echo intVal("17:00")."<br>";
    echo intVal("22:30")."<br>";
    echo intVal(str_replace(":","","10:30"));
    
    
?>
