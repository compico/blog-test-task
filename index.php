<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>Тестовое задание</title>
</head>
<body>
  <div class="card text-dark bg-light mb-3">
    <div class="card-body">
      <form action="index.php" method="get">
        <div class="input-group mb-3" style="padding-right: 25%; padding-left: 25%;">
          <input name="search_query" type="text" class="form-control" placeholder="Поиск по комментарию" aria-label="Поиск по комментарию" aria-describedby="button-addon2">
          <input class="btn btn-dark" type="submit" value="Найти" id="button-addon2">
        </div>
      </form>
    </div>
  </div>
  <div class="container">
    <div style="display: flex; flex-direction: column; align-items: center;">
      <?php
        // Проверка на наличие GET запроса
        if (isset($_GET['search_query'])) {
          // Если есть - в переменную
          $search_query = $_GET['search_query'];
          // Если запрос пустой - остановить скрипт
          if ($search_query == '') {
              echo '<div class="alert alert-warning" role="alert">
              Форма пуста!
            </div>';
              die();
          }
          // Если запрос меньше 3 символов - остановить скрипт
          if (iconv_strlen($search_query) < 3) {
              echo '<div class="alert alert-warning" role="alert">
              В запросе меньше 3 символов!
            </div>';
              die();
          }
          // Добавляю в выражение знак процента, что бы искать все результаты, где только частица запроса.
          $search_query = "%" . $search_query . "%";

          // Загрузка конфигураций сервера
          require_once 'config.php';

          // Создание соединения с SQL сервером
          $conn = new mysqli( $SQL_SERVERNAME, $SQL_USERNAME, $SQL_PASSWORD, $SQL_DBNAME);

          // Проверка на ошибку соединения
          if ($conn->connect_error) {
              die('Connection failed: ' . $conn->connect_error);
          }

          // Создания выражения для SQL запроса
          $stmt = $conn->prepare("SELECT `posts`.`id` AS 'postId',`posts`.`title`,`comments`.`id` AS 'commentId',`comments`.`body` 
FROM `posts`, `comments` 
WHERE `comments`.`body` LIKE ? 
AND `comments`.`postId` = `posts`.`id`;");
          // Присваиваем значение в выражение ("s" - строка)
          $stmt->bind_param("s", $search_query);
          $stmt->execute(); // Выполняем выражение
          $result = $stmt->get_result(); // Получаем результат выполнения выражения
          // Проверяем количество строк в результате, если 0 - возвращаем, что у нас нет результатов
          if ($result->num_rows > 0) {
            // Проходим циклом по всем строкам.
            while ($row = $result->fetch_assoc()) {
                // Шаблон для результата
                echo
                '<div class="card mb-3" style="width: 36rem;">
                  <div class="card-header">
                    <a href="https://jsonplaceholder.typicode.com/posts/'.$row["postId"].'/comments">#'.$row["postId"].'</a>
                    '.$row["title"].'
                  </div>
                  <div class="card-body">
                    <div class="card-title">
                    Комментарий <a href="https://jsonplaceholder.typicode.com/comments/'.$row["commentId"].'">#'.$row["commentId"].'</a>
                    </div>
                    <div class="card-text">
                      '.$row["body"].'
                    </div>
                  </div>
                </div>';
              }
            } else {
              // Если результатов нет -
              echo 
              '<div class="alert alert-warning" role="alert">
                0 результатов...
              </div>';
            }
          $conn->close(); // Закрывам соединение
        }
      ?>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>