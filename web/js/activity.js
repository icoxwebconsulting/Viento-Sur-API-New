$(document).ready(function () {
    var url = Routing.generate('search_city_autocomplete');

    $('#autocomplete-activity').on('click', function () {
        $(this).select();
    });

    $('#autocomplete-activity').on('blur', function () {
        setTimeout(function () {
            if ($("#destinationCity-activity").val() === '') {
                $('#autocomplete-activity').val('');
            }
        }, 120);
    });
    
    $('#autocomplete-activity').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            $("#destinationCity-activity").val(suggestion.data.value);
            getLatLongMap(suggestion.data.value)
        },
        minChars: 3,
        groupBy: 'category',
        onInvalidateSelection: function () {
            $("#destinationCity-activity").val('');
        }
    });
    
    function moveProgressBar(elem) {
            var width = 1;
            var id = setInterval(frame, 40);
            function frame() {
                if (width >= 100) {
                    clearInterval(id);
                } else {
                    width++;
                    elem.style.width = width + '%';
                }
            }
        }
    $('#submitActivity').on('click', function () {
        var form = $('#search-activity')[0];
        if(form.checkValidity()){
            $('#search-activity').hide();
            $('#searchActivityMsg').removeClass('hide');
            moveProgressBar($('#activity_pb')[0]);
        }
    });
    
    function getLatLongMap(city){
        L.mapquest.key = 'vcdIMmp9OiCGm1lsGE4Z1QRz5AVABDyG';
        L.mapquest.open = true;

        L.mapquest.geocoding().geocode(city, createMap);  
    }
    function createMap(error,response) {
        var location = response.results[0].locations[0];
        var lat = location.displayLatLng.lat;
        var lng = location.displayLatLng.lng;
        $("#latitude").val(lat);
        $("#longitude").val(lng);
    }
});


