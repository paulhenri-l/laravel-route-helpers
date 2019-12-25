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
    public function __construct(Filesystem $files, HelpersGenerator $generator, array $config)
    {
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

        if ($this->helpersFileNeedsRecompilation()) {
            $this->createFile();
        }

        $this->files->getRequire($this->config['file_path']);
    }

    /**
     * Create the helpers file.
     */
    protected function createFile()
    {
        $helpers = $this->generator->generateHelpers();

        $this->files->replace($this->config['file_path'], $helpers);
    }

    /**
     * Check if the helpers files needs to be recompiled.
     */
    protected function helpersFileNeedsRecompilation()
    {
        if (config('app.env') !== 'local') {
            return false;
        }

        $helpers = $this->generator->generateHelpers();

        return $this->files->get($this->config['file_path']) !== $helpers;
    }
}
