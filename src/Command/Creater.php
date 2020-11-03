<?php
namespace App\Command;

// https://symfony.com/doc/current/console.html
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use App\Common\CreateFile;
use App\Entity\Registration;
use App\Entity\Etc\Di;
use App\Entity\Etc\Webapi;
use App\Entity\Etc\Router;
use App\Entity\Controller\Controller;
use App\Entity\View\Layout;
use App\Entity\Plugin\Plugin;
use App\Entity\Api\ResetInterface;

class Creater extends Command
{
    protected static $defaultName = 'm2:creater';

    protected function configure()
    {
        $this
        // the short description shown while running "php bin/console list"
        ->setDescription('Magento2 Module creater.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to create a Magento2 Module...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        $config = [
            'license' => '/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */', // \' \"
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
                'di' => [
                    'global' => [
                        'plugin' => [
                            [
                                'observed' => 'Magento\Framework\Mview\View\StateInterface', // observed what interface or class
                                'class' => 'Plugin\Test',
                                'name' => 'Plugin_Name',
                                'sort' => 100, // plugin run sort order
                                'disabled' => 'false' // plugin disabled
                            ]
                        ],
                        'preference' => [
                            [
                                'for' => 'Cloyi\Creater\Api\Data\TestInteface',
                                'type' => 'Data\Test'
                            ],
                            [
                                'for' => 'Magento\Quote\Api\CartItemInterface',
                                'type' => 'CartItem'
                            ],
                            [
                                'for' => 'interface name',
                                'type' => 'class name'
                            ]
                        ]
                    ],
                    'adminhtml' => [],
                    'webapi_rest' => [],
                    'webapi_soap' => []
                ],
                'router' => [
                    'global' => [
                        [
                            'id' => 'route_id',
                            'front_name' => 'front_name',
                            'module' => 'Cloyi_Creater'
                        ]
                    ],
                    'frontend' => [
                        [
                            'id' => 'route_id',
                            'front_name' => 'front_name',
                            'module' => 'Cloyi_Creater',
                            'action' => 'Post\Index'
                        ],
                        [
                            'id' => 'route_id',
                            'front_name' => 'front_name',
                            'module' => 'Cloyi_Creater'
                        ]
                    ],
                    'adminhtml' => [
                        [
                            'id' => 'route_id',
                            'front_name' => 'front_name',
                            'module' => 'Cloyi_Creater',
                            'action' => 'Test\Index',
                            'resource' => 'Magento_Backend::admin'
                        ],
                        [
                            'id' => 'route_id',
                            'front_name' => 'front_name',
                            'module' => 'Cloyi_Creater'
                        ]
                    ]
                ],
                // webapi.xml info
                'webapi' => [
                    [
                        'version' => 'V1',
                        'url' => 'webapi url',
                        'method' => 'POST',
                        'service' => 'Data\TestInteface',
                        'function' => 'getCart',
                        'resources' => [
                            'self',
                            'Magento_Catalog::products'
                        ],
                        'data' => [
                            [
                                'name' => 'cartId', // function param
                                'code' => 'cart_id',
                                'force' => 'true' // true | false
                            ]
                        ]
                    ]
                ],
            ],
            // Api Code
            'webapi_reset' => [
                [
                    'interface' => 'TestInteface',
                    // default create get and set function
                    'property' => [
                        [
                            'name' => 'id', // string
                            'type' => 'string', // input type sting[], int, flat, interface
                            'return' => '$this'
                        ],
                        [
                            'name' => 'name', // string
                            'type' => 'string', // input type sting[], int, flat, interface
                            'return' => 'string'
                        ]
                    ],
                    'functions' => [
                        [
                            'name' => 'getCartInfo', // method name
                            'arguments' => [
                                [
                                    'name' => 'cartId',
                                    'type' => 'string'
                                ],
                                [
                                    'name' => 'cart',
                                    'type' => '\Magento\Quote\Api\Data\CartItemInterface' // string, string[], float, int, Interface, Interface[]
                                ]
                            ],
                            'return' => '\Magento\Quote\Api\Data\CartItemInterface[]',
                            'throws' => [
                                'Exception'
                            ]
                        ]
                    ]
                ],
                [
                    'interface' => 'Data\TestInteface',
                    // default create get and set function
                    'property' => [
                        [
                            'name' => 'id', // string
                            'type' => 'string' // input type sting[], int, flat, interface
                        ],
                        [
                            'name' => 'name', // string
                            'type' => 'string' // input type sting[], int, flat, interface
                        ]
                    ],
                    'functions' => [
                        [
                            'name' => 'method_name1', // method name
                            'arguments' => [
                                [
                                    'name' => 'argumentN',
                                    'type' => 'argumentT' // string, string[], float, int, Interface, Interface[]
                                ]
                            ],
                            'return' => 'string'
                        ]
                    ]
                ],
            ],
            'view' => [
                'global' => [],
                'frontend' => [
                    'layout' => [
                        [
                            'id' => 'route_id',
                            'action' => 'Post\Index',
                            'content' => 'grid', // ui_component or block
                        ]
                    ],
                    'ui_component' => []
                ],
                'adminhtml' => [
                    'layout' => [
                        [
                            'id' => 'route_id',
                            'action' => 'Post\Index',
                            'content' => 'grid', // ui_component or block
                        ]
                    ],
                    'ui_component' => []
                ]
            ]
        ];
        // $createFile = new CreateFile($config);
        // $createFile->create('registration.php', 'test');

        new Registration($config);

        new Webapi($config);

        new Di($config);

        new Router($config);

        new Controller($config);

        new Layout($config);

        new Plugin($config);

        $res = new ResetInterface($config);
        $res->createInterface();







        // ... put here the code to run in your command

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;
    }
}