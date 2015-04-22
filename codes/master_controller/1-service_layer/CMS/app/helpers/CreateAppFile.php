<?php

final class CreateAppFile
{

    public static function make($app_details = [], $id = '', $code = [])
    {
        $app_details = json_decode($app_details, true);
        $dir = self::make_dir($app_details['author']);
        self::make_file($dir . '/' . $id . '.html', $code);

    }

    private static function make_dir($author_name = '')
    {
        mkdir(ROOT_PATH . '/app/apps/' . $author_name);
        return ROOT_PATH . '/app/apps/' . $author_name;
    }

    private static function make_file($file_name = '', $code = [])
    {
        $codes = json_decode($code, true);
        $code = $codes['code'];
        file_put_contents($file_name, $code);
    }

}