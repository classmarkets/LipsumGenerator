<?php

namespace Classmarkets\Tests;

use Classmarkets\LipsumGenerator as Generator;

class LipsumGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function firewall()
    {
        $generator = new Generator();
        $this->assertEquals('Lorem ipsum.',   $generator->getContent(2, Generator::FORMAT_PLAIN, true));
        $this->assertEquals("\tLorem ipsum.", $generator->getContent(2, Generator::FORMAT_TEXT, true));
        $this->assertEquals("<p>\n    Lorem ipsum.\n</p>", $generator->getContent(2, Generator::FORMAT_HTML, true));
    }

    /** @test */
    public function testNumberOfWords()
    {
        $generator  = new Generator();
        $lipsumText = $generator->getContent(114, 'plain', true);
        $this->assertEquals(114, count(preg_split('/\s+/', $lipsumText)));
    }
}
