<?php
session_start();

session_destroy();

header("Location: consultorio.html");
exit();
?>
