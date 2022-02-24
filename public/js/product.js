"use strict";

/*************************************
 * ** VARIABLES    ------------------
 *  *********************************/

// compter les étoiles
let starCount;
let sommeNote;

/*************************************
 * ** FONCTIONS    -----------------
 *  *********************************/

// majuscule première lettre
function titleCase(string) {
  return string[0].toUpperCase() + string.slice(1).toLowerCase();
}

// donne la valeur de l'input etoile au moment du clic
function starValue() {
  let starValue = document.querySelector("input[name=stars]:checked").value;
  let starHTML;
  switch (starValue) {
    case "1":
      starHTML = "★";
      starCount.oneStar++;
      break;
    case "2":
      starHTML = "★★";
      starCount.twoStar++;
      break;
    case "3":
      starHTML = "★★★";
      starCount.threeStar++;
      break;
    case "4":
      starHTML = "★★★★";
      starCount.fourStar++;
      break;
    case "5":
      starHTML = "★★★★★";
      starCount.fiveStar++;
      break;
  }

  return starHTML;
}
/////////////////////////////////
// Comparer date avec date actuelle

(function (global) {
  const SECOND = 1;
  const MINUTE = 60;
  const HOUR = 3600;
  const DAY = 86400;
  const MONTH = 2629746;
  const YEAR = 31556952;
  const DECADE = 315569520;

  global.timeAgo = function (date) {
    var now = new Date();
    var diff = Math.round((now - date) / 1000);

    var unit = "";
    var num = 0;
    var plural = false;

    switch (true) {
      case diff <= 0:
        return "À l'instant";

      case diff < MINUTE:
        num = Math.round(diff / SECOND);
        unit = "sec";
        plural = num > 1;
        break;

      case diff < HOUR:
        num = Math.round(diff / MINUTE);
        unit = "min";
        plural = num > 1;
        break;

      case diff < DAY:
        num = Math.round(diff / HOUR);
        unit = "heure";
        plural = num > 1;
        break;

      case diff < MONTH:
        num = Math.round(diff / DAY);
        unit = "jour";
        plural = num > 1;
        break;

      case diff < YEAR:
        num = Math.round(diff / MONTH);
        unit = "mois";
        plural = num > 1;
        break;

      case diff < DECADE:
        num = Math.round(diff / YEAR);
        unit = "an";
        plural = num > 1;
        break;

      default:
        num = Math.round(diff / YEAR);
        unit = "an";
        plural = num > 1;
    }

    var str = "";
    str += "Il y a ";
    if (num) {
      str += `${num} `;
    }

    str += `${unit}`;

    if (plural && unit !== "mois") {
      str += "s";
    }

    return str;
  };
})(window);

///////////////////////////////////
// Injection de l'avis l'avis sur la page
function publishRating() {
  let name = document.querySelector("#nomAvis").value.trim();
  // alerte nom vide
  if (name == "") {
    alert("Vous n'avez pas saisi de nom");
  }

  // alerte texte vide
  let text = document.querySelector("#avis").value.trim();
  if (text == "") {
    alert("Vous n'avez pas saisi de texte");
    return;
  }

  let blockAvis = document.querySelector("#blockAvis");
  let contentAvis = `<div class="col-lg-6 col-12 my-3">
  <div class="row align-items-center justify-content-around">
    <div class="col-5">
      <h6 class="my-0">${titleCase(name)} </h6>
    </div>
    <div class="col-3">
      <span class="icon fs-4 text-red my-0">${starValue()} </span>
    </div>
    <div class="col-4">
      <p class="my-0">-  ${timeAgo(new Date())} </p>
    </div>
  </div>
</div>
<div class="col-lg-6 d-none d-lg-block"></div>
<div class="col-12">
  <p class="ms-auto me-0 w-75 my-1">
  ' ${text} '
  </p>
  <hr>
</div>`;

  // injecter l'avis en fonction du contenu initial
  if (document.querySelector("#blockAvis h5") != null) {
    blockAvis.innerHTML = contentAvis;
  } else {
    blockAvis.innerHTML += contentAvis;
  }

  // reset les champs de saisie
  document.querySelector("#nomAvis").value = "";
  document.querySelector("#avis").value = "";

  // rafraichir le compte du nombre d'avis
  refreshNbAvis();
}
//////////////////////////////////////////////

