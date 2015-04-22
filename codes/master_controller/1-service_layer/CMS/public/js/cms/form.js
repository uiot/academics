$(document).ready(
    function () {
        $(".no_space").bind(
            'keydown paste', function () {
                if (event.type == "keydown" && event.which == 32) {
                    return false;
                }
                if (event.type == "paste" && event.clipboardData.getData('text/plain').indexOf(" ") != -1) {
                    return false;
                }
            }
        );
        $(".replace_space").bind(
            'keydown paste', function () {
                if (event.type == "keydown" && event.which == 32) {
                    $(this).val($.trim($(this).val()) + "_");
                    return false;
                }
                if (event.type == "paste" && event.clipboardData.getData('text/plain').indexOf(" ") != -1) {
                    var glue = event.clipboardData.getData('text/plain').replace(new RegExp(" ", 'g'), "_");
                    $(this).val($.trim($(this).val()) + glue);
                    return false;
                }
            }
        );
        jQuery.fn.ForceNumericOnly =
            function () {
                return this.each(
                    function () {
                        $(this).keydown(
                            function (e) {
                                var key = e.charCode || e.keyCode || 0;
                                return ( key == 8 || key == 9 || key == 13 || key == 46 || key == 110 || key == 190 || (key >= 35 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105));
                            }
                        );
                    }
                );
            };
        $(".numbers_only").ForceNumericOnly();
    }
);