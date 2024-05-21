<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Usuarios</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand"> Bienvenid@,&nbsp;
            <?php echo isset($_SESSION['email']) ? $_SESSION['email'] : 'email'; ?>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Citas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="medicos.php">Médicos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pacientes.php">Pacientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="usuarios.php">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="cerrarSesion.php">Cerrar Sesión</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center mb-4">Usuarios</h2>

                <a href="agregar_usuario.php" class="btn btn-primary ml-auto mb-2">Agregar Nuevo Usuario</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Rol</th>
                            <th>Editar</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php include 'crud_usuarios.php'; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        function editarUsuario(usuarioId) {
            window.location.href = 'editar_usuarios.php?id=' + usuarioId;
        }

        function cargarUsuarios() {
            console.log('Cargando usuarios...'); 
            fetch('crud_usuarios.php')
                .then(response => response.json()) 
                .then(data => {
                    if (data.success) {

                    const tablaUsuarios = document.querySelector('.table tbody');
                    tablaUsuarios.innerHTML = '';

                    data.data.forEach(usuario => {
                        const fila = document.createElement('tr');
                        fila.innerHTML = `
                            <td>${usuario.email}</td>
                            <td>${usuario.role}</td>
                            <td><button class='btn btn-info' onclick='editarUsuario(${usuario.id})'>Editar</button></td>
                            <td><button class='btn btn-danger' onclick='eliminarUsuario(${usuario.id})'>Eliminar</button></td>
                            `;
                        tablaUsuarios.appendChild(fila);
                    });
                } else {
                    console.error('Error al cargar los usuarios:', data.error);
                }
                })
                .catch(error => {
                    console.error('Error en la solicitud AJAX:', error);
                });
        }

        cargarUsuarios();

        function eliminarUsuario(usuarioId) {
            console.log('ID del usuario a eliminar:', usuarioId);

            if (confirm('¿Estás seguro de que deseas eliminar el usuario?')) {
                fetch('crud_usuarios.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', 
                    },
                    body: JSON.stringify({ action: 'eliminar', id: usuarioId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log(data);
                            alert('El usuario se eliminó correctamente.');
                            console.log('regargando pagina');
                            location.reload();
                            console.log('pagina recargada');
                        } else {
                            alert('Error al eliminar el usuario.');
                        }
                    })
                    .catch(error => {
                        console.error('Error en la solicitud AJAX:', error);
                        console.log(error)
                    });
            }
        }
    </script>
</body>

</html>
