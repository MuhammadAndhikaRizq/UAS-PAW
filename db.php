<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "voice_memories";

// Connecting to MongoDB
$mongoClient = new MongoDB\Client("mongodb://localhost:27017");

// Selecting a database
$mongodb = $mongoClient->$database;


// Selecting a collection (equivalent to a table in MySQL)
$collection = $mongodb->vox_quest; 

$data = [
    'field1' => 'value1',
    'field2' => 'value2',
];

$result = $collection->insertOne($data);

if ($result->getInsertedCount() > 0) {
    echo "Document inserted successfully.";
} else {
    echo "Error inserting document.";
}
?>
