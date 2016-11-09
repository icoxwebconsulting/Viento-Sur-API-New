$('#form_payment_expiration_day').hide();

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
    $('.eva-card-container:first').addClass('selected-p-card');
    var cardId = $('.eva-card-container:first').attr('data-card-id');
    $('#form_payment_bank_code').val(cardId);
    var splitCard = cardId.split("-");
    $('#form_payment_card_code').val(splitCard[1]);
    $('#form_payment_card_type').val(splitCard[3]);
    $('#card-selected').val(cardsObject[splitCard[1]]);

    $('.eva-card-container').on('click', function () {
        $('.card-list .eva-card-container').removeClass('selected-p-card');
        var cardId = $(this).attr('data-card-id');
        $('#form_payment_bank_code').val(cardId);
        $(this).addClass('selected-p-card');
        var splitCard = cardId.split("-");
        $('#form_payment_card_code').val(splitCard[1]);
        $('#form_payment_card_type').val(splitCard[3]);
        $('#card-selected').val(cardsObject[splitCard[1]]);
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