<?php

namespace BrCommons\Tests\Unit;

use PHPUnit\Framework\TestCase;
use BrCommons\Type\CNPJ;
use BrCommons\Exception\DocumentException;

class CNPJTest extends Testcase
{

    const CNPJ_VALID                    = '37501885000112';
    const CNPJ_VALID_NUMERIC            =  37501885000112;
    const CNPJ_VALID_FORMATTED          = '37.501.885/0001-12';
    const CNPJ_VALID_FORMATTED_WITH_0   = '037.501.885/0001-12';
    const CNPJ_INVALID                  = '37501885000110';
    const CNPJ_INVALID_EQUAL            = '00000000000000';
    const CNPJ_MANY_NUMBERS             = '375018850001120';
    const CNPJ_FEW_NUMBERS              = '37501885000';
    const CNPJ_WITH_SPACES_LETTERS      = '375as./@  tsz0188ss5000112 xsd';
    const ANOTHER_CNPJ                  = '87920402000192';

    public function testFormattedValue()
    {

        $cnpj = CNPJ::from(self::CNPJ_VALID);

        $this->assertEquals(self::CNPJ_VALID_FORMATTED, CNPJ::from(self::CNPJ_VALID)->formatted());
        $this->assertEquals(self::CNPJ_VALID_FORMATTED, $cnpj->toString());
        $this->assertEquals(self::CNPJ_VALID_FORMATTED, $cnpj);


        $this->assertEquals(self::CNPJ_VALID_FORMATTED_WITH_0, CNPJ::getFormatted(self::CNPJ_VALID, true));
        $this->assertEquals(
            self::CNPJ_VALID_FORMATTED_WITH_0,
            CNPJ::getFormatted(self::CNPJ_WITH_SPACES_LETTERS, true)
        );
        $this->assertEquals(null, CNPJ::getFormatted(self::CNPJ_INVALID));
    }

    public function testRawCNPJ()
    {

        $cnpj = CNPJ::from(self::CNPJ_VALID);
        $this->assertEquals(self::CNPJ_VALID, $cnpj->raw());
    }

    public function testWithNumbers()
    {
        $cnpj = CNPJ::from(self::CNPJ_VALID_NUMERIC);
        $this->assertEquals(self::CNPJ_VALID_FORMATTED, $cnpj->formatted());
    }

    public function testIsValid()
    {

        $this->assertTrue(CNPJ::isValid(self::CNPJ_VALID));
        $this->assertTrue(CNPJ::isValid(self::CNPJ_WITH_SPACES_LETTERS));
        $this->assertFalse(CNPJ::isValid(self::CNPJ_INVALID));
        $this->assertFalse(CNPJ::isValid(self::CNPJ_MANY_NUMBERS));
        $this->assertFalse(CNPJ::isValid(self::CNPJ_FEW_NUMBERS));
        $this->assertFalse(CNPJ::isValid(self::CNPJ_INVALID_EQUAL));
    }

    public function testInvalidCPFOnCreate()
    {
        $this->expectException(DocumentException::class);
        $CNPJ = CNPJ::from(self::CNPJ_INVALID);
    }

    public function testInvalidCPFAndAssertMessage()
    {
        try {
            $cnpj = CNPJ::from(self::CNPJ_INVALID);
        } catch (DocumentException $e) {
            $this->assertEquals(self::CNPJ_INVALID, $e->getMessage());
        }

        try {
            $cnpj = CNPJ::from(self::CNPJ_INVALID);
        } catch (DocumentException $e) {
            $this->assertEquals('Documento inválido: ' . self::CNPJ_INVALID, $e->__toString());
        }
    }

    public function testOverridObject()
    {
        $cnpj  = CNPJ::from(self::CNPJ_VALID);
        $cnpj2 = CNPJ::from(self::ANOTHER_CNPJ);
        $cnpj  = CNPJ::from($cnpj2);
        $this->assertEquals(self::ANOTHER_CNPJ, $cnpj2->raw());
    }

    public function testFromString()
    {
        $this->assertEquals(null, CNPJ::fromString(self::CNPJ_INVALID));
    }
}
