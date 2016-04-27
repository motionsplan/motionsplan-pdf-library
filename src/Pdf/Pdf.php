<?php
namespace Motionsplan\Pdf;

abstract class Pdf extends \TCPDF
{
    protected $temporary_dir = null;

    public function header()
    {
        // Intentionally left blank to avoid line in the header.
    }

    public function setTemporaryDirectory($dir)
    {
        if (!is_dir($dir) || !is_writable($dir)) {
            throw new \Exception('Temporary directory is either not available or not writable');
        }
        $this->temporary_dir = $dir;
    }

    /**
     * Gets barcode file path
     *
     * @param string  $url url
     * @param integer $height Height of the QR Code
     * @param integer $width Width of the QR Code
     *
     * @return string or false
     * @throws \Exception
     */
    protected function getBarcodePath($url, $height, $width)
    {
        if ($this->temporary_dir === null) {
            throw new \Exception("Temporary directory has not been set.");
        }
        $filename = $this->temporary_dir . '/' . md5($url) . '.png';
        if (!file_exists($filename)) {
            file_put_contents($filename, fopen('http://chart.apis.google.com/chart?chs=' . $width . 'x' . $height . '&&cht=qr&chl=' . $url, 'r'));
        }
        return $filename;
    }
}
