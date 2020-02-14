jQuery(document).ready(function(){
    var $ = jQuery;
    console.log("TPPPS");
    // $('.tpp-power-slider .carousel').carousel('cycle');
    $('.tpp-power-slider').slick({
        autoplay: true,
        dots: true,
        infinite: true,
        slidesToShow: 3,
        slidesToScroll: 3
    });
    console.log($('.tpp-power-slider'));
});
