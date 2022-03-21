<?php
session_start();
?>
<!DOCTYPE html>
<html>
<body>

<?php
// Echo session variables that were set on previous page
echo "Favorite id is " . $_SESSION["userid"] . ".<br>";
?>

</body>
</html>