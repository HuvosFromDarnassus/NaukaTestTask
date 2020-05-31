
<!-- СКРИПТ ГЕНЕРАЦИИ ПОЛЕЙ ВВОДА И КНОПОК -->
<?php

function inputElement($type, $placeholder, $name, $value) {
    $ele = "
    <input type='$type' placeholder='$placeholder', name='$name', value='$value'>";

    echo $ele;
}

function buttonElement($btnid, $styleclass, $text, $name, $attr) {
    $btn = "
        <button id='$btnid' class='$styleclass' name='$name' '$attr'>$text</button>
    ";

    echo $btn;
}