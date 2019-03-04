<?php
namespace App;

class Response
{
    public function json($data, $code = 200)
    {
        header_remove();
        http_response_code($code);
        header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
        header('Content-Type: application/json');
        $status = array(
            200 => '200 OK',
            201 => '201 Created',
            400 => '400 Bad Request',
            404 => '404 Not found',
            422 => 'Unprocessable Entity',
            500 => '500 Internal Server Error'
            );
        if (isset($status[$code])) {
            header('Status: '.$status[$code]);
        }
        echo json_encode($data);
    }

    public function html($data, $code = 200)
    {
        header_remove();
        http_response_code($code);
        echo $data;
    }
}