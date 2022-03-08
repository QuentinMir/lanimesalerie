/************** SLIDER PRODUITS**************/

var swiper = new Swiper(".mySwiper", {
    slidesPerView: 1,
    spaceBetween: -35,
    slidesPerGroup: 1,
    breakpoints: {
        768: {
            slidesPerView: 2,
        },

        991: {
            slidesPerView: 4,
        },
    },
    loop: false,
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },

    scrollbar: {
        el: ".swiper-scrollbar",
        draggable: false,
    },
});

/************** SLIDER MARQUES**************/
var swiper = new Swiper(".mySwiper2", {
    slidesPerView: 2,
    spaceBetween: -35,
    slidesPerGroup: 1,
    breakpoints: {
        768: {
            slidesPerView: 3,
        },

        991: {
            slidesPerView: 6,
        },
    },

    scrollbar: {
        el: ".swiper-scrollbar",
        draggable: true,
    },
});

/**************SMOOTH SLIDE DROPDOWN**************/

$(".dropdown-menu").addClass("invisible"); //FIRST TIME INVISIBLE

// ADD SLIDEDOWN ANIMATION TO DROPDOWN-MENU
$(".dropdown").on("show.bs.dropdown", function (e) {
    $(".dropdown-menu").removeClass("invisible");
    $(this).find(".dropdown-menu").first().stop(true, true).slideDown();
});

// ADD SLIDEUP ANIMATION TO DROPDOWN-MENU
$(".dropdown").on("hide.bs.dropdown", function (e) {
    $(this).find(".dropdown-menu").first().stop(true, true).slideUp();
});

/**************RECUP ID PANIER**************/

$("a.panier").click(function () {
    let id = $(this).data('id');
    let nom = $(this).data('nom');
    let prix = $(this).data('prix');
    $('#addPanier').attr('href', 'http://localhost:8000/panier/' + id + '/1');
    $('#addPanierStay').attr('href', 'http://localhost:8000/panier/add/' + id + '/1');
    $('#prixArticle').html(`${prix} €`);
    $('#nomArticle').html(`${nom}`);
});

/************** Navbar suppression ul vides et du chevron dans le parent **************/
uls = document.querySelectorAll('ul.dropdown-menu.dropdown-submenu');
/************** Navbar suppression ul vides categorie navbar **************/
navUls = document.querySelectorAll('ul.navUl');
document.addEventListener("DOMContentLoaded", function () {

    uls.forEach((ul) => {
        if (ul.childNodes.length === 1) {
            ul.parentNode.querySelector('.opac-icon').classList.add('d-none');
            ul.classList.add('d-none');
        }
    })

    /************** FIN Navbar suppression ul vides **************/

    navUls.forEach((ul) => {
        // console.log(ul.innerHTML);
        if (ul.childNodes.length === 7) {
            // ul.parentNode.querySelector('.opac-icon').classList.add('d-none');
            ul.classList.add('d-none');
        }
    })
    /************** FIN Navbar suppression ul vides **************/


})


