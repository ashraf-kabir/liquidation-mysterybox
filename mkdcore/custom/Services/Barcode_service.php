<?php
 
class Barcode_service {

    public function generate_png_barcode($barcode,$manual_text = null)
    {
        if($manual_text == null)
        {
            $barcode_image_name = '/uploads/' . $barcode . '-barcode.png';
        } else{
            $barcode_image_name = '/uploads/' . $barcode . '-' . $manual_text . '-barcode.png';
        } 

        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        
        $black_color  = [0, 0, 0];
        
        if( file_put_contents(__DIR__ . '/../../../'.$barcode_image_name, $generator->getBarcode($barcode, $generator::TYPE_CODE_128,3, 50, $black_color))  )
        {
            return $barcode_image_name;
        }
    }
}