<?php

namespace I18nTest;

use \Sepia\I18n\Translation;
use \Mockery as m;

class TranslationTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    public function testExistingTranslation()
    {
        $translation = new Translation('hello', 'hola');

        $this->assertEquals('hola', $translation->getTranslation());
    }

    public function testUnexistingTranslation()
    {
        $translation = new Translation('hello', null);

        $this->assertEquals(null, $translation->getTranslation());
    }

    public function testContext()
    {
        $translation = new Translation('hello', [
            'context1' => 'hello1',
            'context2' => 'hello2'
            ]);

        $this->assertEquals('hello1', $translation->getTranslation('context1'));
        $this->assertEquals('hello2', $translation->getTranslation('context2'));
        $this->assertEquals('hello', $translation->getTranslation('unknown'));
        $this->assertEquals('hello', $translation->getTranslation());
    }
}
