<?php

/**
 * Metadata version
 */
$sMetadataVersion = '1.1';
 
/**
 * Module information
 */
$aModule = array(
    'id'           => 'jxupdate',
    'title'        => 'jxUpdate - Update of Articles by CSV Imports',
    'description'  => array(
                        'de' => 'Aktualisierung der Artikeln durch CSV Importe.',
                        'en' => 'Updating the products by CSV imports.'
                        ),
    'thumbnail'    => 'jxupdate.png',
    'version'      => '0.3.1',
    'author'       => 'Joachim Barthel',
    'url'          => 'https://github.com/job963/jxUpdate',
    'email'        => 'jobarthel@gmail.com',
    'extend'       => array(
                        ),
    'files'        => array(
        'jxupdate'      => 'jxmods/jxupdate/application/controllers/admin/jxupdate.php',
                        ),
    'templates'    => array(
        'jxupdate.tpl'    => 'jxmods/jxupdate/application/views/admin/tpl/jxupdate.tpl',
                        ),
    'events'       => array(
                        ),
    'settings'     => array(
                        array(
                            'group' => 'JXUPDATE_IMPORT', 
                            'name'  => 'sJxUpdateDelimiter', 
                            'type'  => 'select', 
                            'value' => 'comma',
                            'constrains' => 'comma|semicolon|tabulator', 
                            'position' => 0 
                            ),
                        array(
                            'group' => 'JXUPDATE_IMPORT', 
                            'name'  => 'sJxUpdateEnclosure', 
                            'type'  => 'select', 
                            'value' => 'none',
                            'constrains' => 'none|quot|apos', 
                            'position' => 0 
                            ),
                        array(
                            'group' => 'JXUPDATE_IMPORT', 
                            'name'  => 'sJxUpdateIdField', 
                            'type'  => 'select', 
                            'value' => 'oxartnum',
                            'constrains' => 'oxartnum|oxmpn|oxean|oxdistean', 
                            'position' => 0 
                            ),
                        array(
                            'group' => 'JXUPDATE_IMPORT', 
                            'name'  => 'sJxUpdateCompareMode', 
                            'type'  => 'select', 
                            'value' => 'contains',
                            'constrains' => 'equal|beginswith|endswith|contains', 
                            'position' => 3 
                            ),
                        array(
                            'group' => 'JXUPDATE_IMPORT', 
                            'name'  => 'sJxUpdateIgnoreInactive', 
                            'type'  => 'bool', 
                            'value' => TRUE
                            ),
                        )
    );

?>
