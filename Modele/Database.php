<?php
function getPDO() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "activitrade";
        $db = new PDO("mysql:host=$servername;port=3306;dbname=$dbname;charset=utf8", $username, $password);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
}

function testValidationForm($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>