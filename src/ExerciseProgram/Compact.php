<?php
namespace Motionsplan\ExerciseProgram;

use Motionsplan\Pdf\Pdf;
use Motionsplan\Exercise\ExerciseImageInterface;

class Compact extends \FPDF {
  var $widths;
  var $aligns;
  var $imgs;

    public function setLogo($image, $url)
    {
        $this->logo = $image;
        $this->url = $url;
    }

    public function setContribLogo($image, $url)
    {
        $this->contrib_logo = $image;
        $this->contrib_url = $url;
    }


  /**
   * Set the array of column width
   */
  function SetWidths($w) {
    $this->widths=$w;
  }

  /**
   * Set the array of column alignments
   */
  function SetAligns($a) {
    $this->aligns=$a;
  }

  function Row($data, $pics = array()) {
    // Calculate the height of the row.
    $pic_width = 15;
    $pic_height = 15;
    $nb=0;
    for ($i = 0; $i < count($data); $i++) {
      $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
    }
    $h = 5 * $nb;

    if ($first_col = $this->NbLines($this->widths[$i], $data[0]) * 5 + $pic_height >= $h AND !empty($pics)) {
      $h = $first_col * 5 + $pic_height + 6;
    }

    // Issue a page break first if needed.
    $this->CheckPageBreak($h);
    // Draw the cells of the row.
    for ($i = 0; $i < count($data); $i++) {
      $w=$this->widths[$i];
      $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
      // Save the current position.
      $x = $this->GetX();
      $y = $this->GetY();
      // Draw the border.
      $this->Rect($x, $y, $w, $h);
      // Print the text.
      $this->MultiCell($w, 5, $data[$i], 0, $a);

      $calc_x = $x + 1;
      $calc_y = $this->GetY();
      if (!empty($pics)) {
        if ($i == 0) {
          foreach ($pics as $pic) {
            if (file_exists($pic)) {
              $this->Image($pic, $calc_x, $calc_y, $pic_width, $pic_height);
              $calc_x += $pic_width;
            }
          }
        }
      }
      // Put the position to the right of the cell.
      $this->SetXY($x+$w,$y);
    }

    // Go to the next line.
    $this->Ln($h);
  }

  /**
   * If the height h would cause an overflow, add a new page immediately
   *
   * @param integer $h Height
   *
   * @return void
   */
  function CheckPageBreak($h) {
    if ($this->GetY() + $h > $this->PageBreakTrigger) {
      $this->AddPage($this->CurOrientation);
    }
  }

  /**
   * Computes the number of lines a MultiCell of width w will take
   *
   * @param integer $w   Width
   * @param string  $txt Text
   */
  function NbLines($w, $txt) {
    $cw=&$this->CurrentFont['cw'];
    if ($w==0) {
      $w=$this->w-$this->rMargin-$this->x;
    }
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if ($nb>0 and $s[$nb-1]=="\n") {
      $nb--;
    }
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb) {
      $c=$s[$i];
      if ($c=="\n") {
        $i++;
        $sep=-1;
        $j=$i;
        $l=0;
        $nl++;
        continue;
      }
      if ($c==' ') {
        $sep=$i;
      }
      $l+=$cw[$c];
      if ($l>$wmax) {
        if ($sep==-1) {
          if ($i==$j) {
            $i++;
          }
        }
        else {
          $i=$sep+1;
        }
        $sep=-1;
        $j=$i;
        $l=0;
        $nl++;
      }
      else {
        $i++;
      }
    }
    return $nl;
  }
}

