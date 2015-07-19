<?php 

namespace Sun\Http;


class Request {
    public function redirect($url, array $value = [])
    {
        return http_redirect($url, $value , true, HTTP_REDIRECT_PERM);
    }

    public function with($key, $value)
    {
        $_POST[$key] = $value;
    }
}