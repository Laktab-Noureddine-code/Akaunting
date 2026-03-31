<?php
return [
    'model' => env('MEDIABLE_MODEL', App\Models\Common\Media::class),
    'mediables_table' => env('MEDIABLE_TABLE', 'mediables'),
    'default_disk' => env('MEDIABLE_DEFAULT_DISK', 'uploads'),
    'allowed_disks' => [
        'uploads',
        's3',
    ],
    'max_size' => 1024 * 1024 * 10,
    'on_duplicate' => Plank\Mediable\MediaUploader::ON_DUPLICATE_INCREMENT,
    'strict_type_checking' => false,
    'allow_unrecognized_types' => false,
    'allowed_mime_types' => [],
    'allowed_extensions' => [],
    'allowed_aggregate_types' => [],
    'aggregate_types' => [
        Plank\Mediable\Media::TYPE_IMAGE => [
            'mime_types' => [
                'image/jpeg',
                'image/png',
                'image/gif',
            ],
            'extensions' => [
                'jpg',
                'jpeg',
                'png',
                'gif',
            ]
        ],
        Plank\Mediable\Media::TYPE_IMAGE_VECTOR => [
            'mime_types' => [
                'image/svg+xml',
            ],
            'extensions' => [
                'svg',
            ]
        ],
        Plank\Mediable\Media::TYPE_PDF => [
            'mime_types' => [
                'application/pdf',
            ],
            'extensions' => [
                'pdf',
            ]
        ],
        Plank\Mediable\Media::TYPE_AUDIO => [
            'mime_types' => [
                'audio/aac',
                'audio/ogg',
                'audio/mpeg',
                'audio/mp3',
                'audio/mpeg',
                'audio/wav'
            ],
            'extensions' => [
                'aac',
                'ogg',
                'oga',
                'mp3',
                'wav',
            ]
        ],
        Plank\Mediable\Media::TYPE_VIDEO => [
            'mime_types' => [
                'video/mp4',
                'video/mpeg',
                'video/ogg',
                'video/webm'
            ],
            'extensions' => [
                'mp4',
                'm4v',
                'mov',
                'ogv',
                'webm'
            ]
        ],
        Plank\Mediable\Media::TYPE_ARCHIVE => [
            'mime_types' => [
                'application/zip',
                'application/x-compressed-zip',
                'multipart/x-zip',
            ],
            'extensions' => [
                'zip',
            ]
        ],
        Plank\Mediable\Media::TYPE_DOCUMENT => [
            'mime_types' => [
                'text/plain',
                'application/plain',
                'text/xml',
                'text/json',
                'application/json',
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            ],
            'extensions' => [
                'doc',
                'docx',
                'txt',
                'text',
                'xml',
                'json',
            ]
        ],
        Plank\Mediable\Media::TYPE_SPREADSHEET => [
            'mime_types' => [
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'text/csv',
            ],
            'extensions' => [
                'xls',
                'xlsx',
                'csv',
            ]
        ],
        Plank\Mediable\Media::TYPE_PRESENTATION => [
            'mime_types' =>
                [
                    'application/vnd.ms-powerpoint',
                    'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                    'application/vnd.openxmlformats-officedocument.presentationml.slideshow'
                ],
            'extensions' =>
                [
                    'ppt',
                    'pptx',
                    'ppsx',
                ]
        ],
    ],
    'source_adapters' => [
        'class' => [
            Symfony\Component\HttpFoundation\File\UploadedFile::class => Plank\Mediable\SourceAdapters\UploadedFileAdapter::class,
            Symfony\Component\HttpFoundation\File\File::class => Plank\Mediable\SourceAdapters\FileAdapter::class,
            Psr\Http\Message\StreamInterface::class => Plank\Mediable\SourceAdapters\StreamAdapter::class,
        ],
        'pattern' => [
            '^https?://' => Plank\Mediable\SourceAdapters\RemoteUrlAdapter::class,
            '^/' => Plank\Mediable\SourceAdapters\LocalPathAdapter::class,
            '^[a-zA-Z]:\\\\' => Plank\Mediable\SourceAdapters\LocalPathAdapter::class
        ],
    ],
    'url_generators' => [
        'local' => Plank\Mediable\UrlGenerators\LocalUrlGenerator::class,
        's3' => Plank\Mediable\UrlGenerators\S3UrlGenerator::class,
    ],
    'rehydrate_media' => true,
    'detach_on_soft_delete' => false,
];
