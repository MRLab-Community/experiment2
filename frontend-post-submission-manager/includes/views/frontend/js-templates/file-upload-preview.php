<div class="fpsm-file-preview-row" data-media-id="{{data.media_id}}">
    <span class="fpsm-file-preview-column"><img src="{{data.media_url}}" /></span>
    <span class="fpsm-file-preview-column">{{data.media_name}}</span>
    <span class="fpsm-file-preview-column">{{data.media_size}}</span>
    <# if(!data.hideDelete) {#>
    <span class="fpsm-file-preview-column"><input type="button" class="fpsm-media-delete-button" data-media-id='{{data.media_id}}' data-media-key='{{data.media_key}}' value="<?php esc_html_e('Delete', 'frontend-post-submission-manager'); ?>"/></span>
    <# } #>
</div>