<?php

namespace Tests;

use Fixtures\Prophecy\EmptyClass;
use Fixtures\Prophecy\WithArguments;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophet;

class ProphetTest extends TestCase
{
    /**
     * @test
     */
    public function it_matches_doubled_arguments()
    {
        $class = new \ReflectionClass('Fixtures\Prophecy\SpecialMethods');

        $prophet = new Prophet();
        $double = $prophet->prophesize();
        $double->willExtend(WithArguments::class);
        $doubleArg1 = $prophet->prophesize();
        $doubleArg1->willExtend(EmptyClass::class);
        $doubleArg2 = $prophet->prophesize();
        $doubleArg2->willExtend(EmptyClass::class);

        $double->methodWithoutTypeHints($doubleArg1)->willReturn('A');
        $double->methodWithoutTypeHints($doubleArg2)->willReturn('B');
        $double->methodWithoutTypeHints($doubleArg1)->willReturn('A');

        $object = $double->reveal();

        $returnVal = $object->methodWithoutTypeHints($doubleArg2);

        $this->assertEquals($returnVal, 'B');

        $double->methodWithoutTypeHints($doubleArg1)->shouldNotHaveBeenCalled();

        $prophet->checkPredictions();
    }
}
