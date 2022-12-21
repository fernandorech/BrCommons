<?php

namespace BrCommons\Tests\Unit;

use PHPUnit\Framework\TestCase;
use BrCommons\Type\CPF;
use BrCommons\Exception\DocumentException;

class CPFTest extends Testcase
{

    const CPF_VALID                 = '67091015096';
    const CPF_VALID_NUMERIC         =  67091015096;
    const CPF_VALID_FORMATTED       = '670.910.150-96';
    const CPF_INVALID               = '67091015080';
    const CPF_INVALID_EQUAL         = '00000000000';
    const CPF_MANY_NUMBERS          = '670910150901';
    const CPF_FEW_NUMBERS           = '6709101509';
    const CPF_WITH_SPACES_LETTERS   = '670.\@ae 910 ddff 15096 asa';
    const ANOTHER_CPF               = '22228761095';

    public function testFormattedValue()
    {

        $cpf = CPF::from(self::CPF_VALID);
        $this->assertEquals(self::CPF_VALID_FORMATTED, $cpf->formatted());
        $this->assertEquals(self::CPF_VALID_FORMATTED, $cpf->toString());
        $this->assertEquals(self::CPF_VALID_FORMATTED, $cpf);

        $this->assertEquals(self::CPF_VALID_FORMATTED, CPF::getFormatted(self::CPF_VALID));
        $this->assertEquals(self::CPF_VALID_FORMATTED, CPF::getFormatted(self::CPF_VALID_FORMATTED));
        $this->assertEquals(self::CPF_VALID_FORMATTED, CPF::getFormatted(self::CPF_WITH_SPACES_LETTERS));
    }

    public function testWithNumbers()
    {
        $cpf = CPF::from(self::CPF_VALID_NUMERIC);
        $this->assertEquals(self::CPF_VALID_FORMATTED, $cpf->formatted());
    }

    public function testRawCPF()
    {

        $cpf = CPF::from(self::CPF_VALID);
        $this->assertEquals(self::CPF_VALID, $cpf->raw());
    }

    public function testInvalidFromStringCPF()
    {

        $cpf = CPF::fromString(self::CPF_INVALID);
        $this->assertEquals(null, $cpf);
    }

    public function testIsValid()
    {

        $this->assertTrue(CPF::isValid(self::CPF_VALID));
        $this->assertTrue(CPF::isValid(self::CPF_WITH_SPACES_LETTERS));
        $this->assertFalse(CPF::isValid(self::CPF_INVALID));
        $this->assertFalse(CPF::isValid(self::CPF_MANY_NUMBERS));
        $this->assertFalse(CPF::isValid(self::CPF_FEW_NUMBERS));
        $this->assertFalse(CPF::isValid(false));
        $this->assertFalse(CPF::isValid(self::CPF_INVALID_EQUAL));
    }

    public function testInvalidCPFOnCreate()
    {
        $this->expectException(DocumentException::class);
        $cpf = CPF::from(self::CPF_INVALID);
    }

    public function testInvalidCPFAndAssertMessage()
    {
        try {
            $cpf = CPF::from(self::CPF_INVALID);
        } catch (DocumentException $e) {
            $this->assertEquals(self::CPF_INVALID, $e->getMessage());
        }

        try {
            $cpf = CPF::from(self::CPF_INVALID);
        } catch (DocumentException $e) {
            $this->assertEquals('Documento inválido: ' . self::CPF_INVALID, $e->__toString());
        }
    }

    public function testOverridObject()
    {
        $cpf  = CPF::from(self::CPF_VALID);
        $cpf2 = CPF::from(self::ANOTHER_CPF);
        $cpf  = CPF::from($cpf2);
        $this->assertEquals(self::ANOTHER_CPF, $cpf2->raw());
    }
}
