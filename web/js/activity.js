$(document).ready(function () {
    var url = Routing.generate('search_city_autocomplete');
    
    var $form = $('#form-filter'),
    origForm = $form.serialize();

    $('#autocomplete-activity').on('click', function () {
        $(this).select();
    });
    
    $('#autocomplete-activity').autocomplete({
        serviceUrl: url,
        onSelect: function (suggestion) {
            getLatLongMap(suggestion.data.value);
        },
        minChars: 3,
        groupBy: 'category',
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
    
    $('#form-filter :input').on('change input', function() {
        if(origForm !== $form.serialize()){
             $(this).keypress(function (e) {
                if (e.which === 13) {
                  $form.submit();
                  return false;    //<---- Add this line
                }
              });
        }
    });
});


