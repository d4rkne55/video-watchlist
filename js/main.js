$(document).ready(function() {
    $('form').eq(0).submit(function(e) {
        e.preventDefault();

        $.post(location.pathname + 'add', { url: this.elements.url.value }, function(data) {
            var video = $('main article').eq(0).clone();
            video.find('a')[0].href = data.url;
            video.find('img')[0].src = data.thumbnail;
            video.find(':header').eq(0).text(data.title);

            video.prependTo('main');
        }, 'json')
        .fail(function(xhr) {
            var error = JSON.parse(xhr.responseText).error;

            console.log(error);
        });
    });

    $('article .delete').click(function(e) {
        if (e.button == 0) {
            var video = $(this.parentNode);

            $.post(location.pathname + 'delete', { url: this.nextElementSibling.href }, function(response) {
                if (response) {
                    video.remove();
                }
            }, 'json')
        }
    });
})