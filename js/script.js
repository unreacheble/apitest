/**
 * Created by unreacheble on 26.08.15.
 */

$(document).ready(function () {
    $('#send').on('click',function(){
        var url = $('#form input[name=url]').val();
        var method = $('#form select[name=method]').val();
        var params = [];
        var values = [];
        $('.params input').each(function(){
            if( $(this).attr('name') == 'params' ) {
                params.push($(this).val());
            }else if( $(this).attr('name') == 'values' ) {
                values.push($(this).val());
            }
        });
        var data = {
            url: url,
            method: method,
            params: params,
            values: values,
            keyToFind: $('#form input[name=key]').val(),
            valToFind: $('#form input[name=val]').val()
        };
        jQuery.ajax({
            url: "request.php",
            data: data,
            method: 'post',
            dataType: 'json',
            success: function (resp) {
                $('#response').html('');
                if ( resp.result == 'error' ) {
                    $('#response').html('Error! Response code: ' + resp.status + '. Response text: ' + resp.error );
                } else {
                    var response = $.parseJSON(resp.response);
                    $.each(response, function(k, v){
                        $('#response').append( k + ': ' + v + '<br>');
                    });
                    if ( resp.search !== undefined ) {
                        if ( resp.search == true ) {
                            $('#response').append( 'OK' );
                        } else {
                            $('#response').append( 'NO' );
                        }
                    }
                }
            }
        });
    });
});