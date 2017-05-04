<?php

if (env('APP_ENV') == 'production') {
    return array(
        'IOS' => array(
            'environment' => 'production',
            'certificate' => app_path() . '/certs/production/htr_dev.pem',
            'passPhrase' => 'HTR123',
            'service' => 'apns'
        ),
        'ANDROID' => array(
            'environment' => 'production',
            'apiKey' => 'com.meunity.htr.android​',
            'service' => 'gcm'
        )
    );
} else {
    return array(
        'IOS' => array(
            'environment' => 'development',
            'certificate' => app_path() . '/certs/HTR.pem',
            'passPhrase' => 'HTR123',
            'service' => 'apns'
        ),
        'ANDROID' => array(
            'environment' => 'development',
            'apiKey' => 'AIzaSyDyOjR5eqi1RTVRiba5oIJD-ZGZm94Lwmc',
            'service' => 'gcm'
        )
    );
}
