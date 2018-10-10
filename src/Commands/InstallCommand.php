<?php

namespace SecTheater\Marketplace\Commands;

use Illuminate\Console\Command;
use SecTheater\Market\Providers\MarketplaceServiceProvider;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sectheater-market:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'setup the marketplace.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->call('vendor:publish', ['--tag' => 'marketplace']);
    }
}