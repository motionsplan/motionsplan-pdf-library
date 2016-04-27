<?php
namespace Motionsplan\Tests;

use Motionsplan\Exercise\ExerciseInterface;
use Motionsplan\Tests\ExerciseImageMock;

class ExerciseMock implements ExerciseInterface
{
    public function getTitle()
    {
        return 'My Title';
    }

    public function getCues()
    {
        return 'My cues';
    }

    public function getIntroduction()
    {
        return 'My introduction';
    }

    public function getDescription()
    {
        return 'My description';
    }

    public function getUrl()
    {
        return 'http://motionsplan.dk';
    }

    /**
     * return array with ImageInterface[]
     */
    public function getImages()
    {
        return array(
            new ExerciseImageMock(),
            new ExerciseImageMock()
        );
    }

    public function getBarCode()
    {
        return null;
    }
}
