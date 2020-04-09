<?php
/**
 * This is my debugging toolset to save me time doing everything
 *
 */
class D
{
    /**
     * Var dump
     *
     * @param [type] $data
     * @return void
     */
    public static function v ($data)
    {
        echo var_dump($data);
    }

    /**
     * Vardump exit
     *
     * @param [type] $data
     * @return void
     */
    public static function ve ($data)
    {
        echo var_dump($data);
        exit;
    }

    /**
     * print r
     *
     * @param [type] $data
     * @return void
     */
    public static function p ($data)
    {
        echo '<pre>' . print_r($data, TRUE) . '</pre>';
    }

    /**
     * Print r exit
     *
     * @param [type] $data
     * @return void
     */
    public static function pe ($data)
    {
        echo '<pre>' . print_r($data, TRUE) . '</pre>';
        exit;
    }

    /**
     * Error log
     *
     * @param [type] $data
     * @return void
     */
    public static function e ($data)
    {
        error_log($data);
    }

    /**
     * Error log printr
     *
     * @param [type] $data
     * @return void
     */
    public static function ep ($data)
    {
        error_log('<pre>' . print_r($data, TRUE) . '</pre>');
    }

    /**
     * Error log true/false
     *
     * @param [type] $data
     * @return void
     */
    public static function et ($data)
    {
        error_log(($data) ? 'true': 'false');
    }
}