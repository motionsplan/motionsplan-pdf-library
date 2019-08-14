<?php
namespace Motionsplan\Workout;

use Motionsplan\Exercise\ExerciseInterface;

interface WorkoutInterface
{
    public function getTitle();

    public function getIntroduction();

    public function getWarmupExercises();

    public function getExercises();
}
