<?php

/*
 * BrCommons - Biblioteca  de validação de padrões Brasileiros
 *
 * LICENSE: The MIT License (MIT)
 *
 * Copyright (C) 2021 Fernando Rech
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this
 * software and associated documentation files (the "Software"), to deal in the Software
 * without restriction, including without limitation the rights to use, copy, modify,
 * merge, publish, distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies
 * or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED,
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A
 * PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace BrCommons;

use Composer\Autoload\ClassMapGenerator;
use BrCommons\Exception\DocumentException;
use BrCommons\Mask\Strings;
use BrCommons\Interfaces\DocumentInterface;

class BrCommons implements DocumentInterface
{

    const DIR_TYPE = __DIR__ . '/Type';


    /**
     *
     * @var \BrCommons\BrCommons|False
     */
    private $document;

    /**
     * Static method that creates an instance of BrCommons     *
     *
     * @throws \BrCommons\Exception\DocumentException
     */
    public static function from($value, $returnException = false)
    {

        foreach (self::generateClassMap() as $class) {
            if ($class::isValid($value)) {
                return new BrCommons($class::from($value));
            }
        }
        if ($returnException) {
            throw new DocumentException($value);
        }

        return null;
    }

    /**
     * Return if an string is a valid document type.
     *
     * @return bool
     *
     */
    public static function isValid($value)
    {

        foreach (self::generateClassMap() as $class) {
            if ($class::isValid($value)) {
                return true;
            }
        }

        return false;
    }

    public static function generateClassMap()
    {
        return [
            BrCommons\Type\CPF::class,
            BrCommons\Type\CNPJ::class
        ];        
    }

    /**
     * Return the raw string validated.
     * @param String $value
     * @return string
     *
     */
    public static function strip($value)
    {
        return self::from($value)->raw();
    }

    /**
     * Return the formatted string after its validated.
     *
     * @param String $value
     * @param Bool   $optional
     * @return string
     *
     */
    public static function format($value, $optional = false)
    {
        return self::from($value, $optional)->formatted();
    }

    /**
     * Return the formatted string after its validated.
     * @param String $document
     *
     * @return string
     *
     */
    public static function onlyNumbers($document)
    {
        return Strings::cleanInput($document);
    }

    /**
     * Create a new BrCommons instance.
     *
     * @param  string  $document
     * @return void
     */
    private function __construct($document)
    {
        $this->document = $document;
    }

    /**
     * Return a type.
     *
     * @return void
     */
    public function returnInstance()
    {
        return $this->document;
    }

    /**
     * Check if string passed is the type of the object
     *
     * @param  string $class
     * @return String
     */
    public function isInstanceOf($class)
    {
        return ((new \ReflectionClass($this->document))->getShortName() == strtoupper($class)) ? true : false ;
    }

    /**
     * Return the raw document
     *
     * @return string
     */
    public function raw()
    {
        return $this->document->raw();
    }

    /**
     * Return a formatted string of the type.
     * @param  bool $optional
     * @return String
     */
    public function formatted($optional = false)
    {
        return $this->document->formatted($optional);
    }

    /**
     * Return a formatted string of the type.
     *
     * @return String
     */
    public function toString()
    {
        return $this->formatted();
    }

    /**
     * Return a formatted string of the type.
     *
     * @return String
     */
    public function __toString()
    {
        return $this->formatted();
    }
}
