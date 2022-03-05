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

/****************bar étoiles client****************/

function drawHBarChart(target, data, params) {
    let $target = $(target);
    if ($target.length === 0) {
        return;
    }

    // default params
    let p = {
        containerClassName: 'bs-bar-chart',  // detect if the container already init.
                                             // override if you need another class name
        initAnimation: true,
        max: 0,
        xInc: 3,
        labelWidth: 3,                  // bootstrap grid col width (i.e. 3/12)
        rgbaFrom: [190, 50, 60, 100],    // ignore if colors is not null
        rgbaTo: [120, 10, 20, 100],     // ignore if colors is not null
        colors: null
    };


    let firstTime = !$target.hasClass(p.containerClassName);
    $target.addClass(p.containerClassName);


    // find the max x-axis value, if the new value is greater than the chart
    // increase the chart's x-axis max value

    let total = 0;
    for (let i = 0; i < data.length; i++) {
        total += data[i].count;
    }

    for (let i = 0; i < data.length; i++) {
        let d = data[i];
        let name = d.name;
        let count = d.count;
        let pct = d.count * 100 / total;
        let color;
        if (p.colors) {
            color = p.colors[i % p.colors.length];
        } else {
            color = 'rgba(' +
                Math.round((p.rgbaFrom[0] + (p.rgbaFrom[0] > p.rgbaTo[0] ? -1 : 1) * Math.abs(p.rgbaTo[0] - p.rgbaFrom[0]) / (Math.max(p.max, data.length) - 1) * i)) + ',' +
                Math.round((p.rgbaFrom[1] + (p.rgbaFrom[1] > p.rgbaTo[1] ? -1 : 1) * Math.abs(p.rgbaTo[1] - p.rgbaFrom[1]) / (Math.max(p.max, data.length) - 1) * i)) + ',' +
                Math.round((p.rgbaFrom[2] + (p.rgbaFrom[2] > p.rgbaTo[2] ? -1 : 1) * Math.abs(p.rgbaTo[2] - p.rgbaFrom[2]) / (Math.max(p.max, data.length) - 1) * i)) + ',' +
                (p.rgbaFrom[3] + (p.rgbaFrom[3] > p.rgbaTo[3] ? -1 : 1) * Math.abs(p.rgbaTo[3] - p.rgbaFrom[3]) / (Math.max(p.max, data.length) - 1) * i) + ')';
        }
        let $bar = $target.find('> div:nth-child(' + (i + 1) + ')');
        if (firstTime || $bar.length === 0) {
            let $newbar = $('<div class="row" data-item-id="' + d.id +
                '" style="margin-top:3px;">' +
                '<div class="col-sm-' + p.labelWidth + ' chart-name">' + name + '</div>' +
                '<div class="col-sm-' + (12 - p.labelWidth - 1) + '">' +
                '<div class="progress-bar"' +
                ' data-percentage="' + pct + '" style="background-color:' + color +
                ';width:' + (firstTime && p.initAnimation ? 0 : pct) + '%;">&nbsp;</div>' +
                '</div>' + '<div class="col-sm-1">' + count + '</div>' +
                '</div>');
            $target.append($newbar);
            if (!firstTime) {
                $newbar.hide().fadeIn();
            }
        } else {
            $bar.find('.progress-bar').css({'width': pct + '%'});
            if ($bar.attr('data-item-id') != d.id) {
                $bar.attr('data-item-id', d.id).find('.chart-name').attr('data-anim-name', name).fadeOut(function () {
                    $(this).html($(this).attr('data-anim-name')).removeAttr('data-anim-name').fadeIn();
                });
            }
        }
    }
    if (firstTime && p.initAnimation) {
        // animate
        window.setTimeout(function () {
            $target.find('.progress-bar').each(function () {
                $(this).css({'width': $(this).data('percentage') + '%'});
            });
        }, 0);
    } else {
        // removed extra bars (due to the count change to 0)
        let extraBarSel = '> div';
        if (data.length > 0) {
            // jQuery :gt(0) doesn't work so this 'if' is necessary
            extraBarSel += ':gt(' + (data.length - 1) + ')';
        }
        $target.find(extraBarSel).fadeOut(function () {
            $(this).remove();
        });
    }
}

// ---------- Demo ----------
let blockAvis = document.querySelector('#avis');
let star1 = parseInt(blockAvis.getAttribute('data-star1'));
let star2 = parseInt(blockAvis.getAttribute('data-star2'));
let star3 = parseInt(blockAvis.getAttribute('data-star3'));
let star4 = parseInt(blockAvis.getAttribute('data-star4'));
let star5 = parseInt(blockAvis.getAttribute('data-star5'));
console.log(star1, star2, star3, star4, star5);
let sample = [
    {id: 1001, name: '5 étoiles', count: star5},
    {id: 1002, name: '4 étoiles', count: star4},
    {id: 1003, name: '3 étoiles', count: star3},
    {id: 1004, name: '2 étoiles', count: star2},
    {id: 1005, name: '1 étoile', count: star1},

];

drawHBarChart('#chart1', sample);




