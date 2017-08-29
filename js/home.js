

$('.what-is-link').hover(function () {
    $(this).prev('.fa').addClass('icon-hover');
}, function () {
    $('.fa').removeClass('icon-hover');
});

$('.fa').hover(function () {
    $(this).next('.what-is-link').addClass('underline');
}, function () {
    $(this).next('.what-is-link').removeClass('underline');
})

/**/
$("[data-media]").on("click", function(e) {
    e.preventDefault();
    var $this = $(this);
    // var videoUrl = $this.attr("data-media");
    // var popup = $this.attr("href");
    // var $popupIframe = $(popup).find("iframe");
    // $popupIframe.attr("src", videoUrl);
    $(".page").addClass("show-popup");
    player.playVideo();
});

var tag = document.createElement('script');
tag.src = "https://www.youtube.com/player_api";
var firstScriptTag = document.getElementsByTagName('script')[0];
firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

var player;
function onYouTubePlayerAPIReady() {
    var width = $(window).width();
    width -= 30;
    if(width > 720) {
        width = 720;
    }
    var height = width * 405 / 720;
    player = new YT.Player('player', {
        height: height,
        width: width,
        videoId: 'ZcPBcyEzpAU',
        playerVars: {
            rel: 0
        },
        events: {
            'onStateChange': onPlayerStateChange
        }
    });
}

function onPlayerStateChange(event) {
    if(event.data === 0) {
        player.stopVideo();
        $(".page").removeClass("show-popup");
    }
}

$(".popup").on("click", function(e) {
    e.preventDefault();
    e.stopPropagation();
    player.stopVideo();
    $(".page").removeClass("show-popup");
});

$(".popup > iframe").on("click", function(e) {
    e.stopPropagation();
});
