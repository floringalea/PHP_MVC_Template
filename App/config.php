<?php
<<<<<<< HEAD

/*
| DATABASE CONFIG
*/
$db = array(
	'dsn'	=> '',
	'hostname' => '10.169.0.133',
	'username' => 'galeadig_sims',
	'password' => 'I8L82Day',
	'database' => 'galeadig_z1jm',
);

/*
| Define db connection
*/
$conn = mysqli_connect($db['hostname'], $db['username'], $db['password']);
=======
$conn = mysqli_connect('localhost','root', '');
>>>>>>> 43ba814043711368a5a1eccccbdb16eea1678fd3

if (!$conn){

    die("Database Connection Failed" . mysqli_connect_error());

}

<<<<<<< HEAD
$select_db = mysqli_select_db($conn, $db['database']);
=======
$select_db = mysqli_select_db($conn, 'school_s');
>>>>>>> 43ba814043711368a5a1eccccbdb16eea1678fd3

if (!$select_db){

    die("Database Selection Failed" . mysqli_connect_error());

}
<<<<<<< HEAD


=======
session_start();
 ?>
>>>>>>> 43ba814043711368a5a1eccccbdb16eea1678fd3
