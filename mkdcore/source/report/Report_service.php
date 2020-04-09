<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * {{{ucname}}} Reporting Service
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
class {{{ucname}}}_report_service
{
    protected $_model;
    protected $_start_date;
    protected $_end_date;

    public function __construct($model , $start_date, $end_date)
    {
        $this->_model = $model;

        $start_date_time = strtotime($start_date);
        $end_date_time = strtotime($end_date);

        if ($end_date_time < $start_date_time)
        {
            $tmp = $start_date;
            $start_date = $end_date;
            $end_date = $tmp;
        }

        $this->_start_date = $start_date;
        $this->_end_date = $end_date;
    }

    public function process($parameters)
    {
{{{query}}}

        $data = [];

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
{{{result}}}
            }
        }
{{{post}}}

        return $data;
    }

    public function generate_csv($header, $data, $filename)
    {
        $csv = $this->generate_csv_raw($header, $data);
        $this->force_download($filename, $csv);
    }

    public function generate_csv_raw($header, $data)
    {
        $csv = '';
        $csv .= implode(',', $header) . "\n";

        foreach ($data as $row) {
            $csv .= implode(',', $row) . "\n";
        }
        return $csv;
    }

    public function force_download($file, $text)
    {
        $mime = 'application/force-download';
        header('Pragma: public'); // required
        header('Expires: 0'); // no cache
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Cache-Control: private', false);
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');
        echo $text;
        exit;
    }
}