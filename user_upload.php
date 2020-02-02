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
commandControl();

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
        help_messages();
    }

    else {
        echo "Invalid command, enter --help for full commands list\n";
    }
}

function help_messages() {
    echo 
    "    --file [csv file name] – this is the name of the CSV to be parsed\n 
    --create_table – this will cause the MySQL users table to be built (and no further action will be taken)\n
    --dry_run – this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered.\n
    -u – MySQL username\n
    -p – MySQL password\n
    -h – MySQL host\n";
}
