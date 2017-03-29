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

    function checkCard(cc) {
        var cardBrand = $('#card-selected').val();

        if (checkCreditCard(cc, cardBrand)) {
            $('#card-error').addClass('hidden');
            $('#buyBtn').prop('disabled', false);
        } else {
            var cardArray = ['Visa', 'MasterCard', 'American Express', 'Diners Club', 'Cabal'];
            if (cardArray.indexOf(cardBrand) != -1) {
                $('#card-error').text(ccErrors[ccErrorNo]).removeClass('hidden');
                $('#buyBtn').prop('disabled', true);
            }
        }
    }

    $(".birth-input").datepicker({
        dayNamesMin: dayNamesMin,
        monthNames: monthNames,
        monthNamesShort: monthNamesShort,
        yearRange: "-115:+0",
        dateFormat: dateFormat,
        changeMonth: true,
        changeYear: true,
        minDate: new Date(1900, 1 - 1, 1)
    });

    $('#form_payments_card-expiration0_day').hide();

    $('.eva-card-container:first').addClass('selected-p-card');
    var cardId = $('.eva-card-container:first').attr('data-card-id');
    console.log(cardId);
    $('#card-selected').val(cardsObject[cardId]);

    $('.list-group').on('click', '.clickable-card', function () {
        var cardId = $(this).data('card-id');
        var cardCode = $(this).data('card-code');
        var bank = $(this).data('bank-code');
        var cc = $('#form_payments_card-number0').val();
        if (cc != '') {
            $('#card-selected').val(cardsObject[cardCode]);
            checkCard(cc);
        } else {
            $('#card-selected').val(cardsObject[cardCode]);
        }
        $('#form_payments_card-type0').val(cardCode);
        $('#form_payments_card_code0').val(cardId);
        $('#form_payments_installments0').val($('input[name=paymentOption]:checked').val());
        $('.card-list .eva-card-container').removeClass('selected-p-card');

        if (bank) {
            var parentId = $(this).data('parent-id');
            console.log(parentId);
            $('#' + parentId).addClass('selected-p-card');
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
               if ($('#form_payments_card_code0').val() == $(this).data('card-id')) {
                   $(this).addClass('selected-p-card');
               }
           })
    });

    $('.list-group-item').on('click', function () {
        $($(this).find('input:radio')[0]).prop("checked", true);
    });

    $("#form_contact_info_phones-country_code0").on('keydown', function (e) {
        e.preventDefault();
        return false;
    }).intlTelInput({
        autoHideDialCode: false,
        nationalMode: false,
        initialCountry: 'AR'
    });

    $('#form_payments_card-number0').payment('formatCardNumber');
    $('#form_payments_card-security_code0').payment('formatCardCVC');

    var ac = $('#autocomplete-city').autocomplete({
        serviceUrl: Routing.generate('city_autocomplete_argentina'),
        onSelect: function (suggestion) {
            $("#form_payments_invoice-city_id0").val(suggestion.data);
            $("#form_payments_invoice-city_id0").data('name', suggestion.value);
        },
        onInvalidateSelection: function () {
            $("#form_payments_invoice-city_id0").val('');
        },
        minChars: 3
    });

    $('#select-state').on('change', function () {
        if(this.value != '') {
            $("#form_payments_invoice-state0").val(this.value);
            $('#autocomplete-city').removeProp('readonly').prop('placeholder', 'Buscar ciudad');
            $('#autocomplete-city').autocomplete().setOptions({
                serviceUrl: Routing.generate('city_autocomplete_argentina') + '/' + this.value
            });
        } else {
            $('#autocomplete-city').prop('readonly', true);
            $('#autocomplete-city').prop('placeholder', 'Buscar ciudad');
        }
    });

    $('#autocomplete-state, #autocomplete-city').on("click", function () {
        $(this).select();
    });

    $('#form_payments_card-number0').on('blur', function () {
        checkCard(this.value);
    });
});