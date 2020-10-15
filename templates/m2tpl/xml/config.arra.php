<?php
$config = [
    'license' => 'string', // \' \"
    // create module.xml info
    // https://devdocs.magento.com/guides/v2.4/config-guide/config/config-files.html?itm_source=devdocs&itm_medium=search_page&itm_campaign=federated_search&itm_term=cronjob.xml
    'module' => [
        'vendor' => 'Cloyi',
        'module' => 'Creater',
        'description' => 'Module Description',
        'depends' => [
            'Module_Name',
            'Magento_Cms'
        ]
    ],
    'etc' => [
        // create di.xml info
        // https://devdocs.magento.com/guides/v2.4/extension-dev-guide/build/di-xml-file.html?itm_source=devdocs&itm_medium=search_page&itm_campaign=federated_search&itm_term=di.xml
        'di' => [
            'global' => [
                'plugins' => [
                    [
                        'observed' => 'Magento\Framework\Mview\View\StateInterface', // observed what interface or class
                        'class' => 'Cloyi\Creater\Plugin\Test',
                        'name' => 'Plugin_Name',
                        'sort' => 100, // plugin run sort order
                        'disabled' => false // plugin disabled
                    ]
                ],
                'preference' => [
                    [
                        'for' => 'interface name',
                        'type' => 'class name'
                    ]
                ]
            ],
            'frontend' => [],
            'adminhtml' => [],
            'webapi_rest' => [],
            'webapi_soap' => []
        ],
        'events' => [
            'global' => [],
            'frontend' => [],
            'adminhtml' => [],
            'webapi_reset' => [],
            'webapi_soap' => []
        ],
        // crontab.xml
        // https://devdocs.magento.com/guides/v2.4/config-guide/cron/custom-cron-tut.html?itm_source=devdocs&itm_medium=search_page&itm_campaign=federated_search&itm_term=cronjob.xml
        'crontab' => [
            [
                'group' => 'default', // if change mean create custom group, can custom
                'job' => [
                    [
                        'name' => 'cronjob_name',
                        'instance' => 'Magento\SampleMinimal\Cron\Test',
                        'method' => 'execute',
                        'schedule' => [
                            'type' => 'fixed', // fixed or daymic use class
                            'value' => '* * * * *' // hard code or class name
                        ]
                    ]
                ]
            ]
        ],
        // webapi.xml info
        'webapi' => [
            [
                'version' => 'V1',
                'url' => 'webapi url',
                'method' => 'POST',
                'service' => 'interface name',
                'function' => 'request function',
                'resources' => [
                    'self',
                    'Magento_Catalog::products'
                ],
                'data' => [
                    [
                        'name' => 'cart_id', // function param
                        'force' => 'true' // true | false
                    ]
                ]
            ]
        ],
        // webapi_async.xml
        // webapi_rest
        // webape_soap
        // 
    ],
    'setup' => [
        [
            'type' => 'patch/data',
            'class' => 'class name'
        ]
    ],
    'block' => [],
    'model' => [],
    'controller' => [],
    'helper' => [],
    'observer' => [],
    'crontab' => [],
    'observer' => [],
    'plugin' => [],
    'webapi_reset' => [
        [
            // one api
            [
                [
                    'interface' => 'interface name',
                    'property' => [
                        [
                            'name' => 'name', // string
                            'type' => 'string' // input type sting[], int, flat, interface
                        ]
                    ],
                    'functions' => [
                        [
                            'method' => 'method_name', // method name
                            'arguments' => [
                                [
                                    'name' => 'argument name',
                                    'type' => 'argument type' // string, string[], float, int, Interface, Interface[]
                                ]
                            ],
                            'return' => [
                                'value' => 'value name',
                                'type' => 'return type' // $this, string, int, float, Interface[]....
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
];