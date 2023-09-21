window.onload = () => {
let btn = document.getElementById('btnToggle');
let counter = 0;
let table = document.getElementById('toggleTable');
let card = document.getElementById('toggleCard');

btn.addEventListener("click", function ()
{
    counter++;
    if (counter % 2 !== 0) {
        table.className='invisible';
        card.className='';
    } else {
        card.className='invisible';
        table.className='';
    }
});}