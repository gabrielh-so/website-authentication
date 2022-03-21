<?php

session_start();

?>

<!DOCTYPE html>
<html>

    <body>

        <?php

echo "color " . $_SESSION["color"] . "<br>";
echo "animal " . $_SESSION["animal"];

        ?>

    </body>

</html>