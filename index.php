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
  <?php
  $search_query = '';
  if (isset($_GET['search_query'])) {
      $search_query = $_GET['search_query'];
      if ($search_query == '') {
          echo 'Форма пуста!';
          die();
      }
      if (iconv_strlen($search_query) < 3) {
          echo 'В запросе меньше 3 символов!';
          die();
      }

      require_once 'config.php';

      $conn = new mysqli(
          $SQL_SERVERNAME,
          $SQL_USERNAME,
          $SQL_PASSWORD,
          $SQL_DBNAME
      );

      if ($conn->connect_error) {
          die('Connection failed: ' . $conn->connect_error);
      }

      $sql = "SELECT DISTINCT `postId` FROM `comments` WHERE `body` LIKE '%$search_query%'";
      $result = $conn->query($sql);
      var_dump($result);
      echo '<br>';
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo 'postId: ' . $row['postId'] . '<br>';
          }
      } else {
          echo '0 результатов...';
      }
      $conn->close();
  }
  ?>
  </div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>