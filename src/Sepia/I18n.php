<?php

namespace Sepia;

use Sepia\I18n\Translation;
use Sepia\I18n\TranslationProvider\TranslationProviderInterface;
use Sepia\I18n\Exception\TranslationNotFound;

/**
 * I18n
 */
class I18n
{
    /**
     * @var TranslationProviderInterface
     */
    protected $translationProvider;

    /**
     * @var string target language: en-use, es-es, zh-cn, etc
     */
    protected $language;

    /**
     * Constructor.
     * @param TranslationProviderInterface $translationProvider [description]
     * @param string  $language [<description>]
     */
    public function __construct(TranslationProviderInterface $translationProvider, $language)
    {
        $this->translationProvider = $translationProvider;
        $this->language = $language;
    }

    /**
     * [translate description]
     *
     * @param string      $msgid    String to translate
     * @param array|null  $values   Param values to insert
     * @param string|null $language Target language
     *
     * @return string
     */
    public function translate($msgid, array $values = null, $language = null)
    {
        return $this->translateContext($msgid, null, $values, $language);
    }

    /**
     * [translateContext description]
     * @param  string      $msgid    [description]
     * @param  string      $context  [description]
     * @param  array|null  $values   [description]
     * @param  string|null $language [description]
     *
     * @return string
     */
    public function translateContext($msgid, $context, array $values = null, $language = null)
    {
        $translation = $this->getTranslation($msgid, $language);

        if ($context !== null) {
            $context = $this->resolveContext($context, $language);
        }

        $string = $translation->getTranslation($context);

        return empty($values) ? $string : strtr($string, $values);
    }


    /**
     * Load the Translation object.
     *
     * @param  [type] $msgid [description]
     * @param  [type] $lang  [description]
     * @return Translation
     */
    private function getTranslation($msgid, $lang = null)
    {
        $lang = ($lang === null ? $this->language : $lang);

        try {
            $translation = $this->translationProvider->get($msgid, $lang);
        } catch (TranslationNotFound $e) {
            // As a fallback, return the same string as a translation.
            $translation = new Translation($msgid, $msgid);
        }

        return $translation;
    }

    /**
     * [resolveContext description]
     * @param  string $context
     *
     * @return string
     */
    private function resolveContext($context, $language)
    {
        if (is_numeric($context)) {
            // Get plural context
        } else {
            // Leave context as is
        }

        return $context;
    }
}
