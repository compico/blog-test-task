<?php
//Подгрузка конфигурций сервера
require_once '../config.php';

//Создание соединения с SQL сервером
$conn = new mysqli($SQL_SERVERNAME, $SQL_USERNAME, $SQL_PASSWORD, $SQL_DBNAME);

//Проверка на ошибку соединения
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

// URL для подключения к плэйсхолдеру
$url = 'https://jsonplaceholder.typicode.com/';

$postsRequest = curl_init(); // Инициализация CURL для GET запроса к HTTP серверу
curl_setopt($postsRequest, CURLOPT_RETURNTRANSFER, true); // Опция для возвращения ответа в виде строки
curl_setopt($postsRequest, CURLOPT_URL, $url . 'posts'); // Указываем URL
$postsResultJson = curl_exec($postsRequest); // Делам запрос и получаем ответ в виде строки
curl_close($postsRequest); // Закрываем соединение

$posts = json_decode($postsResultJson); // Делаем из JSON строки - ассоциативный массив

// Создаём выражение для создания запроса SQL
$stmtPosts = $conn->prepare(
    'INSERT INTO `posts` (`userId`, `title`, `body`) VALUE (?,?,?)'
);

// Проходимся по всем записям из результата HTTP
foreach ($posts as $key => $value) {
    // Говорим, что у нас 3 параметра i - целочисленное ; s - строка, поэтому "iss"
    // и записываем параметры в выражение
    $stmtPosts->bind_param('iss', $value->userId, $value->title, $value->body);
    $stmtPosts->execute(); // Делаем запрос выражение SQL
}

//Тоже самое только для комментариев к записям.
$commentsRequest = curl_init();
curl_setopt($commentsRequest, CURLOPT_RETURNTRANSFER, true);
curl_setopt($commentsRequest, CURLOPT_URL, $url . 'comments');
$commentsResultJson = curl_exec($commentsRequest);
curl_close($commentsRequest);

$comments = json_decode($commentsResultJson);

$stmtComments = $conn->prepare(
    'INSERT INTO `comments` (`postId`, `name`, `email`, `body`)VALUE (?, ?, ?, ?)'
);

foreach ($comments as $key => $value) {
    $stmtComments->bind_param(
        'isss',
        $value->postId,
        $value->name,
        $value->email,
        $value->body
    );
    $stmtComments->execute();
}

// Если произошла ошибка - вывести его
if ($conn->error) {
    echo 'Error: ' . $conn->error . '<br>';
} else {
    //Загружено Х записей и Y комментариев
    echo 'Загружено ' .
        count($posts) .
        ' записей и ' .
        count($comments) .
        ' комментариев.';
}

//Закрываем соединение к серверу.
$conn->close();
