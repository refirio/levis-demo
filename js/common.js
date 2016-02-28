$(document).ready(function() {

    /*
     * プレビュー
     */
    $('.preview').on('click', function() {
        $(this).closest('form').attr('target', '_blank');
        $(this).closest('form').find('input[name=preview]').val('yes');

        $(this).closest('form').submit();

        $(this).closest('form').find('input[name=preview]').val('no');
        $(this).closest('form').attr('target', '');

        return false;
    });
    $('.close').on('click', function() {
        window.close();

        return false;
    });

    /*
     * 入力フォーム
     */
    $('form[method=post] input[type=text], form[method=post] textarea').on('change', function() {
        $(window).on('beforeunload', function() {
            return '編集中の内容は破棄されます。';
        });
    });
    $('form[method=post] :submit').removeAttr('disabled');
    $('form[method=post]').on('submit', function() {
        var form = $(this);

        form.find(':submit').attr('disabled', 'disabled');

        setTimeout(function() {
            form.find(':submit').removeAttr('disabled');
        }, 3000);

        $(window).off('beforeunload');

        return true;
    });

    /*
     * 削除確認
     */
    $('a.delete').on('click', function() {
        return window.confirm('本当に削除してもよろしいですか？');
    });
    $('form.delete').on('submit', function() {
        if (window.confirm('本当に削除してもよろしいですか？')) {
            return true;
        } else {
            $(this).find(':submit').removeAttr('disabled');

            return false;
        }
    });

    /*
     * サブウインドウ
     */
    $('a.image').subwindow({
        width: 500,
        height: 400,
        loading: 'Now Loading...',
        close: '×',
        fade: 500
    });

});
