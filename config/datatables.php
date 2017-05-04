<?php

return [
    'search' => [
        'smart'            => false,
        'case_insensitive' => false,
        'use_wildcards'    => false,
    ],

    'fractal' => [
        'serializer' => 'League\Fractal\Serializer\DataArraySerializer',
    ],

    'script_template' => 'datatables::script',
];
