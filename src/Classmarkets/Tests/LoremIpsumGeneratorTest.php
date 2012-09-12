<?php

namespace Classmarkets\Tests;

use Classmarkets\LoremIpsumGenerator;

class LoremIpsumGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function firewall()
    {
        $generator = new LoremIpsumGenerator(2);
        $this->assertEquals('lorem ipsum. ', $generator->getContent(2, 'plain', true));
    }
}
