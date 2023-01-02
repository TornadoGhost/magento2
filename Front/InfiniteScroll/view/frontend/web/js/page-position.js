// get reference to your scrollable div

/*const element = document.getElementsByClassName("ol.products");

// store element.scrollTop in local storage.
window.localStorage.setItem('scrollTop', element.scrollTop);

// get scrollTop value once back to the previous page
const scrollTop = window.localStorage.getItem('scrollTop')

// apply scrollTop to your scrollable div, to take scroll thumb to the exact position where it was left before
document.getElementsByClassName("ol.products").scrollTop = scrollTop;*/

let elements;

require([
    'jquery'
], function ($) {
    //same page position after returning on page


    //remove bottom page limiter
    $('.toolbar-products div:last-child').css("display", "none");
});

