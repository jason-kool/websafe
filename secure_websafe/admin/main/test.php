<?php

function outbound_allowed($url) {
    
    if (empty($url)) {
        return false;
    } else {
        $value = parse_url($url); // split the URL into sections of scheme/host/path
    }

    if (!empty($value["scheme"])) {
        $scheme = $value["scheme"];
    }
    
    if (!empty($value["host"])) {
        $host = $value["host"];
    }

    if (!empty($value["path"])) {
        $path = $value["path"];
    }

    if ($host != "192.168.40.22") {
        return false;
    }

    if ($path != "/welcome.html") {
        return false;
    }

    return true;
}

$address = "http://192.168.40.22/welcome.html";
echo var_dump(outbound_allowed($address));


?>