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
$username = test_input($_POST["username"]);
//echo $username . "<br>";
$email = test_input($_POST["email"]);
$password = test_input($_POST["pswd"]);

/*echo $username . "<br>";
echo $email . "<br>";
echo $password . "<br>";*/

//echo $hashedpassword;
//echo "geoff";
//echo $username;

/*

To Validate a Password safely
Retrieve the user's salt and hash from the database.
Prepend the salt to the given password and hash it using the same hash function.
Compare the hash of the given password with the hash from the database. If they match, the password is correct. Otherwise, the password is incorrect.

also don't explicity say which part of the login details don't work
*/

$conn = mysqli_connect($servername, $serverusername, $serverpassword, $databaseName);
if ($conn===false) {
    die("Connection failed");
}

$query = "SELECT hashes, salts FROM users WHERE usernames=? AND emails=?";

$stmt = $conn->stmt_init();
if(!$stmt->prepare($query))
{
    print "Failed to prepare statement\n";
}
else
{
    $stmt->bind_param("ss", $username, $email);

    $stmt->execute();
    $result = $stmt->get_result();
    //echo $result->num_rows . " results found using " . $username . " and ". $email . "<br>";
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc())
        {
            //echo "hashes and salts: " . $row["hashes"]. "<br>" . $row["salts"]. "<br>";
            $hashes = $row["hashes"];
            $salt = $row["salts"];
            //echo $salt . "<br>";
            print "\n";
        }
    } else {
        $stmt->close();
        $conn->close();
        die("this username, email and password combination doesn't exist");
    }
}

$query = "SELECT names FROM users WHERE usernames=? AND emails=?";

$saltedPassword = $password . $salt;
/*$options = [
    'cost' => 10
];
$hashedpassword = password_hash($saltedPassword, PASSWORD_BCRYPT, $options);
echo "hashed password is: " . $hashedpassword . "<br>";*/

if (password_verify($saltedPassword,$hashes)){
    //echo "passwords match!" . "<br>";
    if(!$stmt->prepare($query))
    {
        print "Failed to prepare statement\n";
    }
    else
    {
        $stmt->bind_param("ss", $username, $email);

        $stmt->execute();
        $lastId = $stmt->insert_id;
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc())
        {
            $_SESSION["userid"] = $lastId;
            echo "name found: " . $row["names"]. "<br>";
            //print "\n";
        }
    }
} else {
    echo "username, email and password dosen't match";
}



$stmt->close();
$conn->close();
?>

</body>

</html>