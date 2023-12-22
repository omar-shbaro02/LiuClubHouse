<?php
require_once("../../Config.php");
// Retrieve the raw POST data
$jsonData = file_get_contents('php://input');
// Decode the JSON data into a PHP associative array
$data = json_decode($jsonData, true);
$clubId = "";
$userId = "";
if ($data !== null) {
    $clubId = addslashes(strip_tags($data['ClubId']));
    $userId = addslashes(strip_tags($data['UserId']));
    $key = addslashes(strip_tags($data['Key']));

    if ($key != "your_key" or trim($clubId) == "" or trim($userId) == "") {
        http_response_code(403);
        die("access denied");
    }
}
$checkIfUserInClub = "SELECT * FROM UserClub WHERE UserID = " . $userId . " AND ClubID = " . $clubId;
$check = mysqli_query($con, $checkIfUserInClub);
if (mysqli_num_rows($check) > 0) {
    $query = "DELETE FROM UserClub WHERE UserID = " . $userId . " AND ClubID = " . $clubId;
    $deleteResult = mysqli_query($con, $query);
    if ($deleteResult) {
        // Deletion was successful
        echo json_encode(array('success' => true));
    } else {
        // Deletion failed
        echo json_encode(array('success' => false, 'error' => mysqli_error($con)));
    }

    mysqli_close($con);
} else {
    die("Invalid Club or User");
}
