$(function () {
    var big_parent; // now edit
    var edit_box_image = $("#sim-edit-image");
    var edit_box_text = $("#sim-edit-text");
    //Edit
    $(this).find(".sim-row-edit").hover(
        function () {
            $(this).append('<div class="sim-row-edit-hover"></div>');

            $(".sim-row-edit-hover").click(function (e) {
                e.preventDefault();
                big_parent = $(this).parent();

                //edit image
                if (big_parent.attr("data-type") == 'image') {
                    edit_box_image.find('.image').val(big_parent.children('img').attr("src"));
                    edit_box_image.fadeIn(500);
                    edit_box_image.find('.sim-edit-box').slideDown(500);

                    edit_box_image.find('.sim-edit-box-buttons-save').click(function () {
                        $(this).parent().parent().parent().fadeOut(500);
                        $(this).parent().parent().slideUp(500);
                        big_parent.children('img').attr("src", edit_box_image.find('.image').val());
                    });
                }

                //edit text
                if (big_parent.attr("data-type") == 'text') {
                    edit_box_text.find('.text').val(big_parent.text());
                    edit_box_text.fadeIn(500);
                    edit_box_text.find('.sim-edit-box').slideDown(500);
                    edit_box_text.find('.sim-edit-box-buttons-save').click(function () {
                        $(this).parent().parent().parent().fadeOut(500);
                        $(this).parent().parent().slideUp(500);

                        var text = edit_box_text.find('.text').val();
                        text = text.replace(/([^>])\n/g, '$1<br>');
                        text = text.replace(/\n/g, '<br>');
                        text = text.replace('<br>', '<br>\n');
                        big_parent.html(text);
                    });
                }
            });
        }, function () {
            $(this).children(".sim-row-edit-hover").remove();
        }
    );

    //close edit
    $(".sim-edit-box-buttons-cancel").click(function () {
        $(this).parent().parent().parent().fadeOut(500);
        $(this).parent().parent().slideUp(500);
    });
});

//Download
function save(title) {
    var textarea = $("#export-textarea");
    var titlei = $("#export-title");
    titlei.val(title);
    textarea.val($('html')[0].outerHTML);
    $("#export-form").submit();
    textarea.val(' ');
    titlei.val(' ');
}
