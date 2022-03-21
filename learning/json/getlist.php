<?php

$myObj = new \stdClass(); // prevents a warning from cluttering up commnication while using strict mode
$myObj->dataArr = array("something1", "something2", "something3", "something4");
$myObj->name = "John";
$myObj->age = 30;
$myObj->city = "New York";
$JSONOut = json_encode($myObj);

echo $JSONOut;

?>