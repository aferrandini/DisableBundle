<?php

namespace Ferrandini\Bundle\DisableBundle\Test;

use Doctrine\Common\Annotations\AnnotationReader;
use Ferrandini\Bundle\DisableBundle\Annotations\Disable;

class DisableAnnotationTest extends \PHPUnit_Framework_TestCase
{
    public function testAnnotations()
    {
        $reader = new AnnotationReader();

        $reflection = new \ReflectionClass('Ferrandini\Bundle\DisableBundle\Tests\Annotations\Fixtures\Controller\DisabledController');
        $annotations = $reader->getClassAnnotations($reflection);

        foreach ($annotations as $annotation) {
            $this->assertTrue($annotation instanceof Disable);
        }

        $reflection = new \ReflectionClass('Ferrandini\Bundle\DisableBundle\Tests\Annotations\Fixtures\Controller\DisabledMethodController');
        foreach ($reflection->getMethods() as $method) {
            $annotations = $reader->getMethodAnnotations($method);
            foreach ($annotations as $annotation) {
                $this->assertTrue($annotation instanceof Disable);
            }
        }
    }
}
