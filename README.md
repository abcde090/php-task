# php-task
Script and logic test for the interview

## Part A Script task
a PHP script, that is executed from the command line, which accepts a CSV file as input and processes the CSV file. The parsed file data is inserted into a MySQL database. 

### The PHP script can correctly handle the following criteria:
```
• CSV file needs to contain user data and have three columns: name, surname, email 
• CSV file needs to have an arbitrary list of users
• Script can iterate through the CSV rows and insert each record into a dedicated MySQL database into the table “users”
• The users database table can be created/rebuilt as part of the PHP script.
• Name and surname field can be set to be capitalized e.g. from “john” to “John”
• Emails can be set to be lower case before being inserted into DB
• The script can validate the email address before inserting, to make sure that it is valid. 
• In case that an email is invalid, no insert is made to database and an error message is reported to STDOUT.
```

### The PHP script contains these command line options:
```
• --file [csv file name] – this is the name of the CSV to be parsed
• --create_table – this will cause the MySQL users table to be built (and no further action will be taken)
• --dry_run – this will be used with the --file directive in case we want to run the script but not insert into the DB. 
• -u – MySQL username
• -p – MySQL password
• -h – MySQL host
• --help – which will output the above list of directives with details
```

## Part B Logic task
a PHP script that is executed form the command line.
### The PHP script can correctly handle the following criteria:
```
Output the numbers from 1 to 100
• Where the number is divisible by three (3) output the word “foo”
• Where the number is divisible by five (5) output the word “bar”
• Where the number is divisible by three (3) and (5) output the word “foobar”
```
