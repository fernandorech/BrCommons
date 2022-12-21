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
        
        $cnpj = Strings::cleanInput("{$cnpj}");

        if (strlen($cnpj) != 14 || preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }

        for ($i = 0, $j = 5, $sum = 0; $i < 12; $i++) {
            $sum += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
    
        $remainder = $sum % 11;
    
        if ($cnpj[12] != ($remainder < 2 ? 0 : 11 - $remainder)) {
            return false;
        }

        for ($i = 0, $j = 6, $sum = 0; $i < 13; $i++) {
            $sum += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
    
        $remainder = $sum % 11;
    
        return $cnpj[13] == ($remainder < 2 ? 0 : 11 - $remainder);
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
