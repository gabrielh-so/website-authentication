<?php
session_start();
?>
<html>
<body>

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
$name = test_input($_POST["name"]);
$username = test_input($_POST["username"]);
$email = test_input($_POST["email"]);
$password = test_input($_POST["pswd"]);

($name && $username && $email && $password) or die("<script>window.location.href = \"./?e=Login information not recieved.\"</script>");

$salt = openssl_random_pseudo_bytes(80); // 20 more than 60, which is the output hash
//echo $salt . "<br>";
$saltedPassword = $password . $salt;
$options = [
    'cost' => 10
];
$hashedpassword = password_hash($saltedPassword, PASSWORD_BCRYPT, $options);
//echo $hashedpassword . "<br>";

//$sql = "SELECT * FROM users";

// Create connection
$conn = new mysqli($servername, $serverusername, $serverpassword, $databaseName);
// Check connection

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

filter_var($email, FILTER_VALIDATE_EMAIL) or die("<script>window.location.href = \"./?e=Email invalid.\"</script>");
$query = "SELECT * FROM users WHERE emails=?";
$stmt = $conn->stmt_init();
if(!$stmt->prepare($query))
{
    print "Failed to prepare statement\n";
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
            die("<script>window.location.href = \"./?e=Email already taken.\"</script>");
        }
    } else {
        $stmt->close();
        $conn->close();
        die("Error");
    }
}

$query = "INSERT INTO users (usernames, emails, hashes, salts, names) VALUES (?, ?, ?, ?, ?)";
//$stmt = $conn->stmt_init();
if(!$stmt->prepare($query))
{
    echo "Failed to prepare statement\n";
}
else
{
    $stmt->bind_param("sssss", $username, $email, $hashedpassword, $salt, $name);

    if ($stmt->execute())
    {
        echo "New record created successfully";
        $_SESSION["userid"] = $stmt->insert_id;
        header('Location: ' . "./view", true, 303);
        die();
    } else {
        echo "Error when connecting to database";
    }
}

$stmt->close();
$conn->close();





?><br>

Welcome <?php echo $username; ?><br>
Your email address is: <?php echo $email; ?><br>
Your password is: <?php echo $password; ?>

<a href="./userLogin">go back to login<a>

</body>
</html>