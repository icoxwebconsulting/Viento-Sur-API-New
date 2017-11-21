$(document).ready(function () {

    $('#data-adults').text(1);
    $('#data-childs').text(0);
    $('#data-room').text(1);
    $('#roomsQty').val(1);

    $('.coupled').change(function () {
        var couple = $(this).data('couple');
        couple = couple.split('.');
        if (couple) {
            var diff = 8 - this.value;
            var opts = '';
            var i = (couple[1] == '0') ? 0 : 1;
            var selected = $('#' + couple).find(":selected").val();
            for (; i <= diff; i++) {
                opts += '<option value="' + i + '" ' + ((i == selected) ? 'selected="selected"' : '' ) + '>' + i + '</option>';
            }
            $('#' + couple).empty().append(opts);
        }
    });

    $("#add-room").click(function(){
        var visibleRoom = $(".room:visible").length;
        console.log('visible room', visibleRoom);

        var countRoom = visibleRoom + 1;
        console.log(countRoom);
        $('.room'+countRoom).show();

        if ((countRoom > 1) && (countRoom < 5)){
            $('#delete-room').show();
        }
    });

    $("#delete-room").click(function(){
        var visibleRoom = $(".room:visible").length;
        // var id = "#childrenRoom"+visibleRoom;

        if (visibleRoom > 1){
            $('.room'+visibleRoom).hide();
            // $(id+" option:first-child").attr("selected", true);

            if (visibleRoom == 2){
                $('#delete-room').hide();
            }
        }
    });

    $("#data-travelers").click(function(){
        var adults = 0;
        var childs = 0;
        var count = 0;
        var visibleRoom = $(".room:visible").length;

        for(var i=0; i<visibleRoom; i++){
            console.log(i);
            count++;
            console.log('count', count);

            adults = adults + parseInt($('#adults'+count).val());
            childs = childs + parseInt($('#childrenRoom'+count).val());
        }
        $('#data-adults').text(adults);
        $('#data-childs').text(childs);
        $('#data-room').text(visibleRoom);

        $('#roomsQty').val(visibleRoom);
    });

    $("#roomsQty").change(function () {
        if (this.value == 1) {
            $('#room').show();
            $('#extraRooms').addClass('hide');
            $("#childrenRoom1, #childrenRoom2, #childrenRoom3, #childrenRoom4").val(0).change();
        } else {
            $('#room').hide();
            $('#childrenRoom').val(0).change();
            $('#extraRooms').removeClass('hide');
            $('.room1').removeClass('hide');
            $('.room2').removeClass('hide');
            $('.room3').removeClass('hide');
            $('.room4').removeClass('hide');
        }
        for (var i = 1; i < 5; i++) {
            if (this.value != 1 && i <= this.value) {
                $('.room' + i).show();
            } else {
                $('.room' + i).hide();
            }
        }
    });
    
    if($('#childrenRoom').val() == 0){
        console.log('ocultar child')    
    }else{
        console.log('mostrar chil');
        $('#ageBlock').removeClass('hide');
    }
    if($('#childrenRoom1').val() != 0){
        // console.log('room1',$('#childrenRoom1').val())
        var count = $('#childrenRoom1').val();
        $('#menor-room1').removeClass('hide');
        $('.child1').css({'display': 'none'});
        var aux;
        for (var i=0; i<count; i++){
            aux = i + 1;
            $('.childAge'+aux+'1').css({'display': 'block'});
            // console.log('aux', $('.childAge'+aux+'1'));
            aux++;
        }
    }
    if($('#childrenRoom2').val() != 0){
        // console.log('room2',$('#childrenRoom2').val())
        $('#menor-room2').removeClass('hide');
        $('.child2').css({'display': 'none'});
        var count = $('#childrenRoom2').val();
        var aux;
        for (var i=0; i<count; i++){
            aux = i + 1;
            $('.childAge'+aux+'2').css({'display': 'block'});
            // console.log('aux', $('.childAge'+aux+'2'));
            aux++;
        }
    }
    if($('#childrenRoom3').val() != 0){
        $('#menor-room3').removeClass('hide');
        $('.child3').css({'display': 'none'});
        var count = $('#childrenRoom3').val();
        var aux;
        for (var i=0; i<count; i++){
            aux = i + 1;
            $('.childAge'+aux+'3').css({'display': 'block'});
            // console.log('aux', $('.childAge'+aux+'3'));
            aux++;
        }
    }
    if($('#childrenRoom4').val() != 0){
        // console.log('room4',$('#childrenRoom4').val())
        $('#menor-room4').removeClass('hide');
        $('.child4').css({'display': 'none'});
        var count = $('#childrenRoom4').val();
        var aux;
        for (var i=0; i<count; i++){
            aux = i + 1;
            $('.childAge'+aux+'4').css({'display': 'block'});
            // console.log('aux', $('.childAge'+aux+'4'));
            aux++;
        }
    }
    $("#childrenRoom").change(function () {
        for (var i = 1; i < 8; i++) {
            var selector = 'childAge' + i + 'room';
            if (i <= this.value) {
                $('.' + selector).show();
                $('#' + selector).prop('required', true);
            } else {
                $('.' + selector).hide();
                $('#' + selector).removeProp('required');
            }
        }
        if (this.value > 0) {
            $("#ageBlock").removeClass('hide');
            $('.childAge0room').removeClass('hide');
            $('.childAge1room').removeClass('hide');
            $('.childAge2room').removeClass('hide');
            $('.childAge3room').removeClass('hide');
            $('.childAge4room').removeClass('hide');
            $('.childAge5room').removeClass('hide');
            $('.childAge6room').removeClass('hide');
            $('.childAge7room').removeClass('hide');
        } else {
            $("#ageBlock").addClass('hide');
        }
    });

    $("#childrenRoom1, #childrenRoom2, #childrenRoom3, #childrenRoom4").change(function () {
        var nro = this.id;
        nro = nro.slice(-1);
        if (this.value > 0) {
            for (var i = 1; i < 8; i++) {
                if (i <= this.value) {
                    var selector = 'childAge' + i + nro;
                    $('.' + selector).show();
                    $('#' + selector).prop('required', true);
                } else {
                    var selector = 'childAge' + i + nro;
                    $('.' + selector).hide();
                    $('#' + selector).removeProp('required');
                }
            }
            $('#menor-room' + nro).removeClass('hide');
        } else {
            for (var i = 1; i < 8; i++) {
                var selector = 'childAge' + i + nro;
                $('.' + selector).hide();
                $('#' + selector).removeProp('required');
            }
            $('#menor-room' + nro).addClass('hide');
        }
    });
});
