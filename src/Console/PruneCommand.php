<?php

namespace TTBooking\ViteManager\Console;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use TTBooking\ViteManager\Facades\Vite;

#[AsCommand(name: 'vite:prune')]
class PruneCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vite:prune {app?* : Vite application to prune}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned Vite assets';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $apps = $this->argument('app') ?: [
            ...array_keys(config('vite.apps', [])),
            ...config('vite.prune', []),
        ];

        foreach ($apps as $app) {
            Vite::app($app)->prune();
        }

        $this->components->info('Orphaned Vite assets cleaned up successfully.');

        return 0;
    }
}
