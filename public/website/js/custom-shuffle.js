(function ($) {
    "use strict"; // Start of use strict

    /**=========================
        SHUFFLE FILTER
    =========================**/
    var Shuffle = window.Shuffle;
        var myShuffle = new Shuffle(document.querySelector('.theme-shuffle'), {
            itemSelector: '.shuffle-image-item',
            sizer: '.theme-shuffle-result',
            buffer: 1,
        });
        window.jQuery('input[name="shuffle-filter"]').on('change', function (evt) {
        var input = evt.currentTarget;
        if (input.checked) {
            myShuffle.filter(input.value);
        }
    });

})(jQuery); // End of use strict