<?php
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
abstract class Model_interface
{
    public $name;
    public $timestamp;
    public $migration;
    public $field = [];
    public $method;
    public $join = [];
    public $mapping;
    public $pre;
    public $post;
    public $count;
}