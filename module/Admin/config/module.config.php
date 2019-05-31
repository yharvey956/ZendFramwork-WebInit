<?php

namespace Admin;

use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '[/[admin]]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index'
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'index' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/index[/][:action[/]]',
                            'constraints' => [
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
                            ],
                            'defaults' => [
                                'controller' => Controller\IndexController::class,
                                'action' => 'index'
                            ]
                        ]
                    ],
                ],
            ]
        ]
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/500',
        'template_map' => [
            //错误模板页面
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/500' => __DIR__ . '/../view/error/500.phtml',
            //主页的模板页面
            'view/main' => __DIR__ . '/../view/layout/main.phtml'
        ],
        'template_path_stack' => [
            __DIR__ . '/../view'
        ],
    ]
];
