"use strict";

$(document).ready(function () {

    $('.top-user-area .language').on('click', function () {
        window.location = this.href;
    });

    $('html').niceScroll({
        cursorcolor: "#000",
        cursorborder: "0px solid #fff",
        railpadding: {
            top: 0,
            right: 0,
            left: 0,
            bottom: 0
        },
        cursorwidth: "10px",
        cursorborderradius: "0px",
        cursoropacitymin: 0.2,
        cursoropacitymax: 0.8,
        boxzoom: true,
        horizrailenabled: false,
        zindex: 9999
    });

    // footer always on bottom
    var docHeight = $(window).height();
    var footerHeight = $('#main-footer').height();
    var footerTop = $('#main-footer').position().top + footerHeight;

    if (footerTop < docHeight) {
        $('#main-footer').css('margin-top', (docHeight - footerTop) + 'px');
    }
});


$('ul.slimmenu').slimmenu({
    resizeWidth: '992',
    collapserTitle: 'Main Menu',
    animSpeed: 250,
    indentChildren: true,
    childrenIndenter: ''
});


$('.btn').button();

$("[rel='tooltip']").tooltip();

////////////////////////////
$('.form-group').each(function () {
    var self = $(this),
        input = self.find('input');

    input.focus(function () {
        self.addClass('form-group-focus');
    })

    input.blur(function () {
        if (input.val()) {
            self.addClass('form-group-filled');
        } else {
            self.removeClass('form-group-filled');
        }
        self.removeClass('form-group-focus');
    });
});

$('div.bg-parallax').each(function () {
    var $obj = $(this);
    if ($(window).width() > 992) {
        $(window).scroll(function () {
            var animSpeed;
            if ($obj.hasClass('bg-blur')) {
                animSpeed = 10;
            } else {
                animSpeed = 15;
            }
            var yPos = -($(window).scrollTop() / animSpeed);
            var bgpos = '50% ' + yPos + 'px';
            $obj.css('background-position', bgpos);

        });
    }
});

$('.booking-item-review-expand').click(function (event) {
    console.log('baz');
    var parent = $(this).parent('.booking-item-review-content');
    if (parent.hasClass('expanded')) {
        parent.removeClass('expanded');
    } else {
        parent.addClass('expanded');
    }
});


$('.stats-list-select > li > .booking-item-rating-stars > li').each(function () {
    var list = $(this).parent(),
        listItems = list.children(),
        itemIndex = $(this).index();

    $(this).hover(function () {
        for (var i = 0; i < listItems.length; i++) {
            if (i <= itemIndex) {
                $(listItems[i]).addClass('hovered');
            } else {
                break;
            }
        }
        ;
        $(this).click(function () {
            for (var i = 0; i < listItems.length; i++) {
                if (i <= itemIndex) {
                    $(listItems[i]).addClass('selected');
                } else {
                    $(listItems[i]).removeClass('selected');
                }
            }
            ;
        });
    }, function () {
        listItems.removeClass('hovered');
    });
});

$('.booking-item-container').children('.booking-item').click(function (event) {
    if ($(this).hasClass('active')) {
        $(this).removeClass('active');
        $(this).parent().removeClass('active');
    } else {
        $(this).addClass('active');
        $(this).parent().addClass('active');
        $(this).delay(1500).queue(function () {
            $(this).addClass('viewed')
        });
    }
});

// // Lighbox image
// $('.popup-image').magnificPopup({
//     type: 'image'
// });


$('.form-group-select-plus').each(function () {
    var self = $(this),
        btnGroup = self.find('.btn-group').first(),
        select = self.find('select');
    btnGroup.children('label').last().click(function () {
        btnGroup.addClass('hidden');
        select.removeClass('hidden');
    });
});

// Responsive videos
// $(document).ready(function() {
//     $("body").fitVids();
// });




