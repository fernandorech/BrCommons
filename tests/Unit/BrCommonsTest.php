<?php

namespace BrCommons\Tests\Unit;

use PHPUnit\Framework\TestCase;
use BrCommons\BrCommons;
use BrCommons\Type\CPF;
use BrCommons\Type\CNPJ;
use BrCommons\Exception\DocumentException;

class BrCommonsTest extends Testcase
{

    const DOCUMENT_VALID_CPF                = '67091015096';
    const DOCUMENT_VALID_FORMATTED_CPF      = '670.910.150-96';

    const DOCUMENT_VALID_CNPJ               = '37501885000112';
    const DOCUMENT_VALID_FORMATTED_CNPJ     = '37.501.885/0001-12';

    const DOCUMENT_INVALID                  = '67091015090';
    const DOCUMENT_MANY_NUMBERS             = '670910150901';
    const DOCUMENT_FEW_NUMBERS              = '6709101509';
    const DOCUMENT_WITH_SPACES_LETTERS      = '670.\@ae 910 ddff 15096 asa';
    const ANOTHER_DOCUMENT_CPF              = '22228761095';
    const ANOTHER_DOCUMENT_CNPJ             = '22228761095';
    const DOCUMENT_VALID_FORMATTED_CNPJ_0   = '037.501.885/0001-12';

    public function testFormattedValue()
    {

        $document = BrCommons::from(self::DOCUMENT_VALID_CPF);

        $this->assertEquals(self::DOCUMENT_VALID_FORMATTED_CPF, $document->formatted());
        $this->assertEquals(self::DOCUMENT_VALID_FORMATTED_CPF, $document->toString());
        $this->assertEquals(self::DOCUMENT_VALID_FORMATTED_CPF, $document);

        $document = BrCommons::from(self::DOCUMENT_VALID_CNPJ);
        $this->assertEquals(self::DOCUMENT_VALID_FORMATTED_CNPJ, $document->formatted());
        $this->assertEquals(self::DOCUMENT_VALID_FORMATTED_CNPJ, $document->toString());
        $this->assertEquals(self::DOCUMENT_VALID_FORMATTED_CNPJ, $document);

        $this->assertEquals(self::DOCUMENT_VALID_FORMATTED_CNPJ_0, $document->formatted(true));
    }

    public function testFormat()
    {
        $this->assertEquals(self::DOCUMENT_VALID_FORMATTED_CPF, BrCommons::format(self::DOCUMENT_VALID_FORMATTED_CPF));
        $this->assertEquals(null, BrCommons::from(self::DOCUMENT_INVALID));
    }
    public function testInstance()
    {
        $document = BrCommons::from(self::DOCUMENT_VALID_CPF);
        $this->assertInstanceOf(CPF::class, $document->returnInstance());

        $document = BrCommons::from(self::DOCUMENT_VALID_CNPJ);
        $this->assertInstanceOf(CNPJ::class, $document->returnInstance());
    }

    public function testIsInstanceOfDocument()
    {
        $document = BrCommons::from(self::DOCUMENT_VALID_CNPJ);

        $this->assertTrue($document->isInstanceOf('CNPJ'));
    }

    public function testOnlyNumbers()
    {
        $document = BrCommons::from(self::DOCUMENT_VALID_CNPJ);

        $this->assertEquals(self::DOCUMENT_VALID_CNPJ, BrCommons::onlyNumbers(self::DOCUMENT_VALID_CNPJ));
    }

    public function testRaw()
    {

        $document = BrCommons::from(self::DOCUMENT_VALID_CPF);
        $this->assertEquals(self::DOCUMENT_VALID_CPF, $document->raw());

        $document = BrCommons::from(self::DOCUMENT_VALID_CNPJ);
        $this->assertEquals(self::DOCUMENT_VALID_CNPJ, $document->raw());
    }

    public function testIsValid()
    {

        $this->assertTrue(BrCommons::isValid(self::DOCUMENT_VALID_CPF));
        $this->assertTrue(BrCommons::isValid(self::DOCUMENT_WITH_SPACES_LETTERS));
        $this->assertFalse(BrCommons::isValid(self::DOCUMENT_INVALID));
        $this->assertFalse(BrCommons::isValid(self::DOCUMENT_MANY_NUMBERS));
        $this->assertFalse(BrCommons::isValid(self::DOCUMENT_FEW_NUMBERS));
    }

    public function testInvalBrCommonsOnCreate()
    {
        $this->expectException(DocumentException::class);
        $document = BrCommons::from(self::DOCUMENT_INVALID, true);
    }

    public function testInvalBrCommonsAndAssertMessage()
    {
        try {
            $document = BrCommons::from(self::DOCUMENT_INVALID, true);
        } catch (DocumentException $e) {
            $this->assertEquals(self::DOCUMENT_INVALID, $e->getMessage());
        }

        try {
            $document = BrCommons::from(self::DOCUMENT_INVALID, true);
        } catch (DocumentException $e) {
            $this->assertEquals('Documento inválido: ' . self::DOCUMENT_INVALID, $e->__toString());
        }
    }

    public function testInvalBrCommonsWithNoException()
    {
        $document = BrCommons::from(self::DOCUMENT_INVALID, false);
        $this->assertEquals('', $document);
    }

    public function testStrip()
    {
        $this->assertEquals(self::DOCUMENT_VALID_CPF, BrCommons::strip(self::DOCUMENT_VALID_CPF));
    }
}
