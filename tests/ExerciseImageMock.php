<?php
namespace Motionsplan\Tests;

use Motionsplan\Exercise\ExerciseImageInterface;

class ExerciseImageMock implements ExerciseImageInterface
{
    public function getPath()
    {
        return __DIR__ . '/support/pic-800x600.png';
    }

    public function getOrientation()
    {
        return 'portrait';
    }
}
