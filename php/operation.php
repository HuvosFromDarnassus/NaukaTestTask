
<!-- ОПЕРАЦИИ С БАЗОЙ ДАННЫХ: CREATE, READ, UPDATE, DELETE -->
<?php

require_once("db.php"); // Скрипт подключения к базе данных
require_once("component.php"); // Скрипт генерации элемента HTML

$con = Createdb(); // Функция подключения к БД в переменную $con

// При нажатии кнопки "create"
if (isset($_POST['create'])) {
    createData(); // Вызов функции createData
}

// При нажатии кнопки "update"
if (isset($_POST['update'])) {
    updateData(); // Вызов функции updateData
}

// При нажатии кнопки "delete"
if (isset($_POST['delete'])) {
    deleteRecord(); // Вызов функции deleteRecord
}

// При нажатии кнопки "deleteall"
if (isset($_POST['deleteall'])) {
    deleteAll(); // Вызов функции deleteAll
}

// createData - ЗАНОСИТ ДАННЫЕ ИЗ ФОРМЫ СОЗДАНИЯ В БАЗУ ДАННЫХ
function createData() {

    // Данные из полей ввода в переменные
    $preview = addslashes(file_get_contents($_FILES["picture"]["tmp_name"]));
    $firstname = textboxValue("first_name");
    $town = textboxValue("town");
    $lastname = textboxValue("last_name");
    $street = textboxValue("street");
    $birthDate = $_POST['birth_date'];
    $age = calculateAge($birthDate);
    $house = textboxValue("house");
    $job = textboxValue("job");
    $apartment = textboxValue("apartment");

    // Формирование адресса
    $adress = 'г.' . $town . ' ' . 'ул. ' . $street . ' ' . $house . ' кв.' . $apartment;

    // Если чекбокс "удаленной работы" отмечен, то $isRemoteWork = 1, иначе $isRemoteWork = 0
    if(!empty($_POST)) {
        if(!empty($_POST['isRemoteWork'])) {
            $isRemoteWork = 1;
        } else {
            $isRemoteWork = 0;
        }
       }

    // Проверка на заполненные поля
    if($preview && $firstname && $lastname && $birthDate 
    && $age && $job && $adress) {

        // Запрос SQL - Записываем в столбцы соответствующие данные
        $sql = "INSERT INTO employees_list (preview, first_name, last_name, 
        birth_date, age, job, isRemoteWork, adress) VALUES ('$preview', '$firstname', 
        '$lastname', '$birthDate', '$age', '$job', '$isRemoteWork', '$adress')";

        // Error check
        if (mysqli_query($GLOBALS['con'], $sql)) {
            TextNode("success", "Запись успешно добавлена");
        } else {
            TextNode("error", "Ошибка записи в таблицу");
        }

    } else {
       TextNode("error", "Заполните поля ввода");
    }
}

// search - НАХОДИТ СВЯЗАННЫЕ ДАННЫЕ ПО ВВЕДЕННЫМ ДАННЫМ ИЗ ПОЛЯ "ПОИСК"
function search() {
    $l_name = $_POST['fio'];

    // Запрос SQL - Берем все данные из строки где last_name='$l_name'
    $sql = "SELECT * FROM employees_list WHERE (last_name='$l_name')";

    // Полученные данные в переменную $result
    $result = mysqli_query($GLOBALS['con'], $sql);

    // Проверка на наличие данных
    if (mysqli_num_rows($result) > 0) {
        return $result;
    }

}

// calculateAge - ВЫЧИСЛЯЕТ ВОЗРАСТ ПО ВВЕДЕННОЙ ДАТЕ РОЖДЕНИЯ
function calculateAge($birthDate) {
    $date = new DateTime($birthDate);
    $now = new DateTime();
    $interval = $now->diff($date);
    return $interval->y;
}

function getFullYears($birthdayDate) {
    $datetime = new DateTime($birthdayDate);
    $interval = $datetime->diff(new DateTime(date("Y-m-d")));
    return $interval->format("%Y");
}

