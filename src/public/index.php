<?php
require_once('./login.php');
$connection = new mysqli($hn,$un,$pw,$db);
$query = "CREATE TABLE Persons (
    PersonID int,
    LastName varchar(255),
    FirstName varchar(255),
    Address varchar(255),
    City varchar(255)
);";
$result = $connection->query($query);
echo "Done";
?>