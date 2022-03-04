"use strict";

// GALERIE PRODUIT - sélection des photos
function myFunction(imgs) {
    let expandImg = document.getElementById("expandedImg");
    expandImg.src = imgs.src;
    expandImg.parentElement.style.display = "block";
}


// Formulaire avis produit
document.addEventListener("DOMContentLoaded", function () {
    let choices = '';
    let block = document.querySelector('#avis_note');
    let star = "";

    for (let i = 0; i < 5; i++) {
        star += `<span class="icon">★</span>`

        choices += ` 
        <label>
            <input type="radio" id="avis_note_${i}" name="avis[note]"
                    value="${i + 1}">
                   ${star}
            </label>`
    }

    block.classList.add('rating', 'text-start');
    block.innerHTML = choices

});
