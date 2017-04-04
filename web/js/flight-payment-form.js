var bodyEle = $("body").get(0);
if (bodyEle.addEventListener) {
    bodyEle.addEventListener("click", function () {
    }, true);
} else if (bodyEle.attachEvent) {
    document.attachEvent("onclick", function () {
        var event = window.event;
    });
}

function checkCard(cc) {
    var cardBrand = $('#select-card').val();
    var cardName = $('#select-card option:selected').text();

    if (checkCreditCard(cc, cardName)) {
        $('#card-error').addClass('hidden');
        $('#buyBtn').prop('disabled', false);
    } else {
        if (cardsObjs.hasOwnProperty(cardBrand)) {
            $('#card-error').addClass('hidden');
            $('#buyBtn').prop('disabled', false);
        } else {
            $('#card-error').text(ccErrors[ccErrorNo]).removeClass('hidden');
            $('#buyBtn').prop('disabled', true);
        }
    }
}

function selectInList(cardId, cardCode, bank, elementId) {
    var cc = $('#form_payments_card-number0').val();
    checkCard(cc);
    var cardType = cardCode;
    if (bank) {
        cardType += '_' + bank;
    }
    $('#form_payments_card-type0').val(cardType);
    $('#form_payments_card_code0').val(cardCode);
    $('#form_payments_installments0').val($('input[name=paymentOption]:checked').val());
    $('.card-list .eva-card-container').removeClass('selected-p-card');

    $('#' + elementId).addClass('selected-p-card');
}

$(document).ready(function () {

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

    $('#select-card optgroup.others').addClass('hide');

    $('.list-group-item').on('click', function (e) {
        console.log('evt in list-group-item');
        $($(this).find('input:radio')[0]).prop("checked", true);
        //update select
        var cuota = $('input[type=radio][name=paymentOption]:checked').val();
        $('#select-card optgroup').addClass('hide');
        $('#select-card optgroup.opt-' + cuota).removeClass('hide');
        $($('#select-card optgroup.opt-' + cuota + ' option')[0]).prop('selected', true);
    });

    $('#select-card').on('change', function () {
        console.log('cambio #select-card');
        var optSelected = $("option:selected", this);
        var cardId = $(optSelected).data('card-id');
        var cardCode = this.value;
        var bank = $(optSelected).data('bank-code');
        var elementId = $(optSelected).data('element');
        selectInList(cardId, cardCode, bank, elementId);
        console.log(optSelected, cardId, cardCode, bank, elementId)
    });

    $('.list-group .clickable-card').on('click', function (e) {
        e.stopPropagation();
        console.log('evt in .clickable-card');
        var cardId = $(this).data('card-id');
        var cardCode = $(this).data('card-code');
        var bank = $(this).data('bank-code');
        var parent = $(this).data('parent-id');
        if (parent === undefined) {
            parent = cardId;
        }
        selectInList(cardId, cardCode, bank, parent);
    });

    $('.clickable-bank').on('click', function (e) {
        e.stopPropagation();
    });

    $('.list-group').on('click', '.card-click-2', function (e) {
        e.stopPropagation();
        var cardId = $(this).data('card-id');
        var cardCode = $(this).data('card-code');
        var bank = $(this).data('bank-code');
        var cuotas = $(this).data('cuotas');
        var parent = $(this).data('parent-id');
        if (parent === undefined) {
            parent = cardId;
        }
        var elemt = $('#select-card .opt-' + cuotas + '[data-bank="' + bank + '"]')[0];
        console.log('#select-card .opt-' + cuotas + '[data-bank="' + bank + '"]')
        var opt = $(elemt).find('option[value=' + cardCode + ']')[0];
        $(opt).prop('selected', true);
        console.log(cardId, cardCode, bank, parent);
        console.log(elemt);
        selectInList(cardId, cardCode, bank, parent);
    });

    $('.clickable-bank').popover({
        placement: 'bottom',
        html: true,
        content: function () {
            return $(this).find('.card-content').html();
        }
    }).on('show.bs.popover', function (e) {
        e.stopPropagation();
        console.log('evt in clickable-bank');
        $(this).find('.clickable-card').each(function () {
            if ($('#select-card').find(":selected").data('card-id') == $(this).data('card-id')) {
                $(this).addClass('selected-p-card');
            }
        })
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
        if (this.value != '') {
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