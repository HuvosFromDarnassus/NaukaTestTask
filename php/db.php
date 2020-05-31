
<!-- СОЕДИНЕНИЕ С БАЗОЙ ДАННЫХ И ТАБЛИЦЕ -->
<?php

// Createdb - Подключение к базе данных $dbname
function Createdb(){
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "employee";

    // Запрос на подлючение к базу данных в переменную $con
    $con = mysqli_connect($servername, $username, $password);

    // Если не удалось подключиться к базе данных
    if (!$con) {

        // Разорвать соединение и вывести сообщение с ошибкой
        die("Подключение к базе данных не удалось: " . mysqli_connect_error());
    }

    // Запрос SQL - Создание базы данных $dbname, если нет
    $sql = "CREATE DATABASE IF NOT EXISTS $dbname";

    // Если подключение удалось
    if (mysqli_query($con, $sql)) {
        $con = mysqli_connect($servername, $username, $password, $dbname);

        // Запрос SQL - Создание таблицы employees_list
        $sql = "
                CREATE TABLE IF NOT EXISTS employees_list(
                    id INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                    preview LONGBLOB NOT NULL,
                    first_name VARCHAR(25) NOT NULL,
                    last_name VARCHAR(25) NOT NULL,
                    birth_date DATE NOT NULL,
                    age INT(11) NOT NULL,
                    job VARCHAR(25) NOT NULL,
                    isRemoteWork BOOL NOT NULL,
                    adress VARCHAR(255) NOT NULL
                );
        ";
        
        // Если подключение удалось
        if (mysqli_query($con, $sql)) {
            return $con;
        } else {
            echo "Невозможно создать таблицу";
        }

    } else {
        echo "Произошла ошибка во время создания базы данных" . mysqli_error($con);
    }
}