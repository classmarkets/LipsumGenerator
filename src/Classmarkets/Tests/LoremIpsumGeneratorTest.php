<?php

namespace Classmarkets\Tests;

use Classmarkets\LoremIpsumGenerator;

class LoremIpsumGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function firewall()
    {
        $generator = new LoremIpsumGenerator();
        $this->assertEquals('lorem ipsum.', $generator->getContent(2, 'plain', true));
        $this->assertEquals("\tlorem ipsum.", $generator->getContent(2, 'txt', true));
    }

    /** @test */
    public function testNumberOfWords()
    {
        $generator  = new LoremIpsumGenerator();
        $lipsumText = $generator->getContent(114, 'plain', true);
        $this->assertEquals(114, count(preg_split('/\s+/', $lipsumText)));
    }
}
