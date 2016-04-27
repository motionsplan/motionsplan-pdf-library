<?php
namespace Motionsplan\Exercise;

interface ExerciseInterface
{
    public function getTitle();

    public function getCues();

    public function getIntroduction();

    public function getDescription();

    public function getUrl();

    /**
     * return array with ImageInterface[]
     */
    public function getImages();

    public function getBarCode();
}
