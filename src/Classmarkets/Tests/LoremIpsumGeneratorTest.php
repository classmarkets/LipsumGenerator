<?php

namespace Classmarkets\Tests;

use Classmarkets\LoremIpsumGenerator as Generator;

class LoremIpsumGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function firewall()
    {
        $generator = new Generator();
        $this->assertEquals('lorem ipsum.',   $generator->getContent(2, Generator::FORMAT_PLAIN, true));
        $this->assertEquals("\tlorem ipsum.", $generator->getContent(2, Generator::FORMAT_TEXT, true));
        $this->assertEquals("<p>\n    lorem ipsum.\n</p>", $generator->getContent(2, Generator::FORMAT_HTML, true));
    }

    /** @test */
    public function testNumberOfWords()
    {
        $generator  = new Generator();
        $lipsumText = $generator->getContent(114, 'plain', true);
        $this->assertEquals(114, count(preg_split('/\s+/', $lipsumText)));
    }
}
