<?php
session_start();

$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "consultorio";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La conexión ha fallado: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $genero = $_POST['genero'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        echo "Las contraseñas no coinciden";
    } else {
        $hashed_password = md5($password);

        $sql_usuarios = "INSERT INTO usuarios (email, password) VALUES ('$email', '$hashed_password')";


        if ($conn->query($sql_usuarios) === TRUE) {
            
            $last_user_id = $conn->insert_id;

            $sql_pacientes = "INSERT INTO pacientes (id_paciente, nombre, apellido, fecha_nacimiento, 
            genero, telefono, email) 
            VALUES ('$last_user_id', '$nombre', '$apellido', '$fecha_nacimiento', '$genero', '$telefono', '$email')";
            
            $_SESSION['email'] = $row['email'];
            
            if ($conn->query($sql_pacientes) === TRUE) {
                header("Location: bienvenida.php");
            } else {
                echo "Error al registrar el paciente: " . $conn->error;
            }
        } else {
            echo "Error al registrar el usuario: " . $conn->error;
        }
    }
}

$conn->close();
?>
