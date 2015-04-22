<?php

final class GetAppFile
{

    public static function make($id = '')
    {
        $model = AppsMapper::get_by_id($id);
        $code = self::get_file(ROOT_PATH . '/app/apps/' . $model->get_author() . '/' . $model->get_pk_id() . '.html');
        return $code;
    }

    private static function get_file($file_name = '')
    {
        $file = file_get_contents($file_name);
        return $file;
    }

}