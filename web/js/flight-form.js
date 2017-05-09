$(document).ready(function () {
    var fromFlight = $("#start-flight").datepicker({
        dateFormat: dateFormat,
        dayNamesMin: dayNamesMin,
        monthNames: monthNames,
        defaultDate: "+1w",
        numberOfMonths: 2,
        minDate: 0,
        onClose: function (selectedDate) {
            try {
                var minDate = $(this).datepicker('getDate');
                var newMin = new Date(minDate.setDate(minDate.getDate() + 1));
                $("#end-flight").datepicker("option", "minDate", newMin);
                $('#start-flight0').val(fromFlight.val());
            } catch (e) {
                console.error(e);
            }
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
    var startFlight0 = $('#start-flight0').datepicker({
        dateFormat: dateFormat,
        dayNamesMin: dayNamesMin,
        monthNames: monthNames,
        defaultDate: "+1w",
        numberOfMonths: 2,
    });
    var startFlight1 = $('#start-flight1').datepicker({
        dateFormat: dateFormat,
        dayNamesMin: dayNamesMin,
        monthNames: monthNames,
        defaultDate: "+1w",
        numberOfMonths: 2,
    }).on("change", function () {
        startFlight0.datepicker("option", "maxDate", getDate(this));
    });

    var url = Routing.generate('flight_autocomplete');
    $('#from-flight').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#originFlight").val(suggestion.data.id);
            $('#multidestination-from-flight0').val($("#from-flight").val());
            $("#multidestination-originFlight0").val(suggestion.data.id);
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#originFlight").val('');
        }
    });
    $('#multidestination-from-flight0').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-originFlight0").val(suggestion.data.id);
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-originFlight0").val('');
        }
    });
    $('#multidestination-from-flight1').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-originFlight1").val(suggestion.data.id);
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-originFlight1").val('');
        }
    });
    $('#multidestination-to-flight0').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-destinationFlight0").val(suggestion.data.id);
            $("#multidestination-from-flight1").val($("#multidestination-to-flight0").val());
            $("#multidestination-originFlight1").val($("#multidestination-destinationFlight0").val());
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-destinationFlight0").val('');
        }
    });
    $('#multidestination-to-flight1').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-destinationFlight1").val(suggestion.data.id);
            var nextSelector = document.getElementById("multidestination-from-flight2");
            if (nextSelector){
                $("#multidestination-from-flight2").val($("#multidestination-to-flight1").val());
                $("#multidestination-originFlight2").val($("#multidestination-destinationFlight1").val());
            }
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-destinationFlight1").val('');
        }
    });

    $('#to-flight').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#destinationFlight").val(suggestion.data.id);
            $('#multidestination-to-flight0').val($("#to-flight").val());
            $('#multidestination-from-flight1').val($("#to-flight").val());
            $("#multidestination-destinationFlight0").val(suggestion.data.id);
            $("#multidestination-originFlight1").val(suggestion.data.id);
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#destinationFlight").val('');
        }
    });
    $('#search-multidestination').click(function () {
        $('#multipledestination').val(true);
    });
    $('#multiple-destinations').click(function(){
        $('.wrapper').show();
        $('#add-stretch').show();
        $('#remove-stretch').show();
        $('#multipledestination').val(true);
        console.log($('#multipledestination').val());
        $('#div-end-flight').hide();
        $('#end-flight').removeAttr('required');
        $('#multidestination-from-flight0').attr('required', 'true');
        $('#multidestination-to-flight0').attr('required', 'true');
        $('#start-flight0').attr('required', 'true');
        $('#multidestination-from-flight1').attr('required', 'true');
        $('#multidestination-to-flight1').attr('required', 'true');
        $('#start-flight1').attr('required', 'true');
        $('#tab-2').css({'overflow': 'auto', 'height': '313px'});
    });
    $('#multipledestination').val(false);

    $('#add-stretch').click(function(){
        var lenght = $('.multiple-destinations-label').length;
        var count = $('.multiple-destinations-label').length;
        lenght++;
        var labelFrom = $('#label-from').text();
        var labelTo = $('#label-to').text();
        var labelStart = $('#label-start').text();
        if (lenght < 7){
            $('.col-md-12.stretch').before('<div class="wrapper col-md-12">'+
                                            '<label class="multiple-destinations-label">Tramo '+lenght+'</label>'+
                                            '<div class="multiple-destinations-wrapper col-md-6 ">'+
                                                '<div class="form-group form-group-sm form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>'+
                                                    '<label>'+labelFrom+'</label>'+
                                                    '<input class="form-control" id="multidestination-from-flight'+count+'" name="multidestination['+count+'][fromFlight]" data-provide="typeahead" required type="text" />'+
                                                    '<input id="multidestination-originFlight'+count+'" name="multidestination['+count+'][originFlight]" type="hidden" value="" />'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="multiple-destinations-wrapper col-md-6 ">'+
                                                '<div class="form-group form-group-sm form-group-icon-left"><i class="fa fa-map-marker input-icon"></i>'+
                                                    '<label>'+labelTo+'</label>'+
                                                    '<input class="form-control" id="multidestination-to-flight'+count+'" name="multidestination['+count+'][toFlight]" data-provide="typeahead" required type="text" />'+
                                                    '<input id="multidestination-destinationFlight'+count+'" name="multidestination['+count+'][destinationFlight]" type="hidden" value="" />'+
                                                '</div>'+
                                            '</div>'+
                                            '<div class="multiple-destinations-wrapper col-md-12">'+
                                                '<div class="col-md-6">'+
                                                    '<div class="form-group form-group-sm form-group-icon-left"><i class="fa fa-calendar input-icon input-icon-highlight"></i>'+
                                                        '<label>'+labelStart+'</label>'+
                                                        '<input class="form-control required departure" id="start-flight'+count+'" name="multidestination['+count+'][start]" type="text" required="" placeholder="dd/mm/yyyy"/>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>');
            var aux = count - 1;
            var dateSelector = '#start-flight'+aux;
            console.log('#start-flight'+aux);
            $('#start-flight'+count).datepicker({
                dateFormat: dateFormat,
                dayNamesMin: dayNamesMin,
                monthNames: monthNames,
                defaultDate: "+1w",
                numberOfMonths: 2
            }).on("change", function () {
                $(dateSelector).datepicker("option", "maxDate", getDate(this));
            });
            $('#multidestination-from-flight'+count).autocomplete({
                serviceUrl: url,
                onSelect: function (suggestion) {
                    var aux = count -1;
                    var selector = '#multidestination-originFlight'+aux;
                    $(selector).val(suggestion.data.id);
                },
                minChars: 3,
                groupBy: 'category',
                onInvalidateSelection: function () {
                    console.log("invalidado");
                    $("#multidestination-originFlight"+count).val('');
                }
            }); $('#multidestination-to-flight'+count).autocomplete({
                serviceUrl: url,
                onSelect: function (suggestion) {
                    var aux = count -1;
                    var next = count + 1;
                    console.log('count', count);
                    console.log('aux', aux);
                    console.log('nextSelector', next)
                    var selector = '#multidestination-destinationFlight'+aux;
                    $(selector).val(suggestion.data.id);
                    var nextSelector = document.getElementById("multidestination-from-flight"+count);
                    if (nextSelector){
                        console.log('nextselector',nextSelector)
                        $("#multidestination-from-flight"+count).val($("#multidestination-to-flight"+aux).val());
                        $("#multidestination-originFlight"+count).val($("#multidestination-destinationFlight"+aux).val());
                    }
                },
                minChars: 3,
                groupBy: 'category',
                onInvalidateSelection: function () {
                    console.log("invalidado");
                    $("#multidestination-destinationFlight"+count).val('');
                }
            });
            count++;
        }
    });
    $('#remove-stretch').click(function () {
        var lenght = $('.wrapper').length;
        if (lenght > 2){
            $('.wrapper').last().remove();
        }else{
            $('.wrapper').hide();
            $('#remove-stretch').hide();
            $('#add-stretch').hide();
            $('#multipledestination').val(false);
            console.log($('#multipledestination').val());
            $('#div-end-flight').show();
            $('#end-flight').attr('required', 'true');
            $('#multidestination-from-flight0').removeAttr('required');
            $('#multidestination-to-flight0').removeAttr('required');
            $('#multidestination-from-flight1').removeAttr('required');
            $('#multidestination-to-flight1').removeAttr('required');
            $('#start-flight0').removeAttr('required');
            $('#start-flight1').removeAttr('required');
            $('#tab-2').css({'overflow': '', 'height': ''});
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

    if($('#only_out').is(':checked')){
        $("#end-flight").removeAttr('required').hide();
        $("#dummy-end-flight").removeClass('hidden');
    }

    $('#only_out').change(function() {
        if($(this).is(':checked')) {
            $("#end-flight").removeAttr('required').hide();
            $("#dummy-end-flight").removeClass('hidden');
        } else {
            $("#dummy-end-flight").addClass('hidden');
            $("#end-flight").show().attr("required");
        }
    });
});