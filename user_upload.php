<?php
// debug 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// login details
$host = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "myDatabase";


echo "Script starting now\n";
connection();

function connection()
{
    global $host, $username, $password;
    $conn = new mysqli($host, $username, $password);
    if ($conn->connect_error) {
        die('Error (' . $conn->connect_errno . ') ');
    } else echo "Successfully connected to MySQL server.\n";
}

function commandControl()
{
    $shortOptions = "uph";
    $longOptions  = array(
        "file:",
        "create_table",
        "dry_run",
        "help",
    );
    $options = getopt($shortOptions, $longOptions);
}
