<?php
namespace Motionsplan\Workout;

use Motionsplan\Workout\WorkoutInterface;
use Dompdf\Dompdf;

class Html
{
    protected $html;
    protected $workout;

    public function __construct(WorkoutInterface $workout)
    {
        $this->workout = $workout;
        $loader = new \Twig_Loader_Filesystem(__DIR__);
        $twig = new \Twig_Environment($loader, array('debug' => true));
        $twig->addExtension(new \Twig_Extension_Debug());
        $template = $twig->loadTemplate('program.html');
        $this->html = $template->render(array(
            'warmup_exercises' => $workout->getWarmupExercises(),
            'exercises' => $workout->getExercises()
        ));
    }

    public function getHtml()
    {
        return $this->html;
    }

    public function getPdf()
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($this->html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->output();
    }
}
