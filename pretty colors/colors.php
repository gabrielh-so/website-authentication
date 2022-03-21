<html>

<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$color = test_input($_POST["color"]);
$Dcolor = test_input($_POST["Dcolor"]);
$success = false;
if ($color || $Dcolor){
    $color = ($color) ? $color : $Dcolor;
    if (strlen($color) === 7 || strlen($color) === 4 && strpos($color, '#') !== false){
        echo "<body bgcolor=$color>";
        echo $color . " selected";
        $success = true;
        setcookie("savedColor", $Dcolor);
    }
} else if (!empty($_COOKIE["savedColor"])){
    $color = test_input($_COOKIE["savedColor"]);
    echo "<body bgcolor=$color>";
    echo $color . " selected from cookie settings";
    $success = true;
}
if (!$success){
    echo "<body bgcolor='#000000'>";
    echo "<p style='color: #FFFFFF'>black selected due to no input and no valid cookies found :(</p>";
}
if ($Dcolor === "reset") setcookie("savedColor", "", time()-3600);


?><br>



</body>
</html>