// textboxValue - Перевод убирает спец.символы для запроса в SQL, проверка на наличие данных
function textboxValue($value) {
    $textbox = mysqli_real_escape_string($GLOBALS['con'], trim($_POST[$value]));
    if(empty($textbox)) {
        return false;
    } else {
        return $textbox;
    }
}

// TextNode - Формирует сообщения об успехе или ошибке по введенным параметрам
function TextNode($classname, $msg) {
    $element = "<h4 class='$classname'>$msg</h4>";
    echo $element;
}

// getData - ФОРМИРУЕТ МАССИВ ВСЕХ ДАННЫХ ИЗ БАЗЫ ДАННЫХ
function getData() {
    $sql = "SELECT * FROM employees_list";

    $result = mysqli_query($GLOBALS['con'], $sql);

    if (mysqli_num_rows($result) > 0) {
        return $result;
    }

}

// updateData - ЗАНОСИТ ДАННЫЕ ИЗ ФОРМЫ ОБНОВЛЕНИЯ ДАННЫХ В БАЗУ ДАННЫХ, ОБНОВЛЯЕТ СТАРЫЕ
function updateData() {

    // Данные из полей ввода в переменные
    $_id = textboxValue("id");
    $preview = addslashes(file_get_contents($_FILES["picture"]["tmp_name"]));
    $firstname = textboxValue("first_name");
    $town = textboxValue("town");
    $lastname = textboxValue("last_name");
    $street = textboxValue("street");
    $birthDate = $_POST['birth_date'];
    $age = calculateAge($birthDate);
    $house = textboxValue("house");
    $job = textboxValue("job");
    $apartment = textboxValue("apartment");

    // Формирование адресса
    $adress = 'г.' . $town . ' ' . 'ул. ' . $street . ' ' . $house . ' кв.' . $apartment;

    // Если чекбокс "удаленной работы" отмечен, то $isRemoteWork = 1, иначе $isRemoteWork = 0
    if(!empty($_POST)) {
        if(!empty($_POST['isRemoteWork'])) {
            $isRemoteWork = 1;
        } else {
            $isRemoteWork = 0;
        }
       }

    // Проверка на заполненные поля
    if($firstname && $lastname && $birthDate 
    && $age && $job && $adress) {

        // Запрос SQL - Обновляет данные в соответствующих стоблцах
        $sql = "UPDATE employees_list SET preview='$preview', first_name='$firstname', 
        last_name='$lastname', birth_date='$birthDate', age='$age', job='$job', 
        isRemoteWork='$isRemoteWork', adress='$adress' WHERE id='$_id'";

        // Error check
        if (mysqli_query($GLOBALS['con'], $sql)) {
            TextNode("success", "Данные успешно обновленны");
        } else {
            TextNode("error", "Ошибка обновления данных");
        }

    } else {
       TextNode("error", "Заполните поля ввода");
    }
}

// deleteRecord - Удаляет строку с данными по полученному id
function deleteRecord() {
    $_id = (int)textboxValue("id");

    $sql = "DELETE FROM employees_list WHERE id='$_id'";

    if (mysqli_query($GLOBALS['con'], $sql)) {
        TextNode("success", "Данные успешно удалены");
    } else {
        TextNode("error", "Ошибка удаления данных");
    }
}

// deleteBtn - Отображает кнопку "удалить все", если больше 1 записи в таблице
function deleteBtn() {
    $result = getData();
    $i = 0;
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $i++;
            if ($i > 1) {
                buttonElement("btn-deleteall", "btn btndeleteall", "Удалить ВСЕ", "deleteall", "");
                return;
            }
            
        }
    }
}

// deleteAll - Удаляет таблицу с данными, пересоздает такую же с тем же названием
function deleteAll() {
    $sql = "DROP TABLE employees_list";

    if (mysqli_query($GLOBALS['con'], $sql)) {
        TextNode("success", "Все данные успешно удалены");
        Createdb();
    } else {
        TextNode("error", "Ошибка удаления данных");
    }
}

// setID - Отображает id в поле id в форме обнновления данных
function setID(){
    $getid = getData();
    $id = 0;
    if ($getid) {
        while ($row = mysqli_fetch_assoc($getid)) {
            $id = $row['id'];
        }
    }
    return ($id + 1);
}