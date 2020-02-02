<?php
// debug 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// login details
$host = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "myDatabase";
$conn = new mysqli($host, $username, $password);


echo "Script starting now\n";
commandControl();


function connection()
{
    global $conn;
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
    $command = FALSE;

    if (array_key_exists('file', $options) and !array_key_exists('dry_run', $options)) {
        $file = $options["file"];
        $info = pathinfo($file);
        if ($info["extension"] === "csv") {
            loadData($file, TRUE);
        } else {
            echo "Unsupported file format. Please try again\n";
        }
        $command = TRUE;
    }

    if (array_key_exists('create_table', $options)) {
        table_creation();
        $command = TRUE;
    }

    if (array_key_exists('dry_run', $options) and array_key_exists('file', $options)) {
        $file = $options["file"];
        $command = TRUE;
    }

    if (array_key_exists('u', $options)) {
        echo "MySQL username: " . $username . "\n";
        $command = TRUE;
    }

    if (array_key_exists('p', $options)) {
        echo "MySQL password: " . $password . "\n";
        $command = TRUE;
    }

    if (array_key_exists('h', $options)) {
        echo "MySQL host: " . $host . "\n";
        $command = TRUE;
    }

    if (array_key_exists('help', $options)) {
        help_messages();
        $command = TRUE;
    }

    if (!$command) {
        echo "Invalid command. Please enter a valid command(enter --help for full commands list). \n";
    }

}

function help_messages()
{
    echo
        "    --file [csv file name] – this is the name of the CSV to be parsed\n 
    --create_table – this will cause the MySQL users table to be built (and no further action will be taken)\n
    --dry_run – this will be used with the --file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered.\n
    -u – MySQL username\n
    -p – MySQL password\n
    -h – MySQL host\n";
}


function table_creation()
{
    connection();
    global $conn, $dbname;
    $query = "CREATE DATABASE IF NOT EXISTS $dbname";
    if (mysqli_query($conn, $query)) {
        echo "Success creating database\n";
    } else {
        echo "Failure creating database\n";
    }

    $db_selected = mysqli_select_db($conn, $dbname);
    if (!$db_selected) {
        die('Cannot use the selected database');
    }
    $sql = "CREATE TABLE IF NOT EXISTS userTable (
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    UNIQUE (email)
    )";


    if (mysqli_query($conn, $sql)) {
        echo "Table userTable created successfully\n";
    } else {
        echo "Error: " . mysqli_error($conn) . "\n";
    }
}

function loadData($filename, $insert)
{
    table_creation();
    $file = fopen($filename, "r");

    while (!feof($file)) {

        $array = fgetcsv($file, 1000, ",");
        $name = string_filter($array[0]);
        $surname = string_filter($array[1]);
        if (email_filter($array[2])) {
            echo "This row of data is valid.\n";
            if ($insert) {
                $email = trim(strtolower($array[2]));
                $sql = "INSERT INTO userTable (name, surname, email)
                VALUES ('$name', '$surname', '$email')";
                global $conn;
                if ($conn->query($sql)) {
                    echo "Data inserted into the database successfully.\n";
                } else {
                    echo "Error: $conn->error\n";
                }
            } 
        }
    }
    fclose($file);
}

// Capitalize the first word and remove numbers and special characters of the name
function string_filter($string)
{
    $string = preg_replace("/[^A-Za-z']/", "", $string);
    $string = trim(ucfirst(strtolower($string)));
    $string = str_replace("'", "\'", $string);
    return $string;
}

//Checks whether email is valid. 
function email_filter($email)
{
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo ("This row contains an invalid email address.\n");
        return false;
    }
    return true;
}
