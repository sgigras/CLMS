<?php
function checkIMP($string)
{
    $string = preg_replace('/"/i', '', $string);
    $string = preg_replace("/'/i", '', $string);
    $string = preg_replace('/<.+>/sU', '', $string);
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string);
    $string = preg_replace('/(?:\{|<|\[)/', '(', $string);

    return $string;
}

function checkIMPalpha($string)
{
    $string = preg_replace('/"/i', '', $string);
    $string = preg_replace("/'/i", '', $string);
    $string = preg_replace('/<.+>/sU', '', $string);
    $string = preg_replace('/[^A-Za-z\s]/', '', $string);
    $string = preg_replace('/(?:\{|<|\[)/', '(', $string);

    return $string;
}

function checkIMPnum($string)
{
    $string = preg_replace('/"/i', '', $string);
    $string = preg_replace("/'/i", '', $string);
    $string = preg_replace('/<.+>/sU', '', $string);
    $string = preg_replace('/[^0-9]/', '', $string);
    $string = preg_replace('/(?:\{|<|\[)/', '(', $string);

    return $string;
}

function checkIMPemail($string)
{
    $string = preg_replace('/"/i', '', $string);
    $string = preg_replace("/'/i", '', $string);
    $string = preg_replace('/<.+>/sU', '', $string);
    $string = preg_replace('/[^A-Za-z0-9\-@_.]/', '', $string);
    $string = preg_replace('/(?:\{|<|\[)/', '(', $string);

    return $string;
}

function checkIMPaddress($string)
{
    $string = preg_replace('/"/i', '', $string);
    $string = preg_replace("/'/i", '', $string);
    $string = preg_replace('/<.+>/sU', '', $string);
    $string = preg_replace("/[^A-Za-z0-9\-@_.';#,&]/", '', $string);
    $string = preg_replace('/(?:\{|<|\[)/', '(', $string);

    return $string;
}

