<?php
$connection = new mysqli('localhost', 'root', '', 'dbwaldas');

if (!$connection) {
	die(mysqli_error($mysqli));
}
