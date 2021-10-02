<?php

namespace  BrCommons\Type;

use BrCommons\Exception\DocumentException;
use BrCommons\Interfaces\DocumentInterface;
use BrCommons\Mask\Strings;

class CNPJ implements DocumentInterface
{
    /**
     * Static method that creates an instance of CNPJ from a string.
     *
     * @return fernandorech\BrCommons\Type\CNPJ
     *
     * @throws fernandorech\BrCommons\Exception\DocumentException
     */
    public static function from($data)
    {

        if (is_string($data) && self::isValid($data)) {
            return new CNPJ(Strings::cleanInput($data));
        } elseif (is_numeric($data) && self::isValid(strval($data))) {
            return new CNPJ(strval($data));
        } elseif (is_object($data) && (get_class($data) === __CLASS__)) {
            return self::fromString("{$data}");
        }

        throw new DocumentException($data);
    }

    /**
     * Creates an instance of CNPJ from a string or return null if is not valid.
     *
     * @return \Mobiup\Validator\BrCommons\Type\CNPJ
     *
     */
    public static function fromString($string)
    {

        if (!self::isValid($string)) {
            return null;
        }

        return new CNPJ(Strings::cleanInput($string));
    }

    /**
     * Return a string sanitized with a zero on the left side of the string.
     *
     * @return string
     */
    public static function addZero($cnpj)
    {
        return str_pad(Strings::cleanInput($cnpj), 15, '0', STR_PAD_LEFT) ;
    }

    /**
     * Return if a string is a valid CNPJ format.
     *
     * @return bool
     */
    public static function isValid($cnpj)
    {
        $cnpj = self::addZero($cnpj) ;

        $table = '6543298765432';

        $sum = 0;

        for ($i = 0; $i < 13; $i++) {
            $digit = (int) $cnpj[$i];
            $multiplyer = (int) $table[$i];

            $sum += $digit * $multiplyer;
        }

        $mod = ($sum % 11);

        $result = $mod < 2 ? 0 : 11 - $mod;

        $vd1 = (int) $cnpj[13];

        if ($result != $vd1) {
            return false;
        }

        $table = '76543298765432';

        $sum = 0;

        for ($i = 0; $i < 14; $i++) {
            $digit = (int) $cnpj[$i];
            $multiplyer = (int) $table[$i];

            $sum += $digit * $multiplyer;
        }

        $mod = ($sum % 11);

        $result = $mod < 2 ? 0 : 11 - $mod;

        $vd2 = (int) $cnpj[14];

        if ($result != $vd2) {
            return false;
        }

        return true;
    }

    /**
     * Static function that returns a raw string of CNPJ if it's valid.
     *
     * @return string
     */
    public function getFormatted($value, $optional = false)
    {
        if (!self::isValid($value)) {
            return null;
        }

        return ($optional) ? Strings::mask(self::addZero($value), "###.###.###/####-##") : '##.###.###/####-##';
    }

    /**
     * The raw string of the document.
     *
     * @var string
     */
    private $raw;

    /**
     * Create a new CNPJ instance.
     *
     * @param  string  $raw
     * @return void
     */
    private function __construct($raw)
    {
        $this->raw = $raw;
    }

    /**
     * Return a raw string of CNPJ.
     *
     * @return string
     */
    public function raw()
    {
        return $this->raw;
    }

    /**
     * Return a formatted string of CNPJ.
     *
     * @return string
     */
    public function formatted($optional = false)
    {
        return ($optional) ? Strings::mask(self::addZero($this->raw), "###.###.###/####-##") :
        Strings::mask($this->raw, "##.###.###/####-##");
    }

    /**
     * Return a formatted string of CNPJ.
     *
     * @return string
     */
    public function toString()
    {
        return $this->__toString();
    }

    /**
     * Magic method a formatted string of CNPJ.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->formatted();
    }
}
