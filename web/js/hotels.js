$(document).ready(function () {
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

    $("#roomsQty").change(function () {
        if (this.value == 1) {
            $('#room').show();
            $('#extraRooms').addClass('hide');
            $("#childrenRoom1, #childrenRoom2, #childrenRoom3, #childrenRoom4").val(0).change();
        } else {
            $('#room').hide();
            $('#childrenRoom').val(0).change();
            $('#extraRooms').removeClass('hide');
        }
        for (var i = 1; i < 5; i++) {
            if (this.value != 1 && i <= this.value) {
                $('.room' + i).show();
            } else {
                $('.room' + i).hide();
            }
        }
    });

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
