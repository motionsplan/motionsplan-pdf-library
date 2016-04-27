<?php
/**
 * @file
 * Layout for default A4
 */

namespace Motionsplan\Exercise\Pdf;

use Motionsplan\Pdf\Pdf;

class Portrait extends Pdf
{
    protected $font = 'Helvetica';
    protected $title_fill_color = array(0, 0, 0);

    public function __construct($disccache = false)
    {
        parent::__construct('P', 'mm', 'A4', true, 'UTF-8', $disccache);
        $this->SetAutoPageBreak(false);
        $this->SetMargins(0, 0, 0);
        //$this->AliasNbPages();
    }

    protected function getPictureFilename()
    {

    }

    /**
     * Set title color in RGB array
     *
     * @var array $rgb An array with rgb codes
     *
     * @return void
     */
    public function setTitleBackgroundColor(array $rgb)
    {
        $this->title_fill_color = $rgb;
    }

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

    protected function addTitle($title)
    {
        $title_size = 30;
        $title_width = $this->GetStringWidth($title);

        if ($title_width > 80) {
            $title_size = 23;
        } elseif ($title_width > 70) {
            $title_size = 25;
        }
        $this->SetFont($this->font, 'B', $title_size);
        $this->SetTextColor(255, 255, 255);
        $this->SetFillColorArray($this->title_fill_color);
        $this->Cell(0, 30, $title, null, 2, 'L', true);
    }

    protected function addBarCode($url)
    {
        $qr_file = $this->getBarcodePath($url, 200, 200);
        if ($qr_file !== false && file_exists($qr_file)) {
            $this->Image($qr_file, 182, 3, 24, 0);
        }
    }

    protected function addLogo()
    {
        $this->Image($this->logo->getPath(), 8, 270, 50, 0, '', $this->url);
    }

    protected function addContribLogo()
    {
        $this->Image($this->contrib_logo->getPath(), 80, 268, 45, 0, '', $this->contrib_url);
    }

    protected function addImages(array $images)
    {
        if (empty($images)) {
            return;
        }
        $x = 10;
        $y = $this->GetY();
        $new_y = $y;
        $width = 0;
        $spacing = 5;
        $count = 0;
        $picture_rows = 1;
        $no_of_pics = count($images);
        foreach ($images as $image) {
            if (!file_exists($image->getPath())) {
                continue;
            }
            if ($image->getOrientation() == 'portrait') {
                if ($no_of_pics <= 2) {
                    $pic_width = 93;
                    $new_line = 145;
                } elseif ($no_of_pics <= 3) {
                    $pic_width = 60;
                    $new_line = 95;
                } elseif ($no_of_pics <= 8) {
                    $pic_width = 45;
                    $new_line = 72;
                } else {
                    $pic_width = 34;
                    $new_line = 55;
                }
            } else {
                if ($no_of_pics == 1) {
                    $pic_width = 190;
                    $new_line = 130;
                } elseif ($no_of_pics <= 4) {
                    $pic_width = 92;
                    $new_line = 66;
                } elseif ($no_of_pics <= 9) {
                    $pic_width = 60;
                    $new_line = 44;
                } else {
                    $pic_width = 40;
                    $new_line = 34;
                }
            }
            $width += $pic_width + $spacing;

            if ($width > 200) {
                $y += $new_line;
                $x = 10;
                $picture_rows++;
                $width = 0;
                $new_y += $new_line;
            }

            $this->Image($image->getPath(), $x, $y, $pic_width, 0, '');
            $x += $pic_width + $spacing;
        }
        $this->setY($new_y + $new_line);
    }

    protected function addKeywords()
    {
        $keywords = array();

        /*
        foreach ($node->taxonomy as $taxonomy) {
        $keywords[] = $taxonomy->name;
        }
        */

        // Add the keywords.
        $this->SetLeftMargin(10);
        $this->SetRightMargin(10);
        /*
        $this->SetY(35);
        $this->SetFont($this->font, null, 10);
        $this->MultiCell(0, 5, utf8_decode(implode($keywords, ", ")), 0, 'L');
        */
    }

    public function addNewPage($exercise)
    {
        parent::AddPage();
        $this->SetMargins(0, 0, 0);
        $this->setX(0);
        $this->setY(0);

        // Add title.
        $this->addTitle("   " . $exercise->getTitle());

        // Add barcode.
        $this->addBarCode($exercise->getUrl());

        // Add keywords.
        $this->addKeywords();

        // Add images.
        $this->setY(40);
        $this->addImages($exercise->getImages());

        // Add description.
        $this->SetFont($this->font, null, 17);
        $this->setTextColor(0, 0, 0);
        $this->MultiCell(0, 8, str_replace("\n", ' ', $exercise->getIntroduction()), 0, 'L', false, 1, '', '', true, 0, true);

        // Add logo.
        $this->addLogo();
        $this->addContribLogo();

        // Add the url for the exercise.
        $this->SetFont($this->font, null, 8);
        $this->setY(280);
        $this->setX(6);
        $this->MultiCell(50, 8, $exercise->getUrl(), 0, 'C');

        // Add circle for manual numbering.
        $this->SetLineWidth(1.6);
        $this->SetDrawColor(185);
        $this->Circle(190, 292, 45);
    }
}
