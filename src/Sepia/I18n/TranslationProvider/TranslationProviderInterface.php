<?php

namespace Sepia\I18n\TranslationProvider;

use Sepia\I18n\Translation;

interface TranslationProviderInterface
{
    /**
     *
     * @param  string $msgid    [description]
     * @param  string $language [description]
     *
     * @return Translation
     */
    public function get($msgid, $language);
}
