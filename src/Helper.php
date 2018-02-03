<?php
namespace Batmahir\Laravelxero;

trait Helper
{
    public function __construct()
    {
        var_dump('here');
    }

    public function getNonce()
    {
        return '<br>getNonce<br>';
    }
}