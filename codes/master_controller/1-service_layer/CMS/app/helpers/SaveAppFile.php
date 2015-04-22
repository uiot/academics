<?php

final class SaveAppFile
{

    public static function make($id = '', $code = '')
    {
        $model = AppsMapper::get_by_id($id);
        self::save_file(ROOT_PATH . '/app/apps/' . $model->get_author() . '/' . $model->get_pk_id() . '.html', $code);

    }

    private static function save_file($file_name = '', $code = '')
    {

        file_put_contents($file_name, $code);
    }

}