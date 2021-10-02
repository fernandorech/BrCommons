<?php

namespace BrCommons\Type;

use BrCommons\Exception\DocumentException;
use BrCommons\Mask\Strings;
use BrCommons\Interfaces\DocumentInterface;

class CPF implements DocumentInterface
{

    /**
     * Static method that creates an instance of CPF from a string.
     *
     * @return fernandorech\BrCommons\Type\CPF
     *
     * @throws fernandorech\BrCommons\Exception\DocumentException
     */
    public static function from($data)
    {

        if (is_string($data) && self::isValid($data)) {
            return new CPF(Strings::cleanInput($data));
        } elseif (is_numeric($data) && self::isValid(strval($data))) {
            return new CPF(strval($data));
        } elseif (is_object($data) && (get_class($data) === __CLASS__)) {
            return self::fromString("{$data}");
        }

        throw new DocumentException($data);
    }

    /**
     * Creates an instance of CPF from a string or return null if is not valid.
     *
     * @return \Mobiup\Validator\BrCommons\Type\CPF
     *
     */
    public static function fromString($string)
    {

        if (!self::isValid($string)) {
            return null;
        }

        return new CPF(Strings::cleanInput($string));
    }

    /**
     * Return if a string is a valid CPF format.
     *
     * @return bool
     */
    public static function isValid($cpf)
    {

        $cpf = Strings::cleanInput("{$cpf}");

        if (strlen($cpf) != 11) {
            return false;
        }

        $sum = 0;

        for ($i = 0, $multiplyer = 10; $i < 9; $i++, $multiplyer--) {
            $digit = (int) $cpf[$i];

            $sum += $digit * $multiplyer;
        }

        $mod = ($sum % 11);

        $result = $mod < 2 ? 0 : 11 - $mod;

        $vd1 = (int) $cpf[9];

        if ($result != $vd1) {
            return false;
        }

        $sum = 0;

        for ($i = 0, $multiplyer = 11; $i < 10; $i++, $multiplyer--) {
            $digit = (int) $cpf[$i];

            $sum += $digit * $multiplyer;
        }

        $mod = ($sum % 11);

        $result = $mod < 2 ? 0 : 11 - $mod;

        $vd1 = (int) $cpf[10];

        if ($result != $vd1) {
            return false;
        }

        return true;
    }

    /**
     * Static function that returns a raw string of CPF if it's valid.
     *
     * @return string
     */
    public static function getFormatted($value)
    {
        return (self::isValid($value)) ? Strings::mask($value, "###.###.###-##") : null;
    }

     /**
     * The raw string of the document.
     *
     * @var string
     */
    private $raw;

    /**
     * Create a new CPF instance.
     *
     * @param  string  $raw
     * @return void
     */
    private function __construct($raw)
    {
        $this->raw = $raw;
    }

    /**
     * Return a raw string of CPF.
     *
     * @return string
     */
    public function raw()
    {
        return $this->raw;
    }

    /**
     * Return a formatted string of CPF.
     *
     * @return string
     */
    public function formatted()
    {
        return Strings::mask($this->raw, "###.###.###-##");
    }

     /**
     * Return a formatted string of CPF.
     *
     * @return string
     */
    public function toString()
    {
        return $this->__toString();
    }

    /**
     * Magic method a formatted string of CPF.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->formatted();
    }
}
