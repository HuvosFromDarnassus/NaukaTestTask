<!-- ГЛАВНАЯ СТРАНИЦА -->
<?php 

// ИМПОРТ СКРИПТОВ
require_once("../EmployeesPrototype/php/component.php"); // Скрипт генерации элемента HTML
require_once("../EmployeesPrototype/php/operation.php"); // Скрипт основных операций: добавления, чтения, обновления, удаления. И вспомогательных функций
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="shortcut icon" href="images/icon.jpg" type="image/x-icon" />
    <title>Новый сотрудник</title>
</head>
<body>
    <main>
        <div class="container">
            <a href="http://www.ntik.ru/"><p class="logo"><img src="images/logo.png" alt="Логотип"></p></a>
            
            <!-- ФОРМА ВВОДА ДАННЫХ -->
            <form enctype="multipart/form-data" method="post">
                
                <div class="photo_upload">

                    <!-- Предпросмотр загруженного изображения -->
                    <img width="270px" height="270px" id="output">
                    
                    <!-- Загрузка изображения -->
                    <input name="picture" type="file" id="file-selector"/>
                    
                    <p id="status"></p>
                    <div>                
                    </div>

                    <!-- Скрипт для предпросмотра изображения в форме -->
                    <script>
                    const status = document.getElementById('status');
                    const output = document.getElementById('output');
                    if (window.FileList && window.File && window.FileReader) {
                        document.getElementById('file-selector').addEventListener('change', event => {
                        output.src = '';
                        status.textContent = '';
                        const file = event.target.files[0];
                        if (!file.type) {
                            status.textContent = 'Error: The File.type property does not appear to be supported on this browser.';
                            return;
                        }
                        if (!file.type.match('image.*')) {
                            status.textContent = 'Error: The selected file does not appear to be an image.'
                            return;
                        }
                        const reader = new FileReader();
                        reader.addEventListener('load', event => {
                            output.src = event.target.result;
                        });
                        reader.readAsDataURL(file);
                        }); 
                    }
                    </script>
                </div>
                
                <div class="input_table">
                    
                <!-- Поля ввода данных -->
                    <table>
                        <tr>
                            <?php inputElement("text", "Имя", "first_name", "")?>
                            <?php inputElement("text", "Город", "town", "")?>
                        </tr>
                        <tr>
                            <?php inputElement("text", "Фамилия", "last_name", "")?>
                            <?php inputElement("text", "Улица", "street", "")?>
                        </tr>
                        <tr>
                            <?php inputElement("date", "", "birth_date", "")?>
                            <?php inputElement("number", "Дом", "house", "")?>
                        </tr>
                        <tr>
                            
                            <!-- Поле - список -->
                            <select name="job">
                                <option value="Менеджер">Менеджер</option>
                                <option value="Программист">Программист</option>
                                <option value="Инженер">Инженер</option>
                                <option value="Бухгалтер">Бухгалтер</option>
                                <option value="Экономист">Экономист</option>
                                <option value="Юрист">Юрист</option>
                            </select>
                            <?php inputElement("number", "Квартира", "apartment", "")?>
                        </tr>
                        <tr>
                            <p class="remote_work"><input type="checkbox" name ="isRemoteWork">Удаленная работа</p>
                        </tr>
                        
                    </table>
                </div>
                <div class="btns_form">
                        
                    <!-- Кнопка подтверждения -> отправляет данные с формы в БД -->
                    <?php buttonElement("btn-create", "btn btncreate", "Создать", "create", "") ?>
                        
                    <!-- Переход к таблице с данными -->
                    <a class="btn totable" href="list.php">Таблица</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>