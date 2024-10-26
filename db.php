<?php
require 'vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Exception\Exception as MongoDBException;
use MongoDB\BSON\UTCDateTime;

function getMongoDBConnection() {
    $uri = 'mongodb+srv://amankmr765:aman123@lms-0.dgc8vq1.mongodb.net/?retryWrites=true&w=majority&appName=lms-0';

    try {
        $client = new Client($uri);
        $client->selectDatabase('admin')->command(['ping' => 1]);
        return $client;
    } catch (MongoDBException $e) {
        die("MongoDB error: " . $e->getMessage());
    } catch (Exception $e) {
        die("General error: " . $e->getMessage());
    }
}
