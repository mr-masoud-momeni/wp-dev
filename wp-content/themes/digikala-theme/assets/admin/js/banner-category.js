jQuery(document).ready(function ($) {


    function toggleBannerSettings() {

        const layout = $('#octo_banner_layout').val();


        if (layout === 'slider') {

            $('#octo-slider-settings').show();

        } else {

            $('#octo-slider-settings').hide();

        }

    }



    // Change Event

    $('#octo_banner_layout').on(
        'change',
        toggleBannerSettings
    );



    // Initial Load

    toggleBannerSettings();


});