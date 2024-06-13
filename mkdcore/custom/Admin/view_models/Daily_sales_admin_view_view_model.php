<?php defined('BASEPATH') || exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * View Daily Sales View Model
 *
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 */
class Daily_sales_admin_view_view_model
{
  protected $_entity;
  protected $_heading;
  protected $_model;

  public function __construct($entity)
  {
    $this->_entity = $entity;
  }

  public function get_entity()
  {
    return $this->_entity;
  }

  /**
   * set_heading function
   *
   * @param string $heading
   * @return void
   */
  public function set_heading($heading)
  {
    $this->_heading = $heading;
  }

  /**
   * get_heading function
   *
   * @return string
   */
  public function get_heading()
  {
    return $this->_heading;
  }

  public function set_model($model)
  {
    $this->_model = $model;
  }

  public function timeago($date)
  {
    $timestamp = strtotime($date);

    $strTime = ['second', 'minute', 'hour', 'day', 'month', 'year'];
    $length  = ['60', '60', '24', '30', '12', '10'];

    $currentTime = time();
    if ($currentTime >= $timestamp) {
      $diff = time() - $timestamp;

      for ($i = 0; $diff >= $length[$i] && $i < count($length) - 1; $i++) {
        $diff = $diff / $length[$i];
      }

      $diff = round($diff);
      return $diff . ' ' . $strTime[$i] . '(s) ago ';
    }
  }

  public function time_default_mapping()
  {
    $results = [];
    for ($i = 0; $i < 24; $i++) {
      for ($j = 0; $j < 60; $j++) {
        $hour                    = ($i < 10) ? '0' . $i : $i;
        $min                     = ($j < 10) ? '0' . $j : $j;
        $results[($i * 60) + $j] = "$hour:$min";
      }
    }
    return $results;
  }
}
