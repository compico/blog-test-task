<?php

require_once '../config.php';

$conn = new mysqli($SQL_SERVERNAME, $SQL_USERNAME, $SQL_PASSWORD, $SQL_DBNAME);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$url = 'https://jsonplaceholder.typicode.com/';

$postsJson = curl_init();
curl_setopt($postsJson, CURLOPT_RETURNTRANSFER, true);
curl_setopt($postsJson, CURLOPT_URL, $url . 'posts');
$postsResult = curl_exec($postsJson);
curl_close($postsJson);

$posts = json_decode($postsResult);

$commentsJson = curl_init();
curl_setopt($commentsJson, CURLOPT_RETURNTRANSFER, true);
curl_setopt($commentsJson, CURLOPT_URL, $url . 'comments');
$commentsResult = curl_exec($commentsJson);
curl_close($commentsJson);

$comments = json_decode($commentsResult);

$sql = '';

foreach ($posts as $key => $value) {
    $sql .= "INSERT INTO `posts` (`userId`, `title`, `body`)
    VALUE ($value->userId, '$value->title', '$value->body');";
}

foreach ($comments as $key => $value) {
    $sql .= "INSERT INTO `comments` (`postId`, `name`, `email`, `body`)
    VALUE ($value->postId, '$value->name', '$value->email', '$value->body');";
}

if ($conn->multi_query($sql) === true) {
    //Загружено Х записей и Y комментариев
    echo 'Загружено ' .
        count($posts) .
        ' записей и ' .
        count($comments) .
        ' комментариев.';
} else {
    echo 'Error: ' . $conn->error . '<br>';
}

$conn->close();
