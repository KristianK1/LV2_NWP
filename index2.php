<?php

function connectToDatabase($dbName){
    return new mysqli("localhost", "root", "", $dbName);
}






?>