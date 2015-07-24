<?php

namespace Sepia\I18n;

class Translation
{
    const ONE = 'one';
    const TWO = 'two';
    const FEW = 'few';
    const MANY = 'many';
    const OTHER = 'other';
    const ZERO = 'zero';

    protected $msgid;

    protected $translation;

    protected $hasContext;

    public function __construct($msgid, $translation)
    {
        $this->msgid = $msgid;
        $this->translation = $translation;
        $this->hasContext = false;

        if (is_array($this->translation)) {
            $this->hasContext = true;
        }
    }

    /**
     * Returns specified form of a string translation.
     * If no translation exists, the original string will be returned.
     *
     * @param  string $context
     * @return string
     */
    public function getTranslation($context = null)
    {
        if ($this->hasContext) {
            if (isset($this->translation[$context])) {
                return $this->translation[$context];
            } elseif (isset($this->translation[self::OTHER])) {
                return $this->translation[self::OTHER];
            } else {
                // return first element
                return $this->msgid;
            }
        } else {
            $string = $this->translation;
        }

        return $string;
    }
}
