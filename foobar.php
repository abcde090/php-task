<?php
$foo = "foo";
$bar = "bar";
$foobar = "foobar";

logic_test($foo, $bar, $foobar);

function logic_test($foo, $bar, $foobar) {
    for ($i = 1; $i <= 100; $i++) {
        if ($i % 3 == 0 && $i % 5 == 0) {    
            echo "$foobar, ";   
        } else if ($i % 3 == 0) {              
            echo "$foo, ";                          
        } else if ($i % 5 == 0) {
            echo "$bar, ";                 
        } else echo "$i, ";                          
    }
}
?>