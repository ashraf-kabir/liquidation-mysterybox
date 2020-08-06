<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * {{{uc_name}}} List View Model
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class {{{uc_name}}}_{{{portal}}}_list_view_model
{
    protected $_list = [];
    protected $_column = [{{{column}}}];
    protected $_entity;
    protected $_heading = '{{{uc_name}}}';

    public function __construct($entity)
    {
        $this->_entity = $entity;
    }

    public function set_heading ($heading)
    {
        $this->_heading = $heading;
    }

    public function get_heading ()
    {
        return $this->_heading;
    }

    public function set_column ($column)
    {
        $this->_column = $column;
    }

    public function get_column ()
    {
        return $this->_column;
    }

    public function image_or_file ($file)
    {
        $images = ['.jpg','.png', '.gif', '.jpeg', '.bmp'];
        $is_image = FALSE;
        if ($this->strposa($file, $images))
        {
            return "<div class='mkd-image-container'><img class='img-fluid' src='{$file}' onerror=\"if (this.src != '/uploads/placeholder.jpg') this.src = '/uploads/placeholder.jpg';\"/></div>";
        }

        return "<a href='{$file}' target='__blank'>{$file}</a>";
    }

    /**
     * Strpos for array
     *
     * @param string $haystack
     * @param array $needle
     * @return boolean
     */
    private function strposa($haystack, $needle)
    {
        foreach($needle as $query)
        {
            if(strpos($haystack, $query) !== FALSE)
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    public function set_list ($list)
    {
        $this->_list = $list;
    }

    public function get_list ()
    {
        return $this->_list;
    }

    public function timeago($date)
    {
        $timestamp = strtotime($date);

        $strTime = array('second', 'minute', 'hour', 'day', 'month', 'year');
        $length = array('60', '60', '24', '30', '12', '10');

        $currentTime = time();
        if($currentTime >= $timestamp)
        {
             $diff  = time() - $timestamp;

             for($i = 0; $diff >= $length[$i] && $i < count($length)-1; $i++)
             {
                $diff = $diff / $length[$i];
             }

             $diff = round($diff);
             return $diff . ' ' . $strTime[$i] . '(s) ago ';
        }
    }

    public function time_default_mapping ()
    {
        $results = [];
        for ($i=0; $i < 24; $i++)
        {
            for ($j=0; $j < 60; $j++)
            {
                $hour = ($i < 10) ? '0' . $i : $i;
                $min = ($j < 10) ? '0' . $j : $j;
                $results[($i * 60) + $j] = "$hour:$min";
            }
        }
        return $results;
    }
{{{mapping}}}{{{to_json}}}
}