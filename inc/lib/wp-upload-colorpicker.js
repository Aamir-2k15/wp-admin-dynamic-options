jQuery(document).ready(function($) {
    console.log('WpFormBuilder upload....');
    $('.color-picker').wpColorPicker();
    $('.wp-upload-button').click(function(e) {
        console.log('WpUpload Init....');
        e.preventDefault();
        const button = $(this);
        const field = button.siblings('input[type=text]');
        const preview = button.siblings(`#${field.attr('id')}_preview`);
        const uploader = wp.media({
            title: 'Upload Image',
            button: { text: 'Use this image' },
            multiple: false,
        }).on('select', function() {
            const selection = uploader.state().get('selection');
            const attachment = selection.first().toJSON().url;
            field.val(attachment);
            preview.empty().append(`<div class="uploaded-file"><img src="${attachment}" style="max-width:100px;" /><span class="remove-file" data-file="${attachment}">Remove</span></div>`);
        }).open();
    });

    // Remove file functionality
    $(document).on('click', '.remove-file', function() {
        const file = $(this).data('file');
        const field = $(this).closest('td').find('input[type=text]');
        const existing_files = field.val().split(',');
        const updated_files = existing_files.filter(function(value) {
            return value !== file;
        });
        field.val(updated_files.join(','));
        $(this).parent().remove();
    });
});