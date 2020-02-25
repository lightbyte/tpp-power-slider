
jQuery(document).ready(function(){
    var $ = jQuery;
    $('.tpp-power-slider').slick({
        "autoplay": false,
        "dots": false,
        "infinite": true,
        "slidesToShow": 3,
        "slidesToScroll": 3,
        "responsive":[
        {
            "breakpoint": 1140,
            "settings": {
                "arrows": false,
                "slidesToShow": 2,
                "slidesToScroll": 2,
            }

        },{
            "breakpoint": 960,
            "settings": {
                "arrows": false,
                "slidesToShow": 1,
                "slidesToScroll": 1,
            }

        }]
    })
    .on('breakpoint', function(event, slick, breakpoint){
        initEvents();
    });

    function initEvents(){
        $('.tpp-power-slider.r-collapsible .slider-item')
            .each(function(i, e){
                var $e = $(e);
                $e.find('.r-more a').click(function(){
                    $e.find('.r-text').toggleClass('d-none');
                    $e.find('.r-text-full').toggleClass('d-none');
                });
            });
    }
    initEvents();
});
