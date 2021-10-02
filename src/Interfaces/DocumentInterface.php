<?php

namespace BrCommons\Interfaces;

interface DocumentInterface
{

    public function raw();

    public function formatted();

    public static function from($data);

    public static function isValid($data);
}
