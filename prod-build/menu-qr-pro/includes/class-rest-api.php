<?php
/**
 * REST API Endpoint for Menu QR Pro - Open Access Version
 * Temporary: No permission check (for testing)
 */

add_action('rest_api_init', function () {
    // Save data via GET - OPEN ACCESS (test için)
    register_rest_route('mqpro/v1', '/save-get', array(
        'methods' => 'GET',
        'callback' => 'mqpro_rest_save_get',
        'permission_callback' => '__return_true' // Open
    ));

    // Get data endpoint
    register_rest_route('mqpro/v1', '/get/(?P<key>[a-zA-Z0-9_-]+)', array(
        'methods' => 'GET',
        'callback' => 'mqpro_rest_get_data',
        'permission_callback' => '__return_true'
    ));
});

function mqpro_rest_save_get($request)
{
    // GEÇİCİ: Permission kontrolü yok (test için)
    // TODO: Production'da nonce veya API key ekle

    $key = $request->get_param('key');
    $value = $request->get_param('value');

    if (empty($key)) {
        return new WP_Error('missing_key', 'Missing key parameter', array('status' => 400));
    }

    if (empty($value)) {
        return new WP_Error('missing_value', 'Missing value parameter', array('status' => 400));
    }

    // Validate JSON
    $decoded = json_decode($value, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return new WP_Error('invalid_json', 'Invalid JSON: ' . json_last_error_msg(), array('status' => 400));
    }

    // Save
    $result = update_option('mickey_' . $key, $value);

    if ($result || get_option('mickey_' . $key) === $value) {
        return array(
            'success' => true,
            'message' => 'Data saved successfully',
            'key' => $key,
            'items' => count($decoded)
        );
    }

    return new WP_Error('save_failed', 'Failed to save data', array('status' => 500));
}

function mqpro_rest_get_data($request)
{
    $key = $request->get_param('key');

    if (empty($key)) {
        return new WP_Error('missing_key', 'Missing key parameter', array('status' => 400));
    }

    $data = get_option('mickey_' . $key, null);

    return array('success' => true, 'data' => $data);
}
