<!-- СТРАНИЦА ТАБЛИЦЫ С ДАННЫМИ -->
<?php 

// ИМПОРТ СКРИПТОВ
require_once("../EmployeesPrototype/php/operation.php"); // скрипт основных операция: добавдения, чтения, обновления, удаления.И вспомогательных функция
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet"> 
    <link rel="shortcut icon" href="images/icon.jpg" type="image/x-icon" />
    <title>Сотрудники</title>
</head>
<body>
    <main>
        <div class="container">

            <!-- ФОРМА РЕДАКТИРОВАНИЯ -->
            <form enctype="multipart/form-data" method="post" class="popup">
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
                            <?php inputElement("text", "ID", "id", setID())?>
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
                        <div class="btns_table upd_btns">

                            <!-- Кнопка обновления отредактированных данных -->
                            <?php buttonElement("btn-update", "btn btnupdate", "Изменить", "update", "") ?>

                            <!-- Кнопка удаления данных выбранной строки таблицы -->
                            <?php buttonElement("btn-delete", "btn btndelete", "Удалить", "delete", "") ?>
                        </div>
                    </table>
                </div>
            </form>

            <!-- Форма с просмотром данных таблицы, кнопкой удаления всех данных, 
            переходом к форме создания данных и поиском в таблице -->
            <form method="post">
                <div class="btns_table read_btns">

                    <!-- Переход к форме создания новой записи -->
                    <a class="btn totable" href="index.php">Новый сотрудник</a>
                    
                    <!-- Кнопка чтения данных -> просмотр данных из БД и трансляция на страницу -->
                    <?php buttonElement("btn-read", "btn btnread", "Просмотреть", "read", "") ?>

                    <!-- Кнопка удаления одной строки таблицы -->
                    <?php deleteBtn();?>
                </div>

                <!-- Поле записи -> берет запись с поля, ищет в БД и отображает строку со связанными данными -->
                <input class="search" type="text" name="fio" placeholder="Фамилия сотрудника" >

                <!-- Кнопка найти -> отправляет данные с поля поиска для поиска записи по введенным данным -->
                <input class="search_btn" type="submit" name="search" value="Найти" >
            </form>

            
            <!-- ТАБЛИЦА СОТРУДНИКОВ - ДАННЫЕ ИЗ БД -->
            <table id="table-id" class="table">
                
                <!-- Заголовок столбцов таблицы -->
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Превью</th>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Дата рождения</th>
                        <th>Возраст</th>
                        <th>Должность</th>
                        <th>Удаленная работа</th>
                        <th>Адресс</th>
                    </tr>
                </thead>

                <!-- Тело таблицы - данные из БД -->
                <tbody id="tbody">
                    <?php

                    // При нажатии на кнопку "просмотр"
                    if (isset($_POST['read'])) {

                        // Вызывается функция getData и помещается в переменную $result
                        $result = getData();

                        // Если $result не пустой
                        if ($result) {

                            // Цикл трансляции данных из БД по ячейкам в таблице
                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['id']?></td>
                                    <td data-id="<?php echo $row['id']?>"><img width="100px" height="100px" src="data:image/jpeg;base64, <?php echo base64_encode($row['preview']);?>"></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['first_name']?></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['last_name']?></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['birth_date']?></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['age']?></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['job']?></td>
                                    <td data-id="<?php echo $row['id']?>">
                                    <?php 

                                    // Если isRemoteWork == 1, то чекбокс отмеченый, иначе пустой
                                    if ($row['isRemoteWork'] == 1) {
                                        echo "<input width='20px' type='checkbox' class='check' checked>";
                                    } else {
                                        echo "<input width='20px' type='checkbox' class='check' >";
                                    }
                                    ?></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['adress']?></td>
                                    <td><img class="btnedit" data-id="<?php echo $row['id'];?>" src="https://img.icons8.com/ultraviolet/40/000000/edit.png"/></td>
                                    
                                </tr>

                            <?php
                            }
                        }
                    }
                    
                    // При нажатии на кнопку "поиск"
                    if (isset($_POST['search'])) {

                        // Вызывается функция search и помещается в переменную $result
                        $result = search();

                        // Если $result не пустой
                        if ($result) {

                            // Цикл трансляции данных из БД по ячейкам в таблице
                            while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['id']?></td>
                                    <td data-id="<?php echo $row['id']?>"><img width="100px" height="100px" src="data:image/jpeg;base64, <?php echo base64_encode($row['preview']);?>"></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['first_name']?></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['last_name']?></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['birth_date']?></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['age']?></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['job']?></td>
                                    <td data-id="<?php echo $row['id']?>">
                                    <?php 

                                    // Если isRemoteWork == 1, то чекбокс отмеченый, иначе пустой
                                    if ($row['isRemoteWork'] == 1) {
                                        echo "<input width='20px' type='checkbox' class='check' checked>";
                                    } else {
                                        echo "<input width='20px' type='checkbox' class='check' >";
                                    }
                                    ?></td>
                                    <td data-id="<?php echo $row['id']?>"><?php echo $row['adress']?></td>
                                    <td><img class="btnedit" data-id="<?php echo $row['id'];?>" src="https://img.icons8.com/ultraviolet/40/000000/edit.png"/></td>
                                </tr>

                            <?php
                            }
                        }
                    }
                    
                    ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Импорт скриптов JS -->
    <script src="../js/jquery-3.4.1.min.js"></script>
    <script src="../js/main.js"></script>   

    <!-- Скрипт сортировки данных таблицы -->
    <script src='../js/src/tablesort.js'></script>

    <!-- Типы сортировок для скрипта tablesort.js-->
    <script src='../js/src/sorts/tablesort.number.js'></script>
    <script src='../js/src/sorts/tablesort.date.js'></script>

    <script>

        // Выбираем таблицу по id и отдем скрипту tablesort
        new Tablesort(document.getElementById('table-id'));
    </script>

    <!-- Скрипт изменения ширины столбцов таблицы -->
    <script>
        var table = document.getElementById('table-id');
        resizableGrid(table);

        function resizableGrid(table) {
        var row = table.getElementsByTagName('tr')[0],
        cols = row ? row.children : undefined;
        if (!cols) return;
        
        for (var i=0;i<cols.length;i++){
        var div = createDiv(table.offsetHeight);
        cols[i].appendChild(div);
        cols[i].style.position = 'relative';
        setListeners(div);
        }

        function createDiv(height){
        var div = document.createElement('div');
        div.style.top = 0;
        div.style.right = 0;
        div.style.width = '5px';
        div.style.position = 'absolute';
        div.style.cursor = 'col-resize';
        /* remove backGroundColor later */
        div.style.backgroundColor = 'red';
        div.style.userSelect = 'none';
        /* table height */
        div.style.height = height+'px';
        return div;
        }

        function setListeners(div){
        var pageX,curCol,nxtCol,curColWidth,nxtColWidth;
        div.addEventListener('mousedown', function (e) {
        curCol = e.target.parentElement;
        nxtCol = curCol.nextElementSibling;
        pageX = e.pageX;
        curColWidth = curCol.offsetWidth
        if (nxtCol)
        nxtColWidth = nxtCol.offsetWidth
        });

        document.addEventListener('mousemove', function (e) {
        if (curCol) {
        var diffX = e.pageX - pageX;
        
        if (nxtCol)
            nxtCol.style.width = (nxtColWidth - (diffX))+'px';

        curCol.style.width = (curColWidth + diffX)+'px';
        }
        });

        document.addEventListener('mouseup', function (e) { 
        curCol = undefined;
        nxtCol = undefined;
        pageX = undefined;
        nxtColWidth = undefined;
        curColWidth = undefined;
        });
        }

        function createDiv(height){
        var div = document.createElement('div');
        div.style.top = 0;
        div.style.right = 0;
        div.style.width = '5px';
        div.style.position = 'absolute';
        div.style.cursor = 'col-resize';
        div.style.userSelect = 'none';
        div.style.height = height+'px';
        div.className = 'columnSelector';
        return div;
        }

        }
    </script>
</body>
</html>