other_lang = document.getElementsByName('language');
input_other_lang = document.getElementById('other_lang');

other_lang.forEach(element => {
    element.addEventListener('click', ()=>{
        if(element.value === 'other'){
            input_other_lang.setAttribute('required', '');
        }else{
            input_other_lang.removeAttribute('required', '');
        }
    });
});