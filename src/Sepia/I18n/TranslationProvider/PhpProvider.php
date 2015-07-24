<?php

namespace Sepia\I18n\TranslationProvider;

use Sepia\I18n\Exception\TranslationNotFound;
use Sepia\I18n\Translation;

/**
 * Gets data from php files containing arrays.
 *
 * @example
 *
 * <?php
 * // es-es.php
 * return [
 *     // Simple translation
 *     'Hello' => 'Hola',
 *
 *      // Translation with multiple contexts/plurals
 *     'Has borrado %s ficheros' => [
 *         'one' => 'Has borrado %s fichero',
 *         'other' => 'Has borrado %s ficheros'
 *     ]
 * ];
 */
class PhpProvider implements TranslationProviderInterface
{
    /**
     * @var string[]
     */
    protected $paths;

    /**
     * @var []
     */
    protected $cache;

    /**
     * Constructor
     *
     * todo implement external cache manager.
     */
    public function __construct($paths)
    {
        $this->paths = $paths;
        $this->cache = [];
    }

    /**
     * {@inheritdoc}
     */
    public function get($msgid, $language)
    {
        if (!isset($this->cache[$language])) {
            $this->loadLanguageFiles($language);
        }

        if (!isset($this->cache[$language][$msgid])) {
            throw new TranslationNotFound(
                sprintf(
                    'Could not found translation of "%s" for language "%s"',
                    $msgid,
                    $language
                )
            );
        }

        $data = $this->cache[$language][$msgid];

        $translation = new Translation($msgid, $data);

        return $translation;
    }

    /**
     * Load files from disk where translations are stored.
     *
     * @param  string $language [description]
     */
    protected function loadLanguageFiles($language)
    {
        $parts = explode('-', $language);

        // New translation table
        $table = array();

        do {
            $path = implode(DIRECTORY_SEPARATOR, $parts);
            $files = $this->fileFinder($path);

            if (count($files) > 0) {
                $t = [];
                foreach ($files as $file) {
                    $t = array_merge($t, require($file));
                }

                $table+= $t;
            }

            array_pop($parts);
        } while ($parts);

        $this->cache[$language] = $table;
    }

    /**
     * Search for files in all folders configured.
     *
     * @param  string $file
     *
     * @return string[]
     */
    protected function fileFinder($file)
    {
        $found = [];
        $file = '/i18n/'.$file.'.php';
        foreach ($this->paths as $path) {
            if (is_file($path.$file)) {
                $found[] = $path.$file;
            }
        }

        return $found;
    }
}
