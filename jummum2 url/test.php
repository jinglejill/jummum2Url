<?php
    //http://192.168.100.27:350/LED=ON
    //jinglejill.dyndns.co.za
    // create a new cURL resource
    $ch = curl_init();
    
    // set URL and other appropriate options
    curl_setopt($ch, CURLOPT_URL, "http://192.168.100.27:350/LED=OFF");
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    // grab URL and pass it to the browser
    curl_exec($ch);
    
    // close cURL resource, and free up system resources
    curl_close($ch);
    
    
    
    
?>
