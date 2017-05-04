<?php
/**
 * NileCode Configurations
 *
 * custom configurations used by development team
 *
 * PHP version 5.4
 *
 * @author     Mustafa Qamar-ud-Din <m.qamaruddin@nilecode.com>
 * @author     Another Author <another@example.com>
 * @copyright  2016 Nilecode
 */

return [
    'emails' => [
        'sales' => [
            'name' => 'HTR Sales',
            'email' => 'sales@howtheyrate.net'
        ],
        'ads' => [
            'name' => 'HTR Ads',
            'email' => 'sales@howtheyrate.net'
        ]
    ],
    'backend' => [
        'pagination' =>
            [
                'page_size' => 10
            ]
    ],
    'cart' => [
        'currency' => [
            'code' => 'USD',
            'symbol' => '$'
        ]
    ],
];