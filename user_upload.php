<?php
// MySQL connection details
$host = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "myDatabase";
$table = "users";
$conn = new mysqli($host, $username, $password);


echo "Script starting now\n";

commandControl($host, $username, $password, $conn, $dbname, $table);

// Open a connection to mySQL server and display connection status
function connection($conn)
{
    if ($conn->connect_error) {
        die('Error (' . $conn->connect_error . ') ');
    } else echo "Successfully connected to MySQL server.\n";
}

// Handle all command inputs
function commandControl($host, $username, $password, $conn, $dbname, $table)
{
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
            table_creation($conn, $dbname, $table);
            loadData($file, TRUE, $conn, $dbname, $table);
        } else {
            echo "Unsupported file format. Please try again with a csv file\n";
        }
        $command = TRUE;
    }

    if (array_key_exists('create_table', $options)) {
        table_creation($conn, $dbname, $table);
        $command = TRUE;
    }

    if (array_key_exists('dry_run', $options) and array_key_exists('file', $options)) {
        echo "Dry run mode: no data will be added to the database.";
        $file = $options["file"];
        $info = pathinfo($file);
        if ($info["extension"] === "csv") {
            table_creation($conn, $dbname, $table);
            loadData($file, FALSE, $conn, $dbname, $table);
        } else {
            echo "Unsupported file format. Please try again\n";
        }
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

// Display help messages
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

// Create the database and table if not exists
function table_creation($conn, $dbname, $table)
{
    connection($conn);
    $query = "CREATE DATABASE IF NOT EXISTS $dbname";
    if (mysqli_query($conn, $query)) {
        echo "Success creating database $dbname\n";
    } else {
        echo "Failure creating database $dbname\n";
    }

    $db_selected = mysqli_select_db($conn, $dbname);
    if (!$db_selected) {
        die('Cannot use the selected database');
    }
    $sql = "CREATE TABLE IF NOT EXISTS $table (
    name VARCHAR(30) NOT NULL,
    surname VARCHAR(30) NOT NULL,
    email VARCHAR(50) NOT NULL,
    UNIQUE (email)
    )";


    if (mysqli_query($conn, $sql)) {
        echo "Table $table created successfully\n";
    } else {
        echo "Error: " . mysqli_error($conn) . "\n";
    }
}

// loadData from csv file, insert data into the database when $insert is true, otherwise dry_run 
function loadData($filename, $insert, $conn, $dbname, $table)
{
    table_creation($conn, $dbname, $table);
    $file = fopen($filename, "r");
    $count = 0;
    while (!feof($file)) {
        $array = fgetcsv($file, 1000, ",");
        $name = string_filter($array[0]);
        $surname = string_filter($array[1]);
        if (email_filter($array[2], $count)) {
            echo "Row $count is valid.\n";
            if ($insert === TRUE) {
                $email = trim(strtolower($array[2]));
                $sql = "INSERT INTO $table (name, surname, email)
                VALUES ('$name', '$surname', '$email')";
                global $conn;
                if ($conn->query($sql)) {
                    echo "Row $count has been inserted into the database successfully.\n";
                } else {
                    echo "Error: $conn->error\n";
                }
            } 
        }
        $count++;
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
function email_filter($email, $count)
{
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = str_replace("'", "\'", $email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo ("Row $count contains an invalid email address.\n");
        return FALSE;
    }
    return TRUE;
}
