/**
 * Created by andrey on 11.07.16.
 */
$(function(){
    var $dateInput = $('.datepicker');

    $dateInput.datepicker({
        format: "yyyy-mm-dd",
        weekStart: 0,
        autoclose: true,
        todayHighlight: true,
        clearBtn: true
    });

    $dateInput.bind('keypress cut paste', function (e) {
        e.preventDefault();
        return false;
    });
});
