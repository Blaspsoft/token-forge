<?php

namespace Blaspsoft\TokenForge\Console;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'token-forge:install')]
class InstallCommand extends Command
{
    use InstallsBladeStack, InstallsVueStack;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token-forge:install {stack : The development stack that should be installed (blade,vue)}
                            {--composer=global : Absolute path to the Composer binary which should be used to install packages}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install the Token Forge package';

    /**
     * Execute the console command.
     *
     * @return int|null
     */
    public function handle()
    {
        if ($this->argument('stack') === 'vue') {
            return $this->installVueStack();
        } elseif ($this->argument('stack') === 'blade') {
            return $this->installBladeStack();
        }

        $this->components->error('Invalid stack. Supported stacks are [blade], [vue].');
        
        return 1;
    }

    /**
     * Installs the given Composer Packages into the application.
     *
     * @param  bool  $asDev
     * @return bool
     */
    protected function requireComposerPackages(array $packages, $asDev = false)
    {
        $composer = $this->option('composer');

        if ($composer !== 'global') {
            $command = ['php', $composer, 'require'];
        }

        $command = array_merge(
            $command ?? ['composer', 'require'],
            $packages,
            $asDev ? ['--dev'] : [],
        );

        return (new Process($command, base_path(), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            }) === 0;
    }

    /**
     * Remove Tailwind dark classes from the given files.
     *
     * @return void
     */
    protected function removeDarkClasses(Finder $finder)
    {
        foreach ($finder as $file) {
            file_put_contents($file->getPathname(), preg_replace('/\sdark:[^\s"\']+/', '', $file->getContents()));
        }
    }
}
