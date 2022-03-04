-- Создание базы данных с название `blog-test-task`
CREATE DATABASE `blog-test-task`;

-- Создание таблицы `Posts` для хранение записей.
CREATE TABLE Posts
(
    `userId` INTEGER NOT NULL,
    `id` INTEGER AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(50) NOT NULL,
    `body` TEXT(268) NOT NULL
) ENGINE = InnoDB;

-- Создание таблицы `Comments` для хранения комментарий к записям
CREATE TABLE Comments
(
    `postId` INTEGER NOT NULL,
    `id` INTEGER AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL,
    `email` VARCHAR(50) NOT NULL,
    `body` TEXT(268) NOT NULL
)ENGINE = InnoDB;
