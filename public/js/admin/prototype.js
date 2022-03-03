$(document).ready(function () {
    $('.add-another-collection-widget').click(function (e) {
        var list = $($(this).attr('data-list-selector'));

        var counter = list.data('widget-counter') || list.children().length;


        var newWidget = $('#produit_images').attr('data-prototype');


        newWidget = newWidget.replace(/__name__/g, counter);

        counter++;
        // And store it, the length cannot be used if deleting widgets is allowed
        list.data('widget-counter', counter);

        // create a new list element and add it to the list
        var newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });
});