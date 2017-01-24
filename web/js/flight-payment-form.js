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
        //var bank = $(this).data('bank-id');
        $('#card-selected').val(cardsObject[cardCode]);
        $('#form_payments_card-type0').val(cardCode);
        $('#form_payments_card_code0, #form_payments_card-token0').each(function () {
          this.value = cardId;
        });
        $('#form_payments_installments0').val($('input[name=paymentOption]:checked').val());
        $('.card-list .eva-card-container').removeClass('selected-p-card');
    });

    $('.clickable-bank').popover({
        placement: 'bottom',
        html: true,
        content: function () {
            return $(this).find('.card-content').html();
        }
    }).on('show.bs.popover', function () {
//            $(this).find('.clickable-card').each(function () {
//                if ($('#form_payment_bank_code').val() == $(this).data('card-id')) {
//                    $(this).addClass('selected-p-card');
//                }
//            })
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
});