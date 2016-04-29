<?php
namespace Motionsplan\Tests;

use Motionsplan\Workout\WorkoutInterface;
use Motionsplan\Tests\ExerciseMock;

class WorkoutMock implements WorkoutInterface
{
    public function getTitle()
    {
        return 'My title';
    }

    public function getIntroduction()
    {
        return 'My introduction';
    }

    public function getWarmupExercises()
    {
        return array(
            new ExerciseMock(),
            new ExerciseMock(),
            new ExerciseMock(),
            new ExerciseMock(),
            new ExerciseMock(),
            new ExerciseMock(),
        );
    }

    public function getExercises()
    {
        return array(
            new ExerciseMock(),
            new ExerciseMock(),
            new ExerciseMock(),
            new ExerciseMock(),
            new ExerciseMock(),
            new ExerciseMock(),
            new ExerciseMock(),
            new ExerciseMock()
        );
    }
}
