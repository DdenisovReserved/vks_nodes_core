$(document).ready(function() {

    function formatRepo (repo) {

        if (repo.loading) return repo.text;

        var markup = '<div class="clearfix">' +
            '<div class="col-sm-6">' + repo.login + '</div>';

        if (repo.fio) {
            markup += ' (' + repo.fio + ')';
        }
        markup += '</div>';
        return markup;
    }

    function formatRepoSelection (repo) {
        return repo.login || repo.text;
    }


    $(".js-user-apiFind").select2({
        ajax: {
            url: "?route=User/apiFind",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term // search term
                };
            },
            processResults: function (data, page) {
                // parse the results into the format expected by Select2.
                // since we are using custom formatting functions we do not need to
                // alter the remote JSON data
                return {
                    results: data.items
                };
            },
            cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 3,

        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });
})