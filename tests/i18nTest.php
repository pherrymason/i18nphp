<?php

namespace I18nTest;

use \Mockery as m;
use Sepia\I18n;

class I18nTest extends \PHPUnit_Framework_TestCase
{
    public function tearDown()
    {
        m::close();
    }

    protected function getI18nMock($translation)
    {
        return m::mock('Sepia\I18n\TranslationProvider\TranslationProviderInterface')
            ->shouldReceive('get')
            ->andReturn($translation)
            ->mock();
    }

    public function testTranslateSimple()
    {
        $translation = m::mock('Sepia\I18n\Translation')
            ->shouldReceive('getTranslation')
            ->andReturn('hola')
            ->mock();

        $provider = $this->getI18nMock($translation);

        $i18n = new I18n($provider, 'es');
        $translation = $i18n->translate('hello');

        $this->assertEquals('hola', $translation);
    }

    public function testTranslateUntranslated()
    {
        $provider = m::mock('Sepia\I18n\TranslationProvider\TranslationProviderInterface')
            ->shouldReceive('get')
            ->andThrow('\Sepia\I18n\Exception\TranslationNotFound', 'mec')
            ->mock();

        $i18n = new I18n($provider, 'es');
        $translation = $i18n->translate('amparo');

        $this->assertEquals('amparo', $translation);
    }

    public function testTranslateWithValues()
    {
        $translation = m::mock('Sepia\I18n\Translation')
            ->shouldReceive('getTranslation')
            ->andReturn('Hola %s')
            ->mock();

        $provider = $this->getI18nMock($translation);

        $i18n = new I18n($provider, 'es');
        $translation = $i18n->translate('hello', ['%s' => 'Bob']);

        $this->assertEquals('Hola Bob', $translation);
    }
}
