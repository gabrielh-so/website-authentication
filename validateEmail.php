<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

$servername = "localhost";
$serverusername = "gabriel";
$serverpassword = "pass123";
$databaseName = "clientdatabase";
$email = test_input($_POST["email"]);

$conn = new mysqli($servername, $serverusername, $serverpassword, $databaseName);

if ($conn->connect_error) die("0");
filter_var($email, FILTER_VALIDATE_EMAIL) or die("0");
$query = "SELECT * FROM users WHERE emails=?";
$stmt = $conn->stmt_init();
if(!$stmt->prepare($query))
{
    echo "0";
}
else
{
    $stmt->bind_param("s", $email);
    $result = $stmt->execute();
    if ($result)
    {
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            $conn->close();
            die("0");
        }
    } else {
        $stmt->close();
        $conn->close();
        die("0");
    }
}
echo "1";
?>