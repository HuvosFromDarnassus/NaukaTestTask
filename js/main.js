
var popup = document.querySelector('.popup'); // Элемент, который будет скрыт и отображаться по нажатию
popup.style.display = 'none'; // Скрыть попап по умолчанию

let id = $("input[name*='id']"); // Нельзя изменить поле id
id.attr("readonly", "readonly");

// При нажатии кнопки редктирования
$(".btnedit").click(e => {

    // Отобразить попап - поле радактирования
    if(popup.style.display == 'none')  
        popup.style.display = 'flex';

    let textvalues = displayData(e); // Функция displayData в переменную textvalues

    // Элементы в перемнные
    let firstname = $("input[name*='first_name']");
    let lastname = $("input[name*='last_name']");
    let town = $("input[name*='town']");
    let street = $("input[name*='street']");
    let house = $("input[name*='house']");
    let apartment = $("input[name*='apartment']");
    let birthdate = $("input[name*='birth_date']");
    let job = $("input[name*='job']");
    let isRemoteWork = $("input[name*='isRemoteWork']");
    
    // Запись в поля ввода значений из массива textvalues
    id.val(textvalues[0]);
    firstname.val(textvalues[2]);
    lastname.val(textvalues[3]);
    birthdate.val(textvalues[4]);
    job.val(textvalues[6]);
    isRemoteWork.val(textvalues[7]);
});

// displayData - Формирует массив со значениями в строке с соответствующем id
function displayData(e) {   
    let id = 0;
    const td = $("#tbody tr td");
    let textvalues = [];

    for (const value of td) {
        if (value.dataset.id == e.target.dataset.id) {
            textvalues[id++] = value.textContent;        
        }        
    }

    return textvalues;
}