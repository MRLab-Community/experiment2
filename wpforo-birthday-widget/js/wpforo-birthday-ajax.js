jQuery(document).ready(function($) {
    // Update birthday via AJAX
    $('#update-birthday').on('click', function(e) {
        e.preventDefault();

        var birthday = $('#wpforo_birthday_date').val();
        var user_id = wpforo_birthday_ajax.user_id;

        $.ajax({
            url: wpforo_birthday_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wpforo_birthday_update',
                birthday: birthday,
                user_id: user_id,
                security: wpforo_birthday_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#wpforo-birthday-message').html('<div class="updated"><p>' + response.data + '</p></div>');
                } else {
                    $('#wpforo-birthday-message').html('<div class="error"><p>' + response.data + '</p></div>');
                }
            },
            error: function() {
                $('#wpforo-birthday-message').html('<div class="error"><p>An error occurred. Please try again.</p></div>');
            }
        });
    });

    // Reset birthday via AJAX
    $('#reset-birthday').on('click', function(e) {
        e.preventDefault();

        var user_id = wpforo_birthday_ajax.user_id;

        $.ajax({
            url: wpforo_birthday_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'wpforo_birthday_reset',
                user_id: user_id,
                security: wpforo_birthday_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('#wpforo-birthday-message').html('<div class="updated"><p>' + response.data + '</p></div>');
                    $('#wpforo_birthday_date').val('');
                } else {
                    $('#wpforo-birthday-message').html('<div class="error"><p>' + response.data + '</p></div>');
                }
            },
            error: function() {
                $('#wpforo-birthday-message').html('<div class="error"><p>An error occurred. Please try again.</p></div>');
            }
        });
    });
});