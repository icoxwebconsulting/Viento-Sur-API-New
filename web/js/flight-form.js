$(document).ready(function () {
    $('#form_payments_deferred_months0').val(0);
    console.log('form',$('#form_payments_deferred_months0').val());
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
        onClose: function (selectedDate) {
            try {
                $('#start-flight').val(startFlight0.val());
            } catch (e) {
                console.error(e);
            }
        }
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
    var startFlight2 = $('#start-flight2').datepicker({
        dateFormat: dateFormat,
        dayNamesMin: dayNamesMin,
        monthNames: monthNames,
        defaultDate: "+1w",
        numberOfMonths: 2,
    }).on("change", function () {
        startFlight1.datepicker("option", "maxDate", getDate(this));
    });
    var startFlight3 = $('#start-flight3').datepicker({
        dateFormat: dateFormat,
        dayNamesMin: dayNamesMin,
        monthNames: monthNames,
        defaultDate: "+1w",
        numberOfMonths: 2,
    }).on("change", function () {
        startFlight2.datepicker("option", "maxDate", getDate(this));
    });
    var startFlight4 = $('#start-flight4').datepicker({
        dateFormat: dateFormat,
        dayNamesMin: dayNamesMin,
        monthNames: monthNames,
        defaultDate: "+1w",
        numberOfMonths: 2,
    }).on("change", function () {
        startFlight3.datepicker("option", "maxDate", getDate(this));
    });
    var startFlight5 = $('#start-flight5').datepicker({
        dateFormat: dateFormat,
        dayNamesMin: dayNamesMin,
        monthNames: monthNames,
        defaultDate: "+1w",
        numberOfMonths: 2,
    }).on("change", function () {
        startFlight4.datepicker("option", "maxDate", getDate(this));
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
            $('#from-flight').val($("#multidestination-from-flight0").val());
            $("#originFlight").val(suggestion.data.id);
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
    $('#multidestination-from-flight2').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-originFlight2").val(suggestion.data.id);
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-originFlight2").val('');
        }
    });
    $('#multidestination-from-flight3').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-originFlight3").val(suggestion.data.id);
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-originFlight3").val('');
        }
    });
    $('#multidestination-from-flight4').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-originFlight4").val(suggestion.data.id);
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-originFlight4").val('');
        }
    });
    $('#multidestination-from-flight5').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-originFlight5").val(suggestion.data.id);
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-originFlight5").val('');
        }
    });
    $('#multidestination-to-flight0').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-destinationFlight0").val(suggestion.data.id);
            $("#multidestination-from-flight1").val($("#multidestination-to-flight0").val());
            $("#multidestination-originFlight1").val($("#multidestination-destinationFlight0").val());
            $('#to-flight').val($("#multidestination-to-flight0").val());
            $("#destinationFlight").val(suggestion.data.id);
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
    $('#multidestination-to-flight2').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-destinationFlight2").val(suggestion.data.id);
            var nextSelector = document.getElementById("multidestination-from-flight3");
            if (nextSelector){
                $("#multidestination-from-flight3").val($("#multidestination-to-flight2").val());
                $("#multidestination-originFlight3").val($("#multidestination-destinationFlight2").val());
            }
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-destinationFlight2").val('');
        }
    });
    $('#multidestination-to-flight3').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-destinationFlight3").val(suggestion.data.id);
            var nextSelector = document.getElementById("multidestination-from-flight4");
            if (nextSelector){
                $("#multidestination-from-flight4").val($("#multidestination-to-flight3").val());
                $("#multidestination-originFlight4").val($("#multidestination-destinationFlight3").val());
            }
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-destinationFlight3").val('');
        }
    });
    $('#multidestination-to-flight4').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-destinationFlight4").val(suggestion.data.id);
            var nextSelector = document.getElementById("multidestination-from-flight5");
            if (nextSelector){
                $("#multidestination-from-flight5").val($("#multidestination-to-flight4").val());
                $("#multidestination-originFlight5").val($("#multidestination-destinationFlight4").val());
            }
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-destinationFlight4").val('');
        }
    });
    $('#multidestination-to-flight5').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#multidestination-destinationFlight5").val(suggestion.data.id);
            var nextSelector = document.getElementById("multidestination-from-flight2");
            if (nextSelector){
                $("#multidestination-from-flight6").val($("#multidestination-to-flight5").val());
                $("#multidestination-originFlight6").val($("#multidestination-destinationFlight5").val());
            }
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            console.log("invalidado");
            $("#multidestination-destinationFlight5").val('');
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
        $('.round-trip-wrapper').hide();
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
    $('#multidestination-lateral').click(function () {
        $('.multi-flight').show();
        $('#remove-stretch-lateral').show();
        $('#add-stretch-lateral').show();
        $('.normal-flight').hide();
        $('#multidestination-lateral').hide();
        // $('#multipledestination').attr('value',true);
        $('#multipledestination-normal').remove();

        var labelStretch = $('#label-stretch').text();
        var labelFrom = $('#label-from').text();
        var labelTo = $('#label-to').text();
        var labelStart = $('#label-start').text();
        var placeHolderFrom = $('#from-flight').attr('placeholder');
        var placeHolderTo = $('#to-flight').attr('placeholder');
        var lenght = $('.wrapper-lateral').length;
        if (lenght == 0){
            console.log('mostrar menu multiple');
            $('.multidestination-lateral-btn').after('<div class="multi-flight">'+
                                                        '<div class="wrapper-lateral">'+
                                                            '<h2>'+labelStretch+' 1</h2>'+
                                                            '<div class="form-group form-group-icon-left form-icon-lg">'+
                                                                '<i class="fa fa-map-marker input-icon input-icon-hightlight"></i>'+
                                                                '<label class="mfont">'+labelFrom+'</label>'+
                                                                '<input class="form-control" id="multidestination-from-flight0" name="multidestination[0][fromFlight]" type="text" data-provide="typeahead" placeholder="'+placeHolderFrom+'" />'+
                                                                '<input id="multidestination-originFlight0" name="multidestination[0][originFlight]" type="hidden" />'+
                                                            '</div>'+
                                                            '<div class="form-group form-group-icon-left form-icon-lg">'+
                                                                '<i class="fa fa-map-marker input-icon input-icon-hightlight"></i>'+
                                                                '<label class="mfont">'+labelTo+'</label>'+
                                                                '<input id="multidestination-to-flight0" name="multidestination[0][toFlight]" type="text" class="form-control" data-provide="typeahead" placeholder="'+placeHolderTo+'" />'+
                                                                '<input id="multidestination-destinationFlight0" name="multidestination[0][destinationFlight]" type="hidden" />'+
                                                            '</div>'+
                                                            '<div>'+
                                                                '<div class="form-group form-group-icon-left form-icon-lg"><i class="fa fa-calendar input-icon input-icon-hightlight"></i>'+
                                                                    '<label class="sfont">'+labelStart+'</label>'+
                                                                    '<input id="start-flight0" class="form-control required" name="multidestination[0][start]" type="text" required="" placeholder="dd/mm/yyyy" />'+
                                                                '</div>'+
                                                            '</div>'+
                                                        '</div>'+
                                                        '<div class="wrapper-lateral">'+
                                                            '<h2>'+labelStretch+' 2</h2>'+
                                                            '<div class="form-group form-group-icon-left form-icon-lg">'+
                                                                '<i class="fa fa-map-marker input-icon input-icon-hightlight"></i>'+
                                                                '<label class="mfont">'+labelFrom+'</label>'+
                                                                '<input class="form-control" id="multidestination-from-flight1" name="multidestination[1][fromFlight]" type="text" data-provide="typeahead" placeholder="'+placeHolderFrom+'" />'+
                                                                '<input id="multidestination-originFlight1" name="multidestination[1][originFlight]" type="hidden" />'+
                                                            '</div>'+
                                                            '<div class="form-group form-group-icon-left form-icon-lg">'+
                                                                '<i class="fa fa-map-marker input-icon input-icon-hightlight"></i>'+
                                                                '<label class="mfont">'+labelTo+'</label>'+
                                                                '<input id="multidestination-to-flight1" name="multidestination[1][toFlight]" type="text" class="form-control" data-provide="typeahead" placeholder="'+placeHolderTo+'" />'+
                                                                '<input id="multidestination-destinationFlight1" name="multidestination[1][destinationFlight]" type="hidden" />'+
                                                            '</div>'+
                                                            '<div>'+
                                                                '<div class="form-group form-group-icon-left form-icon-lg"><i class="fa fa-calendar input-icon input-icon-hightlight"></i>'+
                                                                    '<label class="sfont">'+labelStart+'</label>'+
                                                                    '<input id="start-flight1" class="form-control required" name="multidestination[1][start]" type="text" required="" placeholder="dd/mm/yyyy" />'+
                                                                '</div>'+
                                                            '</div>'+
                                                        '</div>'+
                                                    '</div>');
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
        }
        $('.multi-flight').append('<input type="text"  id="multipledestination-multiple" name="multipledestination" value="true" style="display: none">');
    });
    $('#add-stretch-lateral').click(function () {
        $('.normal-flight').hide();
        var lenght = $('.wrapper-lateral').length;
        var count = $('.wrapper-lateral').length;

        var labelStretch = $('#label-stretch').text();
        var labelFrom = $('#label-from').text();
        var labelTo = $('#label-to').text();
        var labelStart = $('#label-start').text();
        var placeHolderFrom = $('#multidestination-from-flight0').attr('placeholder');
        var placeHolderTo = $('#multidestination-to-flight0').attr('placeholder');
        lenght++;
        console.log(lenght);
        if (lenght < 7){
            $('.wrapper-lateral').last().after('<div class="wrapper-lateral">'+
                    '<h2>'+labelStretch+' '+lenght+'</h2>'+
                '<div class="form-group form-group-icon-left form-icon-lg">'+
                    '<i class="fa fa-map-marker input-icon input-icon-hightlight"></i>'+
                    '<label class="mfont">'+labelFrom+'</label>'+
                    '<input class="form-control" id="multidestination-from-flight'+count+'" name="multidestination['+count+'][fromFlight]" type="text" data-provide="typeahead" placeholder="'+placeHolderFrom+'" />'+
                    '<input id="multidestination-originFlight'+count+'" name="multidestination['+count+'][originFlight]" type="hidden" />'+
                '</div>'+
                '<div class="form-group form-group-icon-left form-icon-lg">'+
                    '<i class="fa fa-map-marker input-icon input-icon-hightlight"></i>'+
                    '<label class="mfont">'+labelTo+'</label>'+
                    '<input id="multidestination-to-flight'+count+'" name="multidestination['+count+'][toFlight]" type="text" class="form-control" data-provide="typeahead" placeholder="'+placeHolderTo+'" />'+
                    '<input id="multidestination-destinationFlight'+count+'" name="multidestination['+count+'][destinationFlight]" type="hidden" />'+
                '</div>'+
                '<div>'+
                    '<div class="form-group form-group-icon-left form-icon-lg"><i class="fa fa-calendar input-icon input-icon-hightlight"></i>'+
                        '<label class="sfont">'+labelStart+'</label>'+
                        '<input id="start-flight'+count+'" class="form-control required" name="multidestination['+count+'][start]" type="text" required="" placeholder="dd/mm/yyyy" />'+
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
            if (lenght == 6){
                $('#add-stretch-lateral').hide();
            }
        }
    });
    $('#remove-stretch-lateral').click(function () {
        var lenght = $('.wrapper-lateral').length;
        console.log(lenght);
        if (lenght > 2){
            $('.wrapper-lateral').last().remove();
            $('#add-stretch-lateral').show();
        }else{
            $('#remove-stretch-lateral').hide();
            $('#add-stretch-lateral').hide();
            $('.normal-flight').show();
            $('.multi-flight').hide();
            $('#multidestination-lateral').show();
            // $('#multipledestination').attr('value',false);
            $('#multipledestination-multiple').remove();
            $('.normal-flight').append('<input type="text"  id="multipledestination-normal" name="multipledestination" value="false" style="display: none">');
            console.log($('#multipledestination').val())
            var fromFlight = $("#start-flight-test").datepicker({
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
                        $("#end-flight-test").datepicker("option", "minDate", newMin);
                    } catch (e) {
                        console.error(e);
                    }
                }
            });
            var toFlight = $("#end-flight-test").datepicker({
                dateFormat: dateFormat,
                dayNamesMin: dayNamesMin,
                monthNames: monthNames,
                defaultDate: "+1w",
                numberOfMonths: 2
            }).on("change", function () {
                fromFlight.datepicker("option", "maxDate", getDate(this));
            });
            $('.test-to-flight').autocomplete({
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
        }
    });
    $('#add-stretch').click(function(){
        var lenght = $('.multiple-destinations-label').length;
        var count = $('.multiple-destinations-label').length;
        lenght++;
        var labelStr = $('#label-stretch').text();
        var labelStretch = labelStr.substring(0, labelStr.length -1);
        var labelFrom = $('#label-from').text();
        var labelTo = $('#label-to').text();
        var labelStart = $('#label-start').text();
        var toDate = $('#toDate').text();
        var placeHolderFrom = $('#multidestination-from-flight0').attr('placeholder');
        var placeHolderTo = $('#multidestination-to-flight0').attr('placeholder');
        if (lenght < 7){
            $('.col-md-12.stretch')
                .before('<div class="row wrapper">'+
                            '<div class="col-xs-12">'+
                                '<div class="col-xs-5">'+
                                    '<p class="multiple-destinations-label">'+labelStretch+' '+lenght+'</p>'+
                                    '<div class="form-group-icon-left marginBottom10">'+
                                        '<i class="fa fa-map-marker input-icon"></i>'+
                                        '<input class="city-input form-control" id="multidestination-from-flight'+count+'" name="multidestination['+count+'][fromFlight]" data-provide="typeahead" required type="text" placeholder="'+placeHolderFrom+'" />'+
                                        '<input id="multidestination-originFlight'+count+'" name="multidestination['+count+'][originFlight]" type="hidden" value="" />'+
                                    '</div>'+
                                    '<div class="form-group-icon-left marginBottom10">'+
                                        '<i class="fa fa-map-marker input-icon"></i>'+
                                        '<input class="city-input form-control" id="multidestination-to-flight'+count+'" name="multidestination['+count+'][toFlight]" data-provide="typeahead" required type="text" placeholder="'+placeHolderTo+'" />'+
                                        '<input id="multidestination-destinationFlight'+count+'" name="multidestination['+count+'][destinationFlight]" type="hidden" value="" />'+
                                    '</div>'+
                                '</div>'+
                                '<div class="col-xs-2 paddingSpecial multiple-destinations-wrapper">'+
                                    '<p>'+toDate+'</p>'+
                                    '<div class="input-group date marginBottom10 col-xs-12" id="datetimepicker3">'+
                                    '<i class="glyphicon glyphicon-calendar"></i>'+
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
            $('.round-trip-wrapper').show();
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
            $('#round_trip').prop('checked', true);
            $('.typeTrip').show();
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

    /*$("#adultsPassengers").change(function () {
        alert($("#adultsPassengers").val());
    })*/

    $("#total-passengers").text($("#adultsPassengers").val());
    $("#total-adults").text($("#adultsPassengers").val());

    $('#adultsPassengers').on('change', function() {
        $("#total-passengers").text($("#adultsPassengers").val());
        console.log(typeof($("#adultsPassengers").val()));
    })

    $('#childrenPassengers').on('change', function() {
        var adultsPas = parseInt($("#adultsPassengers").val());
        var childrenPas = parseInt($("#childrenPassengers").val());

        var totalPas = adultsPas + childrenPas;

        $("#total-adults").text(adultsPas);
        $("#total-children").text(childrenPas);
        $("#total-passengers").text(totalPas);
    })


    $("#typeTrip").change(function () {
        var flightType =  $('input[name=flightType]:checked', '#typeTrip').val();
        if(flightType == 'only_out'){
            $("#end-flight").removeAttr('required').hide();
            $("#dummy-end-flight").removeClass('hidden');
            $('#only_out').val(true);
            $('#multipledestination').val(false);
            $('#only_out').is(':checked');

            console.log($('#multipledestination').val());


        }else if(flightType == 'round_trip') {
            $("#dummy-end-flight").addClass('hidden');
            $("#end-flight").show().attr("required");
            // $('.round-trip-wrapper').show();
            $('#only_out').val(false);
            $('#multipledestination').val(false);
            console.log($('#multipledestination').val());

        }else if(flightType == 'multiple_destination'){
            $('.wrapper').show();
            $('.round-trip-wrapper').hide();
            $('.typeTrip').hide();
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
        }
    });

    /*if(flightType == 'only_out'){
        $("#end-flight").removeAttr('required').hide();
        $("#dummy-end-flight").removeClass('hidden');
    }*/

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

    // var limit =$('.passengers-panel').height() + $('.pay-methods-panel').height() + 100;
    // $(window).scroll(function() {
    //     var scrollVal = $(this).scrollTop();
    //     if(limit > 400){
    //         if ( scrollVal > limit ) {
    //             $('.content-sidebar').css({
    //                 'top': limit,
    //                 'position': 'relative'
    //             });
    //             $('.flight-detail').css({
    //                 'display': 'none'
    //             });
    //         }else{
    //             $('.content-sidebar').css({
    //                 'top': 0,
    //                 'position': 'initial'
    //             });
    //             $('.flight-detail').css({
    //                 'display': 'block'
    //             });
    //         }
    //     }
    // });
    $(".col-md-5.col-md-pull-7").jScroll({speed : "slow"});

});
function selectCuote(id){
    console.log('click');
    $('#cuote').text($('#span-text-'+id).text())
}