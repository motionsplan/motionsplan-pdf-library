<?php
namespace Motionsplan\Tests;

use Motionsplan\ExerciseProgram\Compact;
use Motionsplan\Tests\ExerciseMock;

class ProgramCompactTest extends \PHPUnit_Framework_TestCase
{
    public function testExceptionIsThrownIfTemporaryDirectoryHasNotBeenSet()
    {
        try {
            $pdf = new Compact();
            $pdf->setLogo(new ExerciseImageMock(), 'http://motionsplan.dk');
            $pdf->setContribLogo(new ExerciseImageMock(), 'http://vih.dk');
            // $pdf->addNewPage(new ExerciseMock);
        } catch (\Exception $e) {
            $this->assertTrue(true);
        }
    }

    public function testCompactTrainingProgram()
    {
        $filename =  __DIR__ . '/compact-program.pdf';
        $pdf = new Compact();
        //$pdf->setTemporaryDirectory(__DIR__);
        $pdf->setLogo(new ExerciseImageMock(), 'http://motionsplan.dk');
        $pdf->setContribLogo(new ExerciseImageMock(), 'http://vih.dk');
        
        // Metadata
        $pdf->SetTitle('My cool training program');
        $pdf->SetSubject('My cool subject');
        $pdf->SetAuthor('Motionsplan.dk');
        $pdf->SetAutoPageBreak(false);
        
        // Content
        $pdf->addTitle('My beautiful title for trainingprogram');
        $pdf->addDescription('<p>My <b>description</b> in html</p>');
        // This is not really testing the library - just to see whether functions works.

        $pdf->addExercise(new ExerciseMock);

        // Add the page
        $pdf->AddNewPage();


        $pdf->Output($filename, 'F');

        // Test and cleanup.
        $this->assertTrue(file_exists($filename));
        //unlink($filename);
        array_map('unlink', glob(__DIR__ . '/*.png'));
    }
}



/*
function exerciseprogram_print_compact_pdf($node) {
  global $base_path;
  require_once libraries_get_path('fpdf') . '/fpdf.php';

  // HACK START included because Article PDF uses TCPDF
  require_once libraries_get_path('tcpdf') . '/tcpdf.php';
  // HACK END
  ctools_include('motionsplan_exercise_pdf.pdf', 'motionsplan_exercise_pdf', '.');

  $title = utf8_decode($node->title);
  $description = utf8_decode(strip_tags($node->body[LANGUAGE_NONE][0]['safe_value']));
  // $description = check_markup($description, 'filtered_html');

  $pdf = new ExerciseProgramCompactPdf();
  $pdf->SetTitle($title);
  $pdf->SetSubject('');
  $pdf->SetAuthor('Motionsplan.dk');
  $pdf->SetAutoPageBreak(FALSE);

  $pdf->AddPage();
  $pdf->SetFont('Helvetica', 'B', 20);
  $pdf->Cell(0, 10, $title, null, 2, 'L', FALSE);
  $pdf->Image(dirname(__FILE__) . '/mp-logo.png', 150, 8, 50, 0, '', 'http://motionsplan.dk/');
  $pdf->setTextColor(0, 0, 0);

  $pdf->SetFont('Helvetica', null, 10);
  $pdf->MultiCell(180, 6, $description, 0);
  // $pdf->writeHTMLCell(180, 6, $pdf->GetX(), $pdf->GetY(), $description, 0); 
  $pdf->SetY($pdf->GetY()+2);

  // Table starts.
  $pdf->SetWidths(array(50, 70, 70));
  $pdf->SetFont('Helvetica', 'B', 10);
  $pdf->Row(array(utf8_decode('Øvelse'), utf8_decode('Beskrivelse'), utf8_decode('Kommentar')));
  $pdf->SetFont('Helvetica', null, 10);

  $style = 'square_thumbnail';
  $style_array = image_style_load($style);

  foreach ($node->field_program_exercises[LANGUAGE_NONE] as $entity) {
    $e = node_load($entity['target_id']);

    $imgs = array();
    foreach ($e->field_exercise_images[LANGUAGE_NONE] as $image) {
      $dst = image_style_path($style, $image['uri']);
      if (file_exists($dst) || image_style_create_derivative($style_array, $image['uri'], $dst)) {
        $picture_filename = drupal_realpath(image_style_path($style, $image['uri']));
        if (is_dir($picture_filename)) {
          continue;
        }
        $imgs[] = $picture_filename;
      }
    }
    if (sizeof($imgs) > 3) {
      $tmp_imgs = array();
      $tmp_imgs[] = $imgs[0];
      $tmp_imgs[] = $imgs[floor(sizeof($imgs) / 2)];
      $tmp_imgs[] = $imgs[sizeof($imgs) - 1];
      $imgs = $tmp_imgs;
    }

    $pdf->Row(
      array(
        utf8_decode($e->title), 
        utf8_decode($e->field_exercise_intro[LANGUAGE_NONE][0]['value']), 
        ''
      ), 
      $imgs
    ); 
  }

  $pdf->Output($title . '.pdf', 'I');
}
*/
