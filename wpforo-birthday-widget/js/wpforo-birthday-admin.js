jQuery(document).ready(function($) {
    var wpforo_birthday_media_frame; // Declare the variable globally

    // Media uploader for image selection
    $('#wpforo_birthday_upload_button').on('click', function(e) {
        e.preventDefault();

        // If the media frame already exists, reopen it
        if (wpforo_birthday_media_frame) {
            wpforo_birthday_media_frame.open();
            return;
        }

        // Create a new media frame
        wpforo_birthday_media_frame = wp.media({
            title: '<?php echo esc_js(__('Select or Upload Image', 'wpforo-birthday')); ?>',
            library: { type: 'image' },
            button: { text: '<?php echo esc_js(__('Use This Image', 'wpforo-birthday')); ?>' },
            multiple: false
        });

        // Handle image selection
        wpforo_birthday_media_frame.on('select', function() {
            var attachment = wpforo_birthday_media_frame.state().get('selection').first().toJSON();
            $('#wpforo_birthday_image').val(attachment.id);
            $('#wpforo_birthday_image_preview').html('<img src="' + attachment.url + '" alt="Preview" style="max-width: 100px; max-height: 100px;" />');
        });

        // Open the media frame
        wpforo_birthday_media_frame.open();
    });

    // Reset image button
    $('#wpforo_birthday_reset_image').on('click', function(e) {
        e.preventDefault();
        $('#wpforo_birthday_image').val(''); // Clear the image ID
        $('#wpforo_birthday_image_preview').html(''); // Clear the preview
        alert('<?php echo esc_js(__('Image removed successfully!', 'wpforo-birthday')); ?>');
    });
});