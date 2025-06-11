<?php
// login.php
include 'db.php';
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = hash('sha256', $_POST['password']);

    $sql = "SELECT * FROM users WHERE username = ? AND password = ? AND is_active = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user;
        header("Location: index.php");
    } else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login PawLand</title>
</head>
<body>
<h2>Login</h2>
<?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <input type="submit" name="login" value="Login">
</form>
</body>
</html>


<?php
// logout.php
session_start();
session_destroy();
header("Location: login.php");
?>
