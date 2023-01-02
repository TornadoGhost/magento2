let num = 2;
let hasList = true;
let timeOut = false;


window.addEventListener('scroll', () => {
    if (timeOut && !hasList && localStorage.getItem("magento2-quote-scroll") !== null) {
        return;
    }
    const {scrollTop, scrollHeight, clientHeight} = document.documentElement;

    if (scrollTop + clientHeight >= scrollHeight && hasList && !timeOut) {
        timeOut = true;
        console.log('asdasd');
        getList();
        setTimeout(function () {
            timeOut = false;
        }, 2000);
    }
});

function getList() {
    require([
            'jquery',
        ], function ($) {

        let pageLocation = window.location.href;

            $.ajax({
                url: pageLocation,
                type: "GET",
                data: {
                    p: num
                },
                success: function (data) {
                    let newData = jQuery.parseHTML(data);
                    let elements = $(newData).find("ol.products > li");
                    if (elements[0] === undefined) {
                        return hasList = false;
                    }
                    // $('ol.products').append(elements);
                    let i = 0;
                    for (i; i < elements.length; i++) {
                        $("ol.products").append(elements[i]);
                        let json = JSON.stringify({html: elements[i].outerHTML});
                        let parse = JSON.parse(json);
                        localStorage.setItem('page_' + num + '_element_' + i, parse['html']);
                    }
                    console.log(num);
                    num++;

                    let pageNumber = num - 1;
                    localStorage.setItem('pageNumber', String(pageNumber));
                    localStorage.setItem('elementNumber', String(i));


                    localStorage.setItem('pageLocation', pageLocation);

                    //get items
                    /*let check = localStorage.getItem('page_2_element_0');
                    let show = jQuery.parseHTML(check);
                    console.log(show[0]);*/

                    /*let elem = elements[0];
                    let elem2 = elements[1].outerHTML;
                    console.log(elem);
                    let json = JSON.stringify({html: elem.outerHTML});
                    let parse = JSON.parse(json);
                    parse.push = elem2;
                    console.log(parse);*/


                    /*localStorage.setItem('list', parse['html']);
                    localStorage.setItem('list2', parse['push']);
                    let getStor = localStorage.getItem('list');
                    let getStor2 = localStorage.getItem('list2');
                    // console.log(getStor);
                    console.log(getStor2);*/

                    /*let check = jQuery.parseHTML(parse["html"]);
                    let check2 = jQuery.parseHTML(parse["push"]);
                    console.log(check[0]);
                    console.log(check2[0]);*/

                    /*let json = JSON.stringify({html: {}});
                    let parse = JSON.parse(json);
                    console.log(parse);*/
                    // let check = jQuery.parseHTML(parse["html"]);
                    // console.log(check[0]);

                    /*localStorage.setItem('test1', parse);
                    let test1 = localStorage.getItem('test1');
                    let test2 = jQuery.parseHTML(parse["html"]);
                    console.log(test2[0]);*/
                }
            });
        }
    );
}

/*addEventListener('beforeunload', (event) => {
    localStorage.clear();
});*/


position();
function position() {
    require([
        'jquery'
    ], function ($) {
        $(document).ready(function () {
            let location = localStorage.getItem('pageLocation');
            // if (localStorage.getItem("magento2-quote-scroll") !== null) {
            if (localStorage.getItem('pageNumber') !== null && location === window.location.href) {

                console.log(location);

                let pN = localStorage.getItem('pageNumber');
                let eN = localStorage.getItem('elementNumber');
                let pageNumber = Number(pN);
                let elementNumber = Number(eN);
                num = pageNumber + 1;

                console.log(pageNumber + ' - pageNumber');
                console.log(elementNumber + ' - elementNumber');
                for(let num = 2; num <= pageNumber; num++){
                    for(let elem = 0; elem < elementNumber; elem++){
                        let data = localStorage.getItem('page_' + num + '_element_' + elem);
                        let elementLi = jQuery.parseHTML(data);
                        // console.log(elementLi[0]);
                        if(elementLi === undefined){
                            return ;
                        }
                        $("ol.products").append(elementLi[0]);
                    }
                }
                setTimeout(function (){
                    $(window).scrollTop(localStorage.getItem("magento2-quote-scroll"));
                }, 2000);
                setTimeout(function (){
                    localStorage.clear();
                }, 60000)
            }

            $(window).on("scroll", function () {
                localStorage.setItem("magento2-quote-scroll", $(window).scrollTop());
            });
        });
    });
}

//recorded data of Li element to json and further obtaining;
/*
let elem = elements[0];
console.log(elem);
let json = JSON.stringify({html: elem.outerHTML});
let parse = JSON.parse(json);
console.log(parse);
let check = jQuery.parseHTML(parse["html"]);
console.log(check[0]);*/
