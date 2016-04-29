<?php
namespace Motionsplan\Tests;

use Motionsplan\Workout\Html;
use Motionsplan\Tests\WorkoutMock;

class WorkoutTest extends \PHPUnit_Framework_TestCase
{
    protected $html;

    function setUp()
    {
        $this->html = new Html(new WorkoutMock);
    }

    public function testHtml()
    {
        $this->assertTrue(($this->html instanceof Html));
    }

    public function testGetHtml()
    {
        $filename = __DIR__ . '/test.html';
        $output = $this->html->getHtml();
        $this->assertTrue(is_string($output));
        file_put_contents($filename, $output);
        //unlink($filename);
    }

    public function testGetPdf()
    {
        $filename = __DIR__ . '/test.pdf';
        $output = $this->html->getPdf();
        $this->assertTrue(is_string($output->output()));
        file_put_contents($filename, $output);
        //unlink($filename);
    }
}
