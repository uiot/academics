<?php

/**
 * Generic Purpose Functions
 * Functions for Others Usages
 */

/**
 * Create a Redirect Url
 *
 * @param $sub_url
 */
function redirect($sub_url = '')
{
    $base_url = explode("/", $_SERVER['SCRIPT_NAME']);
    for ($i = 0; $i < count($base_url); $i++)
        unset($base_url[sizeof($base_url) - 1]);
    $base_url = implode("/", $base_url);
    header("Location: " . $base_url . $sub_url);
}

/**
 * Create a Url Valid Format
 *
 * @param $sub_url
 *
 * @return string
 */
function link_to($sub_url = '')
{

    $base_url = explode("/", $_SERVER['SCRIPT_NAME']);
    for ($i = 0; $i < count($base_url); $i++)
        unset($base_url[sizeof($base_url) - 1]);
    $base_url = implode("/", $base_url);
    return $base_url . $sub_url;
}

/**
 * @param string $sub_url
 *
 * @return string
 *
 * Create Complete Link
 */
function clink_to($sub_url = '')
{

    $base_url = explode("/", $_SERVER['SCRIPT_NAME']);
    for ($i = 0; $i < count($base_url); $i++)
        unset($base_url[sizeof($base_url) - 1]);
    $base_url = implode("/", $base_url);
    $sub_url = 'http://' . $_SERVER['HTTP_HOST'] . $base_url . $sub_url;
    return $sub_url;
}

/**
 * Kills the System
 *
 * @param string $content
 * @param bool $return_print
 * @param bool $return_content
 *
 * @return string
 */
function terminate($content = '', $return_print = false, $return_content = false)
{
    if (!$return_print && !$return_content)
        die("<pre>[Die_Information_1]\n" . print_r($content, 1) . "\n[/Die_Information_1]</pre>");
    if ($return_print)
        print("<pre>[Die_Information_2]\n" . print_r($content, 1) . "\n[/Die_Information_2]</pre>");
    if ($return_content === true)
        return "<pre>[Die_Information_3]\n" . print_r($content, 1) . "\n[/Die_Information_3]</pre>";
}


/**
 * Creates a Cookie
 *
 * @param string $cookie_name
 * @param string $cookie_value
 */
function add_cookie($cookie_name = '', $cookie_value = '')
{
    ob_start();
    setcookie($cookie_name, $cookie_value, time() + (10 * 365 * 24 * 60 * 60));
    ob_end_flush();
}

/**
 * Escape the Text to Block Injection and XSS Attacks
 *
 * @param $text_string
 *
 * @return array|mixed
 */
function escape_text($text_string = '')
{
    if (is_array($text_string))
        return array_map(__METHOD__, $text_string);
    if (!empty($text_string) && is_string($text_string))
        return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $text_string);
    return $text_string;
}


/**
 * Formats number into UPnP UUID
 *
 * @param string $integer_value
 *
 * @return string final string containing integer padded with zeros
 */
function uuid_format($integer_value = '')
{
    $size = strlen($integer_value);
    if ($size <= 12):
        $padded = "00000000-0000-0000-0000-";
        $padded .= str_pad($integer_value, 12, "0", STR_PAD_LEFT);
    elseif ($size <= 16):
        $padded = "00000000-0000-0000-";
        $tail = str_pad(substr($integer_value, 0, strlen($integer_value) - 12), 4, "0", STR_PAD_LEFT);
        $tail .= "-" . substr($integer_value, strlen($integer_value) - 12);
        $padded .= $tail;
    elseif ($size <= 20):
        $padded = "00000000-0000-";
        $tail = str_pad(substr($integer_value, 0, strlen($integer_value) - 16), 4, "0", STR_PAD_LEFT);
        $tail .= "-" . substr($integer_value, strlen($integer_value) - 16, 4);
        $tail .= "-" . substr($integer_value, strlen($integer_value) - 12);
        $padded .= $tail;
    elseif ($size <= 24):
        $padded = "00000000-";
        $tail = str_pad(substr($integer_value, 0, strlen($integer_value) - 20), 4, "0", STR_PAD_LEFT);
        $tail .= "-" . substr($integer_value, strlen($integer_value) - 20, 4);
        $tail .= "-" . substr($integer_value, strlen($integer_value) - 16, 4);
        $tail .= "-" . substr($integer_value, strlen($integer_value) - 12);
        $padded .= $tail;
    else:
        $padded = "";
        $tail = str_pad(substr($integer_value, 0, strlen($integer_value) - 24), 8, "0", STR_PAD_LEFT);
        $tail .= "-" . substr($integer_value, strlen($integer_value) - 24, 4);
        $tail .= "-" . substr($integer_value, strlen($integer_value) - 20, 4);
        $tail .= "-" . substr($integer_value, strlen($integer_value) - 16, 4);
        $tail .= "-" . substr($integer_value, strlen($integer_value) - 12);
        $padded .= $tail;
    endif;
    return "uuid:" . $padded;
}

/**
 * Makes a Convert to Javascript String
 * @param $s
 * @return string
 */
function js_str($s)
{
    return '"' . addcslashes($s, "\0..\37\"\\") . '"';
}

/**
 * Create an Javascript Array
 * @param $array
 * @return string
 */
function js_array($array)
{
    $temp = array_map('js_str', $array);
    return '[' . implode(',', $temp) . ']';
}

