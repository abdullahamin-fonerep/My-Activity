<?php
defined('MOODLE_INTERNAL') || die();

// Module settings
if ($ADMIN->fulltree) {
    // Add setting for the name
    $settings->add(new admin_setting_configtext('myactivity/name',
        get_string('name', 'mod_myactivity'),
        get_string('name_desc', 'mod_myactivity'),
        'Your Name', PARAM_TEXT));

    // Add setting for the video URL
    $settings->add(new admin_setting_configtext('myactivity/video_url',
        get_string('video_url', 'mod_myactivity'),
        get_string('video_url_desc', 'mod_myactivity'),
        'https://example.com/video.mp4', PARAM_URL));

    // Get the current settings values
    $currentname = get_config('myactivity', 'name');
    $video_url = get_config('myactivity', 'video_url');

    // Add an HTML output of the current settings
    $settings->add(new admin_setting_heading('myactivity_settings_summary',
        get_string('current_settings', 'mod_myactivity'),
        html_writer::tag('p', get_string('name', 'mod_myactivity') . ': ' . s($currentname) .
        '<br>' . get_string('video_url', 'mod_myactivity') . ': ' . html_writer::link(s($video_url), s($video_url)))));

    // Function to convert standard YouTube URL to embed URL
    if(!function_exists('createEmbeddedUrl')){
         function createEmbeddedUrl($url) {
        // Regular expression patterns for matching different YouTube URL formats
        $patterns = array(
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]+)/', // Standard URL format
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]+)/', // Embed URL format
            '/youtu\.be\/([a-zA-Z0-9_-]+)/' // Short URL format
        );

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                // Extract video ID
                $video_id = $matches[1];
                // Return embeddable URL
                return "https://www.youtube.com/embed/" . $video_id;
            }
        }

        // Return the original URL if no match is found
        return $url;
    }}
    

    // Get the embeddable URL if the video URL is provided
    $embed_url = createEmbeddedUrl($video_url);

    // Display the video player if an embeddable URL is valid
    if (!empty($embed_url)) {
        $settings->add(new admin_setting_heading('myactivity_video_player',
            get_string('current_video', 'mod_myactivity'),
            html_writer::tag('iframe', '', array(
                'width' => '560',
                'height' => '315',
                'src' => s($embed_url),
                'frameborder' => '0',
                'allow' => 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture',
                'allowfullscreen' => 'allowfullscreen'
            ))
        ));
    }
}
