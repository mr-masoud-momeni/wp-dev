jQuery(function ($) {

    $('#commentform').on('submit', function (e) {

        e.preventDefault();

        let formData = new FormData(this);

        formData.append('action', 'wccr_submit_review');
        formData.append('nonce', wccr.nonce);

        $.ajax({
            url: wccr.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,

            success: function (response) {
                
                alert(response.data.message);

            }

        });

    });

});