// GALERIE PRODUIT - sélection des photos
function myFunction(imgs) {
  let expandImg = document.getElementById("expandedImg");
  expandImg.src = imgs.src;
  expandImg.parentElement.style.display = "block";
}

// Faire la somme de l'objet = combien d'avis
function sumValues(obj) {
  let sum = 0;
  for (var el in obj) {
    if (obj.hasOwnProperty(el)) {
      sum += parseInt(obj[el]);
    }
  }
  return sum;
}

//Somme pondérée des étoiles
function sommeNotes() {
  sommeNote = 0;
  for (let i = 0; i < Object.keys(starCount).length; i++) {
    sommeNote += (i + 1) * starCount[Object.keys(starCount)[i]];
  }
  return sommeNote;
}

// Indiquer le nombre d'avis (avec objet starCount) + afficher la note du produit
function refreshNbAvis() {
  let nbAvis = document.querySelectorAll("em.nbAvis");
  nbAvis.forEach((el) => (el.innerHTML = sumValues(starCount)));

  //Compter le nombre d'étoiles et injecter html
  document.querySelector("#star1").textContent = starCount.oneStar;
  document.querySelector("#star2").textContent = starCount.twoStar;
  document.querySelector("#star3").textContent = starCount.threeStar;
  document.querySelector("#star4").textContent = starCount.fourStar;
  document.querySelector("#star5").textContent = starCount.fiveStar;

  // note moyenne du produit
  let note = sommeNotes() / sumValues(starCount);

  // injection de la note
  if (isNaN(note)) {
    note = 0;
  }
  document.querySelector("#note").textContent = parseFloat(note).toFixed(1);

  // nb étoiles de la note
  starRating(note);
}

// Combien d'étoiles apparaissent en fonction de la note
function starRating(note) {
  let nbEtoile = document.querySelector("#starRating");
  let star = `<i class="me-1 fas fa-star text-red"></i>`;
  let halfStar = `<i class="me-1 fas fa-star-half text-red"></i>`;

  if (note <= 0.5) {
    nbEtoile.innerHTML = halfStar;
  } else if (note <= 1) {
    nbEtoile.innerHTML = star;
  } else if (note <= 1.5) {
    nbEtoile.innerHTML = star + halfStar;
  } else if (note <= 2) {
    nbEtoile.innerHTML = star + star;
  } else if (note <= 2.5) {
    nbEtoile.innerHTML = star + star + halfStar;
  } else if (note <= 3) {
    nbEtoile.innerHTML = star + star + star;
  } else if (note <= 3.5) {
    nbEtoile.innerHTML = star + star + star + halfStar;
  } else if (note <= 4) {
    nbEtoile.innerHTML = star + star + star + star;
  } else if (note <= 4.5) {
    nbEtoile.innerHTML = star + star + star + star + halfStar;
  } else if (note <= 5) {
    nbEtoile.innerHTML = star + star + star + star + star;
  }
}

/*************************************
 * ** MAIN  CODE  ----------------------
 *  *********************************/
document.addEventListener("DOMContentLoaded", function () {
  // compter les étoiles
  starCount = {
    oneStar: 0,
    twoStar: 0,
    threeStar: 0,
    fourStar: 0,
    fiveStar: 0,
  };

  // afficher nombre d'avis sur la page
  refreshNbAvis();

  let publiAvis = document.querySelector("#publiAvis");

  // target bouton publier avis
  publiAvis.addEventListener("click", publishRating);

  // tester la valeur des inputs
  document.querySelectorAll("input[name=stars]").forEach((radio) =>
    radio.addEventListener("change", function () {
      console.log("Nouveau rating: " + this.value);
    })
  );
});
