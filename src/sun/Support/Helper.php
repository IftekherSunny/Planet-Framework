<?php namespace sun\Support; 

class Helper {

    public static function output($data)
    {
        if(is_array($data)) {

            echo json_encode($data);
        }

        echo $data;
    }
}