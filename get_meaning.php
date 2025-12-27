<?php
header('Content-Type: application/json');
$conn = new mysqli("localhost", "root", "", "andazebayan");

$word = $_GET['word'];
$word = $conn->real_escape_string($word);

$result = $conn->query("SELECT meaning FROM word_meanings WHERE word='$word'");
$row = $result->fetch_assoc();

echo json_encode([
  'word' => $word,
  'meaning' => $row ? $row['meaning'] : 'Meaning not found'
]);
?>
