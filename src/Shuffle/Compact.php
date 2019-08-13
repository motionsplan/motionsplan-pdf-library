<?php
/**
 * @file
 * Code for the PDF's
 */

class ShuffleWorkoutPdfCompact extends TCPDF {
  protected $node;
  protected $url;
  protected $font = 'Helvetica';

  function __construct() {
    global $base_url;
    parent::__construct('P','mm','A4');
    $this->SetAutoPageBreak(TRUE, 30);
    $this->SetMargins(10, 10, 10);
    $this->AliasNbPages();
    $this->SetY(290);
    $this->SetX(0);
  }

  function Header() {
    // intentionally left blank to avoid line in the header.
  }

  function Footer() {
    $this->Image(dirname(__FILE__) . '/mp-logo.png', 8, 275, 50, 0, '', 'http://motionsplan.dk/');
    $this->Image(dirname(__FILE__) . '/vih_logo.jpg', 155, 270, 45, 0, '', 'http://vih.dk/');      
  }

  function addTerm($term) {
    $title_size = 15;
    $this->SetFont('Helvetica', 'B', $title_size);
    $this->SetTextColor(255, 255, 255);
    $this->SetFillColorArray(hex2rgb($term->field_category_color[LANGUAGE_NONE][0]['rgb']));
    $this->Cell(0, 25, $term->name, null, 2, 'C', TRUE);
    $this->SetY($this->GetY() + 5);
    // strip_tags($term->description);
  }
}

class ShuffleWorkoutPdf extends TCPDF {
  protected $node;
  protected $url;
  protected $font = 'Helvetica';

  function __construct() {
    global $base_url;
    parent::__construct('P','mm','A4');
    $this->SetAutoPageBreak(TRUE, 30);
    $this->SetMargins(10, 10, 10);
    $this->AliasNbPages();
    $this->SetY(290);
    $this->SetX(0);
  }

  function Header() {
    // intentionally left blank to avoid line in the header.
  }

  function Footer() {
    $this->Image(dirname(__FILE__) . '/mp-logo.png', 8, 275, 50, 0, '', 'http://motionsplan.dk/');
    $this->Image(dirname(__FILE__) . '/vih_logo.jpg', 155, 270, 45, 0, '', 'http://vih.dk/');      
  }

  function addTerm($term) {
    $this->AddPage();
    $title_size = 30;
    $this->SetFont($this->font, 'B', $title_size);
    $this->SetTextColor(255, 255, 255);
    $this->SetFillColorArray(hex2rgb($term->field_category_color[LANGUAGE_NONE][0]['rgb']));
    $this->Cell(0, 60, $term->name, null, 2, 'C', TRUE);
    $this->SetY($this->GetY() + 5);
    $this->SetTextColor(0, 0, 0);
    $this->SetFont($this->font, 'N', 12);
    $this->writeHTML($term->description, TRUE, FALSE, TRUE, FALSE, '');
  }
}
