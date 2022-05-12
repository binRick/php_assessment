#!/usr/bin/env php
<?php
// Goals
//  a) Collect user supplied property name and item type
//  b) Display associated property value for each item type
//
// Environment:
//  A directory contains directories which contain files -> each file contains key/value pairs of information pertaining to that item
//  /catalog/books/100:
//  =>
//  "book 100" properties:
//     KEY1=My Value 123
//     KEY2=Second value
// 
// Usage:
//   ./bookstore_mgr.php "item_type" "item_property"
//
// Example:
//   ./bookstore_mgr.php books title
//
// Expected result: Print out the title of each book in the inventory
//
// Pseudocode:
//  - Validate inputs
//  - Collect the file based info into memory
//  - Search the memory recorded value based on supplied property name
//  - Print each item property value to std out
//
// Output format:
//  BOOK ID, "PROPERTY VALUE"
//
//  Test case 1:
//        $ cd $(mktemp -d) && mkdir books && echo 'title=my title' > books/100 && echo 'title=The Bible' > books/999 && ~/dev/bookstore_mgr.php books title
//        Expected result: 
//          100, "my title"
//          999, "The Bible"


function invalid_usage($msg){
    if($msg=="")
        echo "Invalid invocation. Please see usage example\n";
    else
        echo $msg ."\n";
    exit(1);
}

if(count($argv) != 3)
    invalid_usage();

$USER_SUPPLIED_ITEM_TYPE = $argv[1]; 
$USER_SUPPLIED_ITEM_PROP = $argv[2]; 

if($USER_SUPPLIED_ITEM_PROP == "" || $USER_SUPPLIED_ITEM_TYPE == "")
    invalid_usage();

$normalized_dir = getcwd()."/".$USER_SUPPLIED_ITEM_TYPE;

if(!is_dir($normalized_dir))
    invalid_usage("Invalid item type \"".$USER_SUPPLIED_ITEM_TYPE."\"\n");

$processed_qty=0;

foreach(scandir($normalized_dir) as $item){
    if(!is_file($normalized_dir."/".$item)) continue;               // valid item based on spec?
    if(intval(basename($normalized_dir."/".$item)) < 1) continue;

    foreach(explode("\n",file_get_contents($normalized_dir . "/" . $item)) as $item_lines){        // iterate over each file+line+property and print values
        foreach(explode("\n",$item_lines) as $item_line){
            if(count(explode("=",$item_line)) == 2 && explode("=",$item_line)[0] == $USER_SUPPLIED_ITEM_PROP){  // acquire+extract appropriate values
                $msg = trim(basename($item)) . ", " . "\"" . trim(explode("=",$item_line)[1]) . "\"\n";
                echo "$msg";
                $processed_qty++;
            }
        }
    }
}
if($processed_qty<1) invalid_usage("Unable to find items with provided property");

