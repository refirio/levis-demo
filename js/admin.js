$(document).ready(function() {

    /*
     * アップロードファイルの処理
     */
    if ($('.upload').length > 0) {
        // アップロードファイルを削除
        var file_delete = function(key) {
            return function(e) {
                if (window.confirm('本当に削除してもよろしいですか？')) {
                    $.ajax({
                        type: 'post',
                        url: $(this).attr('href'),
                        cache: false,
                        data: '_type=json&_token=' + $(this).attr('data-token'),
                        dataType: 'json',
                        success: function(response) {
                            if (response.status == 'OK') {
                                // 正常終了
                                $('#' + key).attr('src', window.parent.$('#' + key).attr('src') + '&amp;' + new Date().getTime());
                                $('#' + key + '_menu').hide();
                            } else {
                                // 予期しないエラー
                                window.alert('予期しないエラーが発生しました。');
                            }
                        },
                        error: function(request, status, errorThrown) {
                            console.log(request);
                            console.log(status);
                            console.log(errorThrown);
                        }
                    });
                }

                return false;
            };
        };

        // 初期化
        if ($('#image_01').length > 0) {
            $('#image_01_menu').hide();
            $('#image_01_delete').click(file_delete('image_01'));
        }
        if ($('#image_02').length > 0) {
            $('#image_02_menu').hide();
            $('#image_02_delete').click(file_delete('image_02'));
        }

        $.ajax({
            type: 'get',
            url: $('form.validate').attr('action'),
            cache: false,
            data: '_type=json',
            dataType: 'json',
            success: function(response) {
                if (response.status == 'OK') {
                    $.each(response.files, function(key, value) {
                        if (value != null) {
                            $('#' + key + '_menu').show();
                        }
                    });
                }
            },
            error: function(request, status, errorThrown) {
                console.log(request);
                console.log(status);
                console.log(errorThrown);
            }
        });
    }

    /*
     * サブウインドウ
     */
    $('a.file_upload').subwindow({
        width: 500,
        height: 400,
        loading: 'Now Loading...',
        close: '×',
        fade: 500
    });

});
