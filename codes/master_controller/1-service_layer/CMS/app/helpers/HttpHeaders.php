<?php

/**
 * Class HttpHeaders
 * Create HttpHeaders for the Pages
 * HttpHeaders Like 200 OK
 */
class HttpHeaders
{
    /**
     * @var null
     */
    private $status_code = 500;

    /**
     * Makes the Status Message
     *
     * @param int $status_code_id
     * @param string $message_text
     *
     * @return string[
     */
    public
    function render_status($status_code_id = 500, $message_text = "")
    {
        $this->status_codes();
        $error_string = $status_code_id . ' ' . self::$StatusCode[$status_code_id];
        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $error_string, true, $status_code_id);
        $html_content = '<style type="text/css">body { color: #000000; background-color: #FFFFFF; }  a:link { color: #0000CC; }  p, address {margin-left: 3em;}  span {font-size: smaller;}</style><h1>System Error</h1><p>' . $message_text . '</p><h2>Error #' . $status_code_id . '</h2><address><a href="index.php">Home</a><br /><span>UIoT Managament System</span></address>';
        return $html_content;
    }

    /**
     * Generate the Status Codes Array
     */
    private
    function status_codes()
    {
        $this->status_code = [
            100 => 'Continue', 101 => 'Switching Protocols', 102 => 'Processing', 200 => 'OK', 201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content', 205 => 'Reset Content', 206 => 'Partial Content', 207 => 'Multi-Status', 300 => 'Multiple Choices', 301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized', 402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed', 406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict', 410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large', 414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable', 417 => 'Expectation Failed', 422 => 'Unprocessable Entity', 423 => 'Locked', 424 => 'Failed Dependency', 426 => 'Upgrade Required', 500 => 'Internal Server Error', 501 => 'Not Implemented', 502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout', 505 => 'HTTP Version Not Supported', 506 => 'Variant Also Negotiates', 507 => 'Insufficient Storage', 509 => 'Bandwidth Limit Exceeded', 510 => 'Not Extended'
        ];
    }
}
