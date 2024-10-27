<?php
require 'vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Exception\Exception as MongoDBException;
use MongoDB\BSON\UTCDateTime;
use Dotenv\Dotenv;


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

function getMongoDBConnection()
{
 $uri = $_ENV['MONGO_URI'] ?? '';

 if (!$uri) {
  die("Error: MONGO_URI is not set. Check your .env file.");
 }
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
