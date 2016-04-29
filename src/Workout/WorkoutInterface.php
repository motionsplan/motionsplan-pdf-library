<?php
namespace Motionsplan\Workout;

use Motionsplan\Exercise\ExerciseInterface;

interface WorkoutInterface
{
    public function getIntroduction();

    public function getExercises();
}
