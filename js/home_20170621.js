

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
    var videoUrl = $this.attr("data-media");
    var popup = $this.attr("href");
    var $popupIframe = $(popup).find("iframe");

    $popupIframe.attr("src", videoUrl);

    $(".page").addClass("show-popup");
});

$(".popup").on("click", function(e) {
    e.preventDefault();
    e.stopPropagation();

    $(".page").removeClass("show-popup");
});

$(".popup > iframe").on("click", function(e) {
    e.stopPropagation();
});