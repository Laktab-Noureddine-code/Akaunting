<?php
return array(
    'filename'  => '_ide_helper',
    'format'    => 'php',
    'meta_filename' => '.phpstorm.meta.php',
    'include_fluent' => true,
    'include_factory_builders' => false,
    'write_model_magic_where' => false,
    'write_model_relation_count_properties' => false,
    'write_eloquent_model_mixins' => false,
    'include_helpers' => false,
    'helper_files' => array(
        base_path() . '/vendor/laravel/framework/src/Illuminate/Support/helpers.php',
    ),
    'model_locations' => array(
        'app/Models',
        'modules
    'ignored_models' => array(
    ),
    'extra' => array(
        'Eloquent' => array('Illuminate\Database\Eloquent\Builder', 'Illuminate\Database\Query\Builder'),
        'Session' => array('Illuminate\Session\Store'),
    ),
    'magic' => array(),
    'interfaces' => array(
    ),
    'custom_db_types' => array(
    ),
    'model_camel_case_properties' => false,
    'type_overrides' => array(
        'integer' => 'int',
        'boolean' => 'bool',
    ),
    'include_class_docblocks' => false,
);
