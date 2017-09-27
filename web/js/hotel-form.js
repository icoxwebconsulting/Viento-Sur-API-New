$(document).ready(function () {
    var cardsObject = {
        'VI': 'Visa',
        'VIS': 'Visa Signature',
        'CA': 'MasterCard',
        'AX': 'American Express',
        'DC': 'Diners Club',
        'CL': 'Cabal',
        'TN': 'Tarjeta Naranja',
        'NV': 'Tarjeta Nevada'
    };

    $('#form_payment_expiration_day').hide();

    $('.eva-card-container:first').addClass('selected-p-card');
    var cardId = $('.eva-card-container:first').attr('data-card-id');
    $('#form_payment_bank_code').val(cardId);
    var splitCard = cardId.split("-");
    $('#form_payment_card_code').val(splitCard[1]);
    $('#form_payment_card_type').val(splitCard[3]);
    $('#card-selected').val(cardsObject[splitCard[1]]);

    $('.list-group').on('click', '.clickable-card', function () {
        var cardId = $(this).data('card-id');
        var cardIdSelect = $(this).data('card-id-select');
        var idOption = '';
        var cuote = $(this).data('data-cuote');
        console.log('cuote ', cuote);
        var bank = $(this).data('bank-id');
        var splitCard = cardId.split("-");

        if(splitCard[2] === "*"){
            idOption = cuote+'-'+cardIdSelect;
            console.log('tiene *');
        }else{
            idOption = cuote+'-'+cardId;
            console.log('not tiene *');
        }

        console.log('idoption', idOption)
        if (cuote == 1){
            if($('.list-group-item').length === 1){
                $('#select-card2 option[id="'+idOption+'"]').attr("selected", "selected");
            }else{
                $('#select-card3 option[id="'+idOption+'"]').attr("selected", "selected");
            }
            console.log('count list-group-item',$('.list-group-item').length);
        }else{
            // $("#select-card3").find('option').removeAttr("selected");
            $('#select-card3 option[id='+idOption+']').attr("selected", "selected");
        }
        $(cuote+'-'+cardId).attr('selected', 'selected');

        $('#form_payment_bank_code').val(cardId);
        $('#form_payment_card_code').val(splitCard[1]);
        $('#form_payment_card_type').val(splitCard[3]);
        $('#card-selected').val(cardsObject[splitCard[1]]);
        $('.card-list .eva-card-container').removeClass('selected-p-card');

        if (bank) {
            var parentId = $(this).data('parent-id');
            $('#' + parentId).addClass('selected-p-card');
            $.ajax({
                url: Routing.generate('hotel_card_detail'),
                type: 'GET',
                data: {
                    card: cardId
                },
                dataType: 'json'
            }).done(function (data) {
                if (data.hasOwnProperty('description')) {
                    $('#card-selected').val(data.description);
                }
            }).fail(function (e) {
                console.log(e)
            }).always(function () {
            });
        } else {
            $(this).addClass('selected-p-card');
        }
    });

    $('.clickable-bank').popover({
        placement: 'bottom',
        html: true,
        content: function () {
            return $(this).find('.card-content').html();
        }
    }).on('show.bs.popover', function () {
        $(this).find('.clickable-card').each(function () {
            if ($('#form_payment_bank_code').val() == $(this).data('card-id')) {
                $(this).addClass('selected-p-card');
            }
        })
    });

    var isVisible = false;
    var clickedAway = false;

    $(document).on('click', function (e) {
        if (isVisible && clickedAway) {
            $('.clickable-group-bank').popover('hide');
            isVisible = clickedAway = false
        } else {
            clickedAway = true
        }
    });

    $('.clickable-group-bank').popover({
        placement: 'left',
        html: true,
        content: function () {
            return $('.' + this.id).html();
        },
        trigger: 'manual',
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content" style="height: 400px; overflow-y: scroll; overflow-x: hidden;"></div></div>'
    }).on('show.bs.popover', function () {
        $('.' + this.id + ' .clickable-card').each(function () {
            if ($('#form_payment_bank_code').val() == $(this).data('card-id')) {
                $(this).addClass('selected-p-card');
            }
        })
    }).on('click', function (e) {
        $(this).popover('show');
        clickedAway = false;
        isVisible = true;
        e.preventDefault()
    });

    ////
    var ac = $('#autocomplete-city').autocomplete({
        serviceUrl: Routing.generate('city_autocomplete'),
        onSelect: function (suggestion) {
            $("#form_payment_billing_addresscity_id").val(suggestion.data);
            $("#form_payment_billing_addresscity_id").data('name', suggestion.value);
        },
        onInvalidateSelection: function () {
            $("#form_payment_billing_addresscity_id").val('');
        },
        minChars: 3
    });

    $("#autocomplete-state").autocomplete({
        serviceUrl: Routing.generate('state_autocomplete'),
        onSelect: function (suggestion) {
            $("#form_payment_billing_addressstate_id").val(suggestion.data);
            $("#form_payment_billing_addressstate_id").data('name', suggestion.value);
            $('#autocomplete-city').removeProp('readonly').prop('placeholder', 'Buscar ciudad');
            $('#autocomplete-city').autocomplete().setOptions({
                serviceUrl: Routing.generate('city_autocomplete') + '/' + suggestion.data
            });
        },
        onInvalidateSelection: function () {
            $("#form_payment_billing_addressstate_id, #form_payment_billing_addresscity_id").val('');
            $('#autocomplete-city').prop('readonly');
            $('#autocomplete-city').prop('placeholder', 'Rellene la provincia');
        },
        minChars: 3
    });
    $('#autocomplete-state, #autocomplete-city').on("click", function () {
        $(this).select();
    });

    $('.list-group-item').on('click', function () {
        $($(this).find('input:radio')[0]).prop("checked", true);
    })

    $('#form_contact_info_email-1').on('blur', function () {
        if($('#form_contact_email').val() != $('#form_contact_info_email-1').val()) {
            $('#errorEmail').removeClass('hide');
        } else {
            $('#errorEmail').addClass('hide');
        }
    });
    
    $('.booking-form').on('submit', function (event) {
        if($('#form_contact_email').val() != $('#form_contact_info_email-1').val()) {
            event.preventDefault();
            $('#errorEmail').removeClass('hide');
        }
    })
    // var limit =$('.passengers-panel').height() + $('.pay-methods-panel').height() + 100;
    // $(window).scroll(function() {
    //     var scrollVal = $(this).scrollTop();
    //     if(limit > 400){
    //         if (scrollVal > limit) {
    //             $('.col-md-3.col-md-pull-9').css({'top': limit});
    //             console.log('limit',limit)
    //         }else{
    //
    //             $('.col-md-3.col-md-pull-9').stop();
                .css({'top': 0});
            // }
        // }
    // });
    $(".col-xs-12.col-sm-4.col-sm-pull-8").jScroll({speed : "slow"});
});
// var $elt = $('.col-md-3.col-md-pull-9');
//
// $elt.unbind('jScroll', jScroll);

function selectCuote(id){
    console.log('selectCuote',this);
    $('#cuote').text($('#span-text-'+id).text())
    $('#cuoteDesktop').text($('#span-text-'+id).text())
}