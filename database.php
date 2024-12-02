<?php
$host = 'sql12.freesqldatabase.com';
$dbname = 'sql12749058';
$username = 'sql12749058';
$password = 'NMkjpVZlMH';

$mysqli = new mysqli($host, $username, $password, $dbname);
if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}
return $mysqli;