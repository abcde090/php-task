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
    global $host, $username, $password;
    $shortOptions = "uph";
    $longOptions  = array(
        "file:",
        "create_table",
        "dry_run",
        "help",
    );
    $options = getopt($shortOptions, $longOptions);


    if (array_key_exists('file', $options) and !array_key_exists('dry_run', $options)) {
        $file = $options["file"];
    }

    if (array_key_exists('create_table', $options)) {
    }

    if (array_key_exists('dry_run', $options) and array_key_exists('file', $options)) {
        $file = $options["file"];
    }

    if (array_key_exists('u', $options)) {
        echo "MySQL username: " . $username . "\n";
    }

    if (array_key_exists('p', $options)) {
        echo "MySQL password: " . $password . "\n";
    }

    if (array_key_exists('h', $options)) {
        echo "MySQL host: " . $host . "\n";
    }

    if (array_key_exists('help', $options)) {
    }
}
