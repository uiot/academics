$(document).ready(
    function () {
        $(document).ready(
            function () {

                toggle_items();

                $(".side-nav li").click(
                    function () {
                        if ($(this).hasClass('heading')) {
                            var label = $(this).text();
                            var label = label.replace(/\s/g, "");
                            $('.' + label).toggle();
                        }
                    }
                );

                $("#search_item").keyup(
                    function () {
                        var filter = $(this).val();
                        var filter_length = $(this).val().length;
                        if (filter_length !== undefined) {
                            if (filter_length > 0) {
                                $("#form_search li").each(
                                    function () {

                                        if ($(this).hasClass('heading')) {
                                            // Nothing
                                        }
                                        else {
                                            if ($(this).text().search(new RegExp(filter, "i")) < 0) {
                                                var dataString = 'search=' + filter;
                                                $.post(
                                                    "/search/content/", dataString, function (html) {
                                                        $('#result_content').html(html);
                                                        $('.result_title').show();
                                                        $('#result_content').show();
                                                    }, 'html'
                                                );
                                                return false;
                                            }
                                            else {
                                                $(this).show();
                                            }
                                        }
                                    }
                                );
                            }
                            else {
                                hide_items();
                                hide_search();
                            }
                        }
                        else {
                            hide_items();
                            hide_search();
                        }
                    }
                );
            }
        );
    }
);

function toggle_item_by_class(class_id) {
    $("#form_search li").each(
        function () {
            if ($(this).hasClass('heading')) {
                var label = $(this).text();
                var label = label.replace(/\s/g, "");
                $('.' + label).toggle();
            }
        }
    );
}

function hide_search() {
    $('#result_content').hide();
    $('#result_content').html('');
    $('.result_title').hide();
}

function hide_items() {
    $("#form_search li").each(
        function () {
            if ($(this).hasClass('heading')) {
                var label = $(this).text();
                var label = label.replace(/\s/g, "");
                $('.' + label).hide();
            }
        }
    );
}

function toggle_items() {
    $("#form_search li").each(
        function () {
            if ($(this).hasClass('heading')) {
                var label = $(this).text();
                var label = label.replace(/\s/g, "");
                $('.' + label).toggle();
            }
        }
    );
}