<?php

namespace PlugRoute\Test;

use PHPUnit\Framework\TestCase;
use PlugRoute\PlugRoute;
use PlugRoute\Route;
use PlugRoute\RouteType;
use PlugRoute\Http\Request;
use PlugRoute\Test\Mock\FirstMiddlewareMock;
use PlugRoute\Test\Mock\ObjectMock;
use PlugRoute\Test\Mock\RequestMock;
use PlugRoute\Test\Mock\SecondMiddlewareMock;

class PlugRouteTest extends TestCase
{
    private PlugRoute $plugRoute;

    protected function setUp(): void
    {
        $_GET = [];
        $_POST = [];
        $_COOKIE = [];
        $_ENV = [];
        $_FILES = [];
        $_REQUEST = [];
        $_SESSION = [];

        $this->setPlugRouteInstance();
    }

    private function runRoutes()
    {
        $this->plugRoute->run();
    }

    public function testSimpleCallbackGetRoute()
    {
        $this->plugRoute->get('/')
            ->callback(function () {
                return 'working';
            });
        $this->runRoutes();

        $this->expectOutputString('working');
    }

    public function testSimpleClassGetRoute()
    {
        $this->plugRoute->get('/')
            ->controller(ObjectMock::class, 'run');
        $this->runRoutes();

        $this->expectOutputString('working method');
    }

    public function testDynamicCallbackGetRoute()
    {
        $this->setPlugRouteInstance('/people/50');

        $this->plugRoute->get('/people/{id}')
            ->callback(function (Request $request) {
                return $request->parameter('id');
            })
            ->rule('id', '\d+')
            ->name('teste')
        ;
        $this->runRoutes();

        $this->expectOutputString(50);
    }

    public function testAnyCallbackGetRoute()
    {
        $this->plugRoute->any('/')
            ->callback(function () {
                return 'working';
            });
        $this->runRoutes();

        $this->expectOutputString('working');
    }

    public function testExceptionRoute()
    {
        $this->expectException(\Exception::class);

        $this->plugRoute->get('/test')
            ->callback(function () {
                return 'working';
            });
        $this->runRoutes();
    }

    public function testFallbackRoute()
    {
        $this->plugRoute->get('/test')
            ->callback(function () {
                return 'working';
            });

        $this->plugRoute->fallback()
            ->callback(function () {
                return 'Route did not define';
            });

        $this->expectOutputString('Route did not define');
        $this->runRoutes();
    }

    public function testExceptionClassNotDefinedRoute()
    {
        $this->expectException(\Exception::class);

        $this->plugRoute->get('/')
            ->controller('', '');
        $this->runRoutes();
    }

    public function testExceptionMethodNotDefinedRoute()
    {
        $this->expectException(\Exception::class);

        $this->plugRoute->get('/')
            ->controller(ObjectMock::class, '');
        $this->runRoutes();
    }

    public function testGenericDynamicRoute()
    {
        $this->setPlugRouteInstance('/people/50');

        $this->plugRoute->get('/people/{id}')
            ->callback(function (Request $request) {
                return $request->parameter('id');
            });
        $this->runRoutes();

        $this->expectOutputString(50);
    }

    public function testOptionalDynamicRoute()
    {
        $this->setPlugRouteInstance('/posts/1/comments/');

        $this->plugRoute->get('/posts/{id}/comments/{commentId?}')
            ->callback(function (Request $request) {
                return $request->parameter('commentId');
            });
        $this->runRoutes();

        $this->expectOutputString('');
    }

    public function testMatchRoute()
    {
        $this->plugRoute->match('/', RouteType::GET, RouteType::POST)
            ->controller(ObjectMock::class, 'run');
        $this->runRoutes();

        $this->expectOutputString('working method');
    }

