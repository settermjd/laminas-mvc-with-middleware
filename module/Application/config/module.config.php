<?php

declare(strict_types=1);

namespace Application;

use Laminas\Mvc\Middleware\PipeSpec;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;
use Mezzio\Helper\ServerUrlHelper;
use Mezzio\Helper\ServerUrlMiddleware;
use Mezzio\Helper\ServerUrlMiddlewareFactory;
use Mezzio\Helper\Template\TemplateVariableContainerMiddleware;
use Mezzio\Helper\UrlHelper;
use Mezzio\Helper\UrlHelperFactory;
use Mezzio\Helper\UrlHelperMiddleware;
use Mezzio\Helper\UrlHelperMiddlewareFactory;
use Mezzio\Router\Middleware\DispatchMiddleware;
use Mezzio\Router\Middleware\DispatchMiddlewareFactory;
use Mezzio\Router\Middleware\ImplicitHeadMiddleware;
use Mezzio\Router\Middleware\ImplicitHeadMiddlewareFactory;
use Mezzio\Router\Middleware\ImplicitOptionsMiddleware;
use Mezzio\Router\Middleware\ImplicitOptionsMiddlewareFactory;
use Mezzio\Router\Middleware\MethodNotAllowedMiddleware;
use Mezzio\Router\Middleware\MethodNotAllowedMiddlewareFactory;
use Mezzio\Router\Middleware\RouteMiddleware;
use Mezzio\Router\Middleware\RouteMiddlewareFactory;
use Mezzio\Router\RouteCollector;
use Mezzio\Router\RouteCollectorFactory;
use Mezzio\Template\TemplateRendererInterface;
use Mezzio\Twig\TwigEnvironmentFactory;
use Mezzio\Twig\TwigExtension;
use Mezzio\Twig\TwigExtensionFactory;
use Mezzio\Twig\TwigRenderer;
use Mezzio\Twig\TwigRendererFactory;
use Twig\Environment;
use Twig_Environment;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'middleware' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/application/handler',
                    'defaults' => [
                        'controller' => PipeSpec::class,
                        'middleware' => Handler\HomePageHandler::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'templates' => [
        'paths' => [
            'app'    => [__DIR__ . '/../view/handlers/app'],
            'layout'    => [__DIR__ . '/../view/handlers/layout'],
        ],
    ],
    'service_manager' => [
        'aliases'   => [
            TemplateRendererInterface::class => TwigRenderer::class,
            Twig_Environment::class          => Environment::class,
        ],
        'factories' => [
            DispatchMiddleware::class => DispatchMiddlewareFactory::class,
            Environment::class => TwigEnvironmentFactory::class,
            Handler\HomePageHandler::class => Handler\HomePageHandlerFactory::class,
            TwigExtension::class => TwigExtensionFactory::class,
            TwigRenderer::class  => TwigRendererFactory::class,
        ],
        'invokables' => [
            ServerUrlHelper::class => ServerUrlHelper::class,
            TemplateVariableContainerMiddleware::class => TemplateVariableContainerMiddleware::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
