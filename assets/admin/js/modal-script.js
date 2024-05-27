jQuery( document ).ready( function($) {
    $( "#wcrs-allow, #wcrs-deny" ).click( function() {
        var value = $( this ).attr( 'id' ) === 'wcrs-allow' ? 1 : 0;
        
        // Include the nonce in the AJAX request data
        var data = {
            action: 'wcrs_mixpanel_alert_ajax_function',
            value: value,
            nonce: wcrs_nonce // Use the nonce generated earlier
        };
        
        $.ajax( {
            url: ajaxurl,
            type: 'POST',
            data: data,
            success: function( response ) {
                // Handle success
            },
            error: function( error ) {
                // Handle error
            }
        } );

        $( "#wcrs-myModal" ).hide();
    } );






    $("#get-support, #give-rating, #remind-later").click(function() {
        var actionType = $(this).attr('id');

        // Define the action based on the button clicked
        var actionMap = {
            'get-support': 'wcrs_get_support',
            'give-rating': 'wcrs_give_rating',
            'remind-later': 'wcrs_remind_later'
        };

        var data = {
            action: actionMap[actionType],
            // nonce: wcrs_nonce // Use the nonce generated earlier
        };

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: data,
            success: function(response) {
                console.log('Successfully Complated:', response);
                // You might want to hide the notice or show a message
                if (actionType === 'remind-later') {
                    $("#my-custom-notice").hide();
                }
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });

    function hideNotice() {
        $("#my-custom-notice").hide();
    }



} );