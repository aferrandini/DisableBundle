<?php
namespace Ferrandini\Bundle\DisableBundle\Test;

use Ferrandini\Bundle\DisableBundle\Annotations\Disable;
use Symfony\Bundle\FrameworkBundle\Tests\Functional\WebTestCase;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;

class DisableTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return mixed
     */
    private function getMockedRouter()
    {
        return $this
            ->getMockBuilder('Symfony\Component\Routing\RouterInterface')
            ->setMethods(array('generate', 'match', 'getRouteCollection', 'getContext', 'setContext'))
            ->getMock()
        ;
    }

    /**
     * Tests returns a callable which returns a Response
     * with default message and statusCode
     */
    public function testDefault()
    {
        $router = $this->getMockedRouter();

        $disable = new Disable();
        $response = $disable->getResponse($router);

        $this->assertTrue(is_callable($response));

        $response = call_user_func($response);

        $this->assertTrue($response instanceof Response);
        $this->assertEquals($response->getStatusCode(), $disable->getStatusCode());
        $this->assertEquals($response->getContent(), $disable->getMessage());
    }

    /**
     * Tests Router services is called and returns a
     * RedirectResponse to the URL generated
     */
    public function testRouterCall()
    {
        $route_name = 'test_route';
        $route_url  = '/test';

        $router = $this->getMockedRouter();
        $router->expects($this->once())
            ->method('generate')
            ->with($this->equalTo($route_name))
            ->will($this->returnValue($route_url))
        ;

        $disable = new Disable();
        $disable->redirect = $route_name;
        $response = $disable->getResponse($router);

        $this->assertTrue(is_callable($response));
        $response = call_user_func($response);

        $this->assertTrue($response instanceof RedirectResponse);
        $this->assertEquals($response->getTargetUrl(), $route_url);
    }

    /**
     * Tests until now returns Response
     */
    public function testUntil()
    {
        $router = $this->getMockedRouter();

        $until = new \DateTime();
        $until->add(new \DateInterval('P1D'));

        $disable = new Disable();
        $disable->until = $until->format("Y-m-d");

        $response = $disable->getResponse($router);
        $this->assertTrue(is_callable($response));

        $response = call_user_func($response);

        $this->assertTrue($response instanceof Response);
        $this->assertEquals($response->getStatusCode(), $disable->getStatusCode());
        $this->assertEquals($response->getContent(), $disable->getMessage());

        $until = new \DateTime();
        $until->sub(new \DateInterval('P1D'));

        $disable = new Disable();
        $disable->until = $until->format("Y-m-d");

        $response = $disable->getResponse($router);
        $this->assertNull($response);
    }

    /**
     * Tests aftern now returns Response
     */
    public function testAfter()
    {
        $router = $this->getMockedRouter();

        $after = new \DateTime();
        $after->sub(new \DateInterval('P1D'));

        $disable = new Disable();
        $disable->after = $after->format("Y-m-d");

        $response = $disable->getResponse($router);
        $this->assertTrue(is_callable($response));

        $response = call_user_func($response);

        $this->assertTrue($response instanceof Response);
        $this->assertEquals($response->getStatusCode(), $disable->getStatusCode());
        $this->assertEquals($response->getContent(), $disable->getMessage());

        $after = new \DateTime();
        $after->add(new \DateInterval('P1D'));

        $disable = new Disable();
        $disable->after = $after->format("Y-m-d");

        $response = $disable->getResponse($router);
        $this->assertNull($response);
    }

    /**
     * Tests all cases of until and after together
     */
    public function testUntilAndAfter()
    {
        $router = $this->getMockedRouter();

        /**
         * tests until is true and after is false
         */
        $until = new \DateTime();
        $until->add(new \DateInterval('P1D'));
        $after = new \DateTime();
        $after->add(new \DateInterval('P2D'));

        $disable = new Disable();
        $disable->until = $until->format("Y-m-d");
        $disable->after = $after->format("Y-m-d");

        $response = $disable->getResponse($router);
        $this->assertTrue(is_callable($response));
        $response = call_user_func($response);

        $this->assertTrue($response instanceof Response);
        $this->assertEquals($response->getStatusCode(), $disable->getStatusCode());
        $this->assertEquals($response->getContent(), $disable->getMessage());

        /**
         * tests until is false and after is true
         */
        $until = new \DateTime();
        $until->sub(new \DateInterval('P2D'));
        $after = new \DateTime();
        $after->sub(new \DateInterval('P1D'));

        $disable = new Disable();
        $disable->until = $until->format("Y-m-d");
        $disable->after = $after->format("Y-m-d");

        $response = $disable->getResponse($router);
        $this->assertTrue(is_callable($response));
        $response = call_user_func($response);

        $this->assertTrue($response instanceof Response);
        $this->assertEquals($response->getStatusCode(), $disable->getStatusCode());
        $this->assertEquals($response->getContent(), $disable->getMessage());

        /**
         * test untils is false and after is false
         */
        $until = new \DateTime();
        $until->sub(new \DateInterval('P1D'));
        $after = new \DateTime();
        $after->add(new \DateInterval('P1D'));

        $disable = new Disable();
        $disable->until = $until->format("Y-m-d");
        $disable->after = $after->format("Y-m-d");

        $response = $disable->getResponse($router);
        $this->assertNull($response);
    }
}