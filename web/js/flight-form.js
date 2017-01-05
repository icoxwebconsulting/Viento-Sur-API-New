$(document).ready(function () {
    var fromFlight = $("#start-flight").datepicker({
        dateFormat: dateFormat,
        dayNamesMin: dayNamesMin,
        monthNames: monthNames,
        defaultDate: "+1w",
        numberOfMonths: 2,
        minDate: 0,
        onClose: function( selectedDate ) {
            var minDate = $(this).datepicker('getDate');
            var newMin = new Date(minDate.setDate(minDate.getDate() + 1));
            $( "#end-flight" ).datepicker( "option", "minDate", newMin );
        }
    });
    var toFlight = $("#end-flight").datepicker({
        dateFormat: dateFormat,
        dayNamesMin: dayNamesMin,
        monthNames: monthNames,
        defaultDate: "+1w",
        numberOfMonths: 2
    }).on("change", function () {
        fromFlight.datepicker("option", "maxDate", getDate(this));
    });


    var url = Routing.generate('flight_autocomplete');
    $('#from-flight').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#originFlight").val(suggestion.data.id);
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#originFlight").val('');
        }
    });

    $('#to-flight').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#destinationFlight").val(suggestion.data.id);
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#destinationFlight").val('');
        }
    });

    $("#childrenPassengers").change(function () {
        for (var i = 1; i < 8; i++) {
            var selector = 'field-menor-' + i;
            if (i <= this.value) {
                $('.' + selector).show();
                $('#' + selector).prop('required', true);
            } else {
                $('.' + selector).hide();
                $('#' + selector).removeProp('required');
            }
        }
        if (this.value > 0) {
            $("#menorGroup").removeClass('hidden');
        } else {
            $("#menorGroup").addClass('hidden');
        }
    });
});