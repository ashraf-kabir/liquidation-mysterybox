<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * Pdf Service
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Pdf_service
{
  private $_adapter = null;

  public function init($paper='A4', $orientation='portrait')
  {
    $this->_adapter = new Dompdf\DOMPDF();
    $this->_adapter->set_paper($paper, $orientation);
  }

  /**
   * Create PDF function
   *
   * @param string $html_view
   * @param string $filename
   * @param boolean $download
   * @return void
   */
  public function create($html_view = '', $filename='untitled.pdf', $download=TRUE)
  {
    $this->_adapter->load_html($html_view);
    $this->_adapter->render();

    if($download === TRUE)
    {
        $this->_adapter->stream($filename, array('Attachment' => 1));
        exit;
    }
    else
    {
        $this->_adapter->stream($filename, array('Attachment' => 0));
        exit;
    }
  }
}

