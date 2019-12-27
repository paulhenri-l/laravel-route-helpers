<?php

namespace PaulhenriL\LaravelRouteHelpers\Helpers;

use Illuminate\Filesystem\Filesystem;

class Loader
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
     * @var Generator
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
        Generator $generator,
        array $config
    ) {
        $this->files = $files;
        $this->generator = $generator;
        $this->config = $config;
    }

    /**
     * Load the helpers file. Will create it if needed.
     */
    public function load(): void
    {
        $this->createFileIfNeeded();

        $this->recompileIfNeeded();

        $this->files->getRequire($this->config['file_path']);
    }

    /**
     * Force the file recompilation.
     */
    public function forceRecompile(): void
    {
        $this->replaceHelpersFile(
            $this->generator->generateHelpers()
        );
    }

    /**
     * Create the helpers file if needed.
     */
    protected function createFileIfNeeded(): void
    {
        if (!$this->files->exists($this->config['file_path'])) {
            $this->createFile();
        }
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
        if (!$this->config['recompilation_checks_enabled']) {
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
    protected function replaceHelpersFile(string $content): void
    {
        $this->files->replace($this->config['file_path'], $content);
    }
}