    public function testMiddleware()
    {
        $this->plugRoute->middleware(FirstMiddlewareMock::class)
            ->middleware(SecondMiddlewareMock::class)
            ->group(function ($route) {
                $route->get('/')
                    ->callback(function (Request $request) {
                        $messages = [
                            'Passed in firstMiddleware: '.$request->parameter('firstMiddleware'),
                            'Passed in firstMiddleware: '.$request->parameter('firstMiddleware')
                        ];
                        return implode('; ', $messages);
                    });
            });
        $this->runRoutes();

        $expected = [
            'Passed in firstMiddleware: 1',
            'Passed in firstMiddleware: 1'
        ];

        $this->expectOutputString(implode('; ', $expected));
    }

    public function testMiddlewareException()
    {
        $this->expectException(\Exception::class);

        $this->plugRoute
            ->middleware(\Reflection::class)
            ->group(function ($route) {
                $route->get('/')
                    ->callback(function (Request $request) {});
            });
        $this->runRoutes();
    }

    public function testPrefix()
    {
        $expected = '/sports/soccer/teams';
        $this->setPlugRouteInstance($expected);
        $this->plugRoute->prefix('/sports', '/soccer')
            ->group(function ($route) {
                $route->get('/teams')
                    ->callback(function (Request $request) {
                        return $request->getUrl();
                    });
            });
        $this->runRoutes();
        $this->expectOutputString($expected);
    }

    public function testNamespace()
    {
        $this->plugRoute->namespace('PlugRoute', '\\Test')
            ->namespace('\\Mock')
            ->group(function ($route) {
                $route->get('/')
                    ->controller('\\ObjectMock', 'run');
            });
        $this->runRoutes();
        $this->expectOutputString('working method');
    }

    /**
     * @runInSeparateProcess
     **/
    public function testRedirect()
    {
        $this->plugRoute->redirect('/', 'https://github.com/erandirjunior/plug-route');
        $this->runRoutes();

		$this->assertContains(
			'Location: https://github.com/erandirjunior/plug-route',
            ['Location: https://github.com/erandirjunior/plug-route']
		);
    }

    /**
     * @runInSeparateProcess
     */
    public function testRedirectToRoute()
    {
        $this->plugRoute->get('/github')
            ->callback(function (Request $request) {
               $request->redirect('https://github.com/erandirjunior/plug-route');
            })
            ->name('github');

        $this->plugRoute->get('/')
            ->callback(function (Request $request) {
               $request->redirectToRoute('github');
            });

        $this->runRoutes();

		$this->assertContains(
			'Location: https://github.com/erandirjunior/plug-route',
            ['Location: https://github.com/erandirjunior/plug-route']
		);
    }

    /**
     * @runInSeparateProcess
     */
    public function testExceptionRedirectToRoute()
    {
        $this->expectException(\Exception::class);
        $this->plugRoute->get('/')
            ->callback(function (Request $request) {
               $request->redirectToRoute('github');
            });

        $this->runRoutes();

		$this->assertContains(
			'Location: https://github.com/erandirjunior/plug-route',
            ['Location: https://github.com/erandirjunior/plug-route']
		);
    }

    public function testInject()
    {
        $this->setPlugRouteInstance('/people/10/posts/23');
        $this->plugRoute->get('/people/{id}/posts/{postId}')
            ->controller(ObjectMock::class, 'injetMethod');
        $this->runRoutes();

        $this->expectOutputString('user id: 10, post id: 23');
    }

    public function testRequestMock()
    {
        $this->setPlugRouteInstance('/people/5');
        $this->plugRoute->get('/people/{id}')
            ->callback(function (RequestMock $requestMock) {
                return $requestMock->parameter('id');
            });
        $this->runRoutes();

        $this->expectOutputString(5);
    }

    private function setPlugRouteInstance(string $uri = '/', string $method = 'GET'): void
    {
        $_SERVER['REQUEST_URI'] = $uri;
        $_SERVER['REQUEST_METHOD'] = $method;
        $this->plugRoute = new PlugRoute();
    }
}