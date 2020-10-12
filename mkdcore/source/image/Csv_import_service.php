<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*Powered By: Manaknightdigital Inc. https://manaknightdigital.com/ Year: 2019*/
/**
 * CSV Import Service
 * @copyright 2019 Manaknightdigital Inc.
 * @link https://manaknightdigital.com
 * @license Proprietary Software licensing
 * @author Ryan Wong
 *
 */
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;

class Csv_import_service
{
    protected $_model;
    protected $_model_name;

    public function set_model($model, $model_name)
    {
        $this->_model = $model;
        $this->_model_name = $model_name;
    }

    /**
     * CSV File Exist
     *
     * @param array $files
     * @return boolean
     */
    public function csv_file_exist($files)
    {
        return !(isset($files) && count($files) > 0 && isset($files['file']));
    }

    /**
     * Make the import insert query
     * Steps:
     * 1.Figure out the last id
     * 2.Find out schema
     * 3.Loop through each line and make sure schema match
     * 4.Else return error
     * 5.If everything correct, dump sql out
     * 6.Insert sql into database
     *
     * @param [type] $file
     * @return void
     */
    public function make_query($file)
    {
        $last_id = $this->_model->get_last_id();
        $schema = $this->_model->get_schema();
        $insert_query_template_start = "INSERT INTO `{$this->_model_name}` (";
        $insert_query_template_middle = ') VALUES ';
        $line_num = 1;
        $insert_query_list = [];

        foreach ($schema as $field_num => $field)
        {
            $field_list[] = "`$field->name`";
        }
       

        $field_list_str = implode(',', $field_list);
        $insert_query = "{$insert_query_template_start}{$field_list_str} {$insert_query_template_middle} ";

      
        while (($getData = fgetcsv($file, 1000000, ';')) !== FALSE)
        {
            $valid = TRUE;
            $field_list = [];

            if(count($getData) > 0)
            {
                $new_id = (int) $getData[0];
            }
            else
            {
                return [
                    'status' => FALSE,
                    'message' => 'Missing ID on Line ' . $line_num
                ];
            }
            if (!$getData)
            {
                // error_log('LINE FAILED ON: ' . $line_num);
                return [
                    'status' => FALSE,
                    'message' => 'Fail to get data on line ' . $line_num
                ];
            }

            foreach ($schema as $field_num => $field)
            {
                // error_log("{$field_num} {$field->type} $getData[$field_num]\n");
                $valid = $valid && $this->_model->verify_field_type($getData[$field_num], $field->type);

                if (!$valid)
                {
                    return [
                        'status' => FALSE,
                        'message' => 'Fail to get data on line ' . $line_num . '. Field Type did not match ' . $field->name
                    ];
                }
            }

            if ($new_id < $last_id)
            {
                return [
                    'status' => FALSE,
                    'message' => 'Fail to get data on line ' . $line_num . '. Field ID conflict with another row ' . $new_id
                ];
            }

            if ($valid)
            {
                $insert_query_list []= "('" . implode('\',\'', $getData) . "')";
                $field_list = [];
            }

            $line_num++;
        }

        return [
            'status' => TRUE,
            'message' => $insert_query . implode(',', $insert_query_list) . ';'
        ];
    }

    /**
     * Take file, make array
     *
     * @param [type] $file
     * @return void
     */
    public function get_csv_data($file)
    {
        $line_num = 1;
        $result = [];
        $handle = fopen($file, "r");
        while (($getData = fgetcsv($handle, 1000000, ';')) !== FALSE)
        {
            $valid = TRUE;

            if(count($getData) > 0)
            {
                $new_id = (int) $getData[0];
            }
            else
            {
                return [
                    'status' => FALSE,
                    'message' => 'Missing ID on Line ' . $line_num
                ];
            }

            if (!$getData)
            {
                // error_log('LINE FAILED ON: ' . $line_num);
                return [
                    'status' => FALSE,
                    'message' => 'Fail to get data on line ' . $line_num
                ];
            }


            if ($valid)
            {
                $result[] = $getData;
            }

            $line_num++;
        }

        return [
            'status' => TRUE,
            'data' => $result
        ];
    }

    public function import ($query)
    {
        return $this->_model->raw_no_error_query($query);
    }


    public function _get_file_data($file)
    {
        
        try
        {
            $reader = ReaderEntityFactory::createReaderFromFile( $file );
            $reader->open($file);
            $payload = [];
    
            foreach ($reader->getSheetIterator() as $sheet) 
            {
                foreach($sheet->getRowIterator() as $row)
                {
                  
                    $cells = $row->getCells();
                    $temp = [];
                    for($i = 0; $i < count($cells); $i ++)
                    {
                      $temp[]  =  $cells[$i]->getValue();
                    }

                    $payload[] = $temp;
                
                }
            }
    
            return $payload;
        }
        catch(Exception $e)
        {
            return [];
        }
    }

}