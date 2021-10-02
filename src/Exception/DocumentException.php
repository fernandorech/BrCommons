<?php

namespace BrCommons\Exception;

/**
 * undocumented class
 */
class DocumentException extends \Exception
{
    public function __toString()
    {
        return "Documento inválido: " . $this->getMessage();
    }
}
