(function ($) {

    /**
     * WordPress Media Uploader
     *
     * @param {string} uploadButton  Upload button selector
     * @param {string} removeButton  Remove button selector
     * @param {string} input         Hidden input selector
     * @param {string} preview       Preview image selector
     */
    window.octoMediaUploader = function (
        uploadButton,
        removeButton,
        input,
        preview
    ) {

        let frame;

        // Upload image
        $(document).on('click', uploadButton, function (e) {

            e.preventDefault();

            if (frame) {
                frame.open();
                return;
            }

            frame = wp.media({

                title: 'Select Image',

                button: {
                    text: 'Use Image'
                },

                multiple: false

            });

            frame.on('select', function () {

                const attachment = frame
                    .state()
                    .get('selection')
                    .first()
                    .toJSON();

                $(input).val(attachment.id);

                $(preview)
                    .attr('src', attachment.url)
                    .show();

                $(removeButton).show();

            });

            frame.open();

        });

        // Remove image
        $(document).on('click', removeButton, function (e) {

            e.preventDefault();

            $(input).val('');

            $(preview)
                .attr('src', '')
                .hide();

            $(removeButton).hide();

        });

        // Initial state
        if ($(input).val()) {

            $(preview).show();
            $(removeButton).show();

        } else {

            $(preview).hide();
            $(removeButton).hide();

        }

    };

})(jQuery);


// =========================
// Banner
// =========================

jQuery(function () {

    octoMediaUploader(
        '#octo_upload_mobile_image',
        '#octo_remove_mobile_image',
        '#octo_mobile_image',
        '#octo_mobile_image_preview'
    );

});