<?php

function testInput($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$in = testInput($_GET['in']);

$myfile = fopen("names.txt", "r") or die("Unable to open file.");

$in = ucfirst($in);

if (strlen($in) > 2){

    //echo $in;
    $viableNames = [];
    $x = 0;
    while ($line=fgets($myfile)){
        //echo $line;
        if (strpos($line, $in) !== false){
            array_push($viableNames, $line);
        }
    }

    foreach ($viableNames as $line){
        
        echo "<br>".$line;
    }
    //setcookie("names", json_encode($viableNames));
} /*elseif (!$in) {
    echo json_decode($_COOKIE["names"]);
}*/
else exit("Input too short.");
fclose($myfile);

?>