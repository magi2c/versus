var ovApp = {};
(function(mod) {
    "use strict";
    mod.initSearch = function() {
        $(".tree").treemenu({}).openActive();
        mod.searchQuery();
    };

    mod.searchQuery = function() {
        $('#search-query-go').click(function( event ) {
            if($('#search-query').val() != '') {
                var url = $(this).data('url');
                var replace = $(this).data("replace");
                var query = $('#search-query').val();
                var newUrl = url.replace(new RegExp(replace, "g"), query);

                $(this).attr('href', newUrl);

                return true;
            }

            event.stopPropagation();
        });
    };

})(ovApp);