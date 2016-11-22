$(document).ready(function () {
    var cardsObject = {
        'VI': 'Visa',
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
        var bank = $(this).data('bank-id');
        var splitCard = cardId.split("-");
        $('#form_payment_bank_code').val(cardId);
        $('#form_payment_card_code').val(splitCard[1]);
        $('#form_payment_card_type').val(splitCard[3]);
        $('#card-selected').val(cardsObject[splitCard[1]]);
        $('.card-list .eva-card-container').removeClass('selected-p-card');

        if (bank) {
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
});