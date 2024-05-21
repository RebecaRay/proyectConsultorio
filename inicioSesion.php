<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "consultorio";

$conn = new mysqli($servername, $username, $password, $dbname);
session_start();


if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $hashed_password = md5($password);

    $sql = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = $row['role'];
        

        echo "Role from Database: {$row['role']}<br>";
        echo "Nombre de usuario en la sesión: " . $_SESSION['email'];

        if ($row['role'] === 'usuario') {
            
            header("Location: bienvenida.php");
        } elseif ($row['role'] === 'administrador') {
            header("Location: dashboard.php");
        } 
    } else {
        echo "Credenciales incorrectas";
        echo "SQL Query: $sql<br>";
    }
    if (!$result) {
        echo "Error en la consulta: " . $conn->error;
    }
    
}

$conn->close();
?>
