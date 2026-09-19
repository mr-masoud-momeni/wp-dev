<?php

if (!defined('ABSPATH')) {
    exit;
}

function octo_send_sms_ir($phone, $code)
{
    $apiKey      = get_option('octo_auth_sms_api_key');
    $templateId  = get_option('octo_auth_sms_template_id');
    $lineNumber  = get_option('octo_auth_sms_line_number');

    if (empty($apiKey) || empty($templateId)) {
        throw new Exception('SMS configuration is missing.');
    }

    $url = 'https://api.sms.ir/v1/send/verify';

    $args = [
        'body' => wp_json_encode([
            'mobile'     => $phone,
            'templateId' => (int) $templateId,
            'parameters' => [
                [
                    'name'  => 'CODE',
                    'value' => $code
                ]
            ]
        ]),
        'headers' => [
            'Content-Type' => 'application/json',
            'X-API-KEY'    => $apiKey
        ],
        'timeout' => 15
    ];

    $response = wp_remote_post($url, $args);

    if (is_wp_error($response)) {
        throw new Exception($response->get_error_message());
    }

    $statusCode = wp_remote_retrieve_response_code($response);

    if ($statusCode !== 200) {
        throw new Exception('SMS API returned status code ' . $statusCode);
    }

    $body = json_decode(wp_remote_retrieve_body($response), true);

    if (!is_array($body)) {
        throw new Exception('Invalid response from SMS API.');
    }

    // در صورت نیاز می‌توان این قسمت را با ساختار واقعی پاسخ SMS.ir تطبیق داد.
    if (isset($body['status']) && (int) $body['status'] !== 1) {

        $message = $body['message'] ?? 'SMS ارسال نشد.';

        throw new Exception($message);
    }

    return true;
}