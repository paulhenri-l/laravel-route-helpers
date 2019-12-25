<?php

namespace PaulhenriL\LaravelRouteHelpers;

use Illuminate\Filesystem\Filesystem;

class HelpersLoader
{
    /**
     * The Filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * The HelpersGenerator instance.
     *
     * @var HelpersGenerator
     */
    protected $generator;

    /**
     * The HelpersLoader config.
     *
     * @var array
     */
    protected $config;

    /**
     * HelpersLoader constructor.
     */
    public function __construct(
        Filesystem $files,
        HelpersGenerator $generator,
        array $config
    ) {
        $this->files = $files;
        $this->generator = $generator;
        $this->config = $config;
    }

    /**
     * Load the helpers file. Will create it if needed.
     */
    public function load()
    {
        if (!$this->files->exists($this->config['file_path'])) {
            $this->createFile();
        }

        $this->recompileIfNeeded();

        $this->files->getRequire($this->config['file_path']);
    }

    /**
     * Create the helpers file.
     */
    protected function createFile(): void
    {
        $this->replaceHelpersFile(
            $this->generator->generateHelpers()
        );
    }

    /**
     * Recompile the helpers file if needed.
     */
    protected function recompileIfNeeded(): void
    {
        if (config('app.env') !== 'local') {
            return;
        }

        $helpers = $this->generator->generateHelpers();

        if ($this->files->get($this->config['file_path']) !== $helpers) {
            $this->replaceHelpersFile($helpers);
        }
    }

    /**
     * Replace the helpers file with the given content.
     */
    protected function replaceHelpersFile(string $content)
    {
        $this->files->replace($this->config['file_path'], $content);
    }
}
