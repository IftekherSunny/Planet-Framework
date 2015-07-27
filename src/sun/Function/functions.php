<?php

function csrf_token() {
    return Csrf::token();
}

function config($location) {
    $config = new Sun\Support\Config('../config');
    $hold = explode('.', $location);

    $filename = 'get'.strtoupper(array_shift($hold));
    $location = implode('.',$hold);

    return $config->{$filename}($location);
}


function view($name,array $data = []) {
    return View::render($name, $data);
}

function redirect() {
    return new Sun\Http\Redirect(new Sun\Routing\UrlGenerator());
}

function request() {
    return new Sun\Http\Request(new Sun\Session());
}

function response() {
    return new Sun\Http\Response(new Sun\Session);
}

function validator() {
    return new Sun\Validation\Validator(new Sun\Session, new Sun\Security\Encrypter());
}

function url($path) {
    return (new Sun\Routing\UrlGenerator)->url($path);
}