<?php

session_start();

?>

<!DOCTYPE html>
<html>

    <body>

        <?php

$_SESSION["color"] = "blue";
$_SESSION["animal"] = "dog";

echo "session variables are set";

        ?>

        <button onclick="window.location.href='./session2.php'">click me to continue</button>
    </body>

</html>