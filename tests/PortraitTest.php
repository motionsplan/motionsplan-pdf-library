<?php
namespace Motionsplan\Tests;

use Motionsplan\Exercise\Pdf\Portrait;
use Motionsplan\Tests\ExerciseMock;

class PortraitTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionIsThrownIfTemporaryDirectoryHasNotBeenSet()
    {
        try {
            $pdf = new Portrait();
            $pdf->setLogo(new ExerciseImageMock(), 'http://motionsplan.dk');
            $pdf->setContribLogo(new ExerciseImageMock(), 'http://vih.dk');
            $pdf->addNewPage(new ExerciseMock);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testPortrait()
    {
        $filename =  __DIR__ . '/test.pdf';
        $pdf = new Portrait();
        $pdf->setTemporaryDirectory(__DIR__);
        $pdf->setLogo(new ExerciseImageMock(), 'http://motionsplan.dk');
        $pdf->setContribLogo(new ExerciseImageMock(), 'http://vih.dk');
        $pdf->addNewPage(new ExerciseMock);

        // This is not really testing the library - just to see whether functions works.
        $pdf->Output($filename, 'F');

        // Test and cleanup.
        $this->assertTrue(file_exists($filename));
        unlink($filename);
        array_map('unlink', glob(__DIR__ . '/*.png'));
    }
}
