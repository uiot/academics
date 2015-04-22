<?php

final class RemoveAppFile
{

    public static function make($id = '')
    {
        $model = AppsMapper::get_by_id($id);
        self::remove_file(ROOT_PATH . '/app/apps/' . $model->get_author() . '/' . $model->get_pk_id() . '.html');

    }

    private static function remove_file($file_name = '')
    {

        unlink($file_name);
    }

}