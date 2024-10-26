<?php
require 'vendor/autoload.php';
require 'db.php';

use MongoDB\BSON\UTCDateTime;

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["error" => "Only POST requests are allowed."]);
    exit();
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name'], $data['time'], $data['stars'])) {
 http_response_code(400);
 echo json_encode(["error" => "All fields (name, time, stars) are required and must be valid."]);
 exit();
}

$name = htmlspecialchars(trim($data['name']));
$time = filter_var($data['time'], FILTER_VALIDATE_INT);
$stars = filter_var($data['stars'], FILTER_VALIDATE_INT);

try {
    $client = getMongoDBConnection();
    $collection = $client->selectDatabase('sky-angel')->score;

    $userData = [
        "name" => $name,
        "time" => $time,
        "stars" => $stars,
        "created_at" => new MongoDB\BSON\UTCDateTime()
    ];

    $insertResult = $collection->insertOne($userData);

    $allUsers = $collection->find()->toArray();

    $responseData = array_map(function ($user) {
        return [
            'name' => $user['name'],
            'time' => $user['time'],
            'stars' => $user['stars'],
            'id' => $user['_id']
        ];
    }, $allUsers);

    http_response_code(200);
    echo json_encode([
        "message" => "User registered successfully.",
        "data" => $responseData
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Could not save data to MongoDB.", "details" => $e->getMessage()]);
}
