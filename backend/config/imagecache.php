<?php
return array(
    'route' => null,
    'paths' => array(
        public_path('upload'),
        public_path('images')
    ),
    'templates' => array(
        'small' => 'Intervention\Image\Templates\Small',
        'medium' => 'Intervention\Image\Templates\Medium',
        'large' => 'Intervention\Image\Templates\Large',
    ),
    'lifetime' => 43200,
);
