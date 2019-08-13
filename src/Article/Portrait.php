<?php
class ArticlePdf extends TCPDF {
  protected $node;
  protected $url;

  function __construct($node) {
    global $base_url;
    parent::__construct('P','mm','A4');
    $this->SetAutoPageBreak(TRUE, 30);
    $this->SetMargins(10, 10, 10);
    $this->AliasNbPages();
    $this->node = $node;

    $this->url = $base_url. '/node/' . $this->node->nid;
  }

  function Header() {
    // intentionally left blank to avoid line in the header
  }

  function Footer() {
    $this->Image(dirname(__FILE__) . '/mp-logo.png', 8, 275, 50, 0, '', 'http://motionsplan.dk/');
    $this->Image(dirname(__FILE__) . '/vih_logo.jpg', 155, 270, 45, 0, '', 'http://vih.dk/');      
    $this->SetFont('Helvetica', null, 8);
    $this->setY(285);
    $this->setX(6);
    $this->MultiCell(50, 8, $this->url, 0, 'C');
    $this->SetY(-15);
    $this->SetFont('Helvetica', null, 12);
    $this->Cell(0,10, $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'C');
  }

  function AddContent() {
    parent::AddPage();
    $title = '  ' . $this->node->title;
    $body_field = field_get_items('node', $this->node, 'body');
    $body = check_markup($this->clearJavascript($body_field[0]['safe_value']), 'wysiwyg');

    $keywords = array();
    if (!empty($this->node->taxonomy)) {
      foreach ($this->node->taxonomy as $taxonomy) {
        $keywords[] = $taxonomy->name;
      }
    }

    if (!empty($this->node->field_article_image[LANGUAGE_NONE][0])) {
      $presetname = 'rotating_banner_slideshow';
      $style = image_style_load($presetname);
      $dst = image_style_path($style, $this->node->field_article_image[LANGUAGE_NONE][0]['uri']);
      if (file_exists($dst) || image_style_create_derivative($style, $this->node->field_article_image[LANGUAGE_NONE][0]['uri'], $dst)) {
        $this->Image($dst, 10, 10, 190, 0, '', 'http://motionsplan.dk/');
      }
    }
    $this->Image(dirname(__FILE__) . '/cc-by-sa_340x340.png', 180, 13, 17, 0, '');

    $this->SetY(43);
    $title_size = 15;
    $this->SetFont('Helvetica', 'B', $title_size);
    $this->SetTextColor(255, 255, 255);
    $this->Cell(0, 17, $title, null, 2, 'L', TRUE);

    $this->Ln(8);
    $this->SetFont('Helvetica', 'N', 10);
    $this->SetTextColor(0, 0, 0);
    $this->writeHTML($body, TRUE, FALSE, TRUE, FALSE, '');
  }

  protected function clearJavascript($s) {
     $do = true;
     while ($do) {
       $start = stripos($s, '<script');
       $stop = stripos($s, '</script>');
       if ((is_numeric($start)) && (is_numeric($stop))) {
         $s = substr($s, 0, $start) . substr($s, ($stop + strlen('</script>')));
       }
       else {
         $do = false;
       }
     }
     return trim($s);
   }
}
