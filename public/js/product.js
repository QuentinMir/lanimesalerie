"use strict";

// GALERIE PRODUIT - sélection des photos
function myFunction(imgs) {
    let expandImg = document.getElementById("expandedImg");
    expandImg.src = imgs.src;
    expandImg.parentElement.style.display = "block";
}

document.querySelectorAll("input[name=stars]").forEach((radio) =>
    radio.addEventListener("change", function () {
        console.log("Nouveau rating: " + this.value);
    })
);


/*function addChecked() {
    document.querySelectorAll("input[name=stars]").forEach((radio) => radio.removeAttribute('checked'));
    this.setAttribute('checked', '');
}

document.querySelectorAll("input[name=stars]").forEach((radio) =>
    radio.addEventListener("change", addChecked)
);*/

/*
document.addEventListener("DOMContentLoaded", function () {
document.querySelectorAll('.form-check')
});*/
