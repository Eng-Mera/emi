/**
 * Created by qamaruddin on 25/07/16.
 */

(function ($) {
    $(document).ready(function () {
        $('#datepicker').datepicker({
            autoclose: true,
            todayHighlight: true,
            minDate: 0,
            dateFormat: 'yy-mm-dd'
        });

        $('#auto').bind({

        });


        $("#auto").autocomplete({
            source: "/admin/autocomplete/user",
            // autoFocus:true,
            minLength: 1,
            select: function( event, ui ) {
                $('#auto').val(ui.item.id);
            }
        });

        // $('.auto-complete').autocomplete({
        // source: function (request, response) {
        //     $.getJSON('/admin/autocomplete/user', {term: request.term}, function (data) {
        //             var ret = [];
        //             for (var i = 0; i < data.length; i++)
        //             {
        //                 if (!data[i]['username'] || !data[i]['id'])
        //                 {
        //                     continue;
        //                 }
        //                 ret.push({
        //                     label: data[i]['username'],
        //                     value: data[i]['id']
        //                 });
        //             }
        //             console.log(ret);
        //             return response(ret);
        //         });
        //     },
        //     minLength: 3,
        //     focus: function (event, ui) {
        //
        //     },
        //     select: function (event, ui) {
        //         $('.auto-complete-target').first().val(ui.item.value);
        //         ui.item.value = ui.item.label;
        //     }
        // });

    });
})(jQuery);