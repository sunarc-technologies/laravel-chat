<?php

namespace Sunarc\LaravelChat\Console;

use Illuminate\Console\Command;
use Sunarc\LaravelChat\Console\PackageResources\AssetsResource;
use Sunarc\LaravelChat\Console\PackageResources\ConfigResource;
use Sunarc\LaravelChat\Console\PackageResources\EventResource;

class LaravelChatInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chat:install
        {--type=basic : The installation type: basic (default)}
        {--only=* : To install only specific resources: assets, config}
        {--force : To force the overwrite of existing files}
        {--interactive : The installation will guide you through the process}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all the required files for Laravel Chat, and additional resources';

    /**
     * Array with all the available package resources.
     *
     * @var array
     */
    protected $pkgResources;

    /**
     * Array with all the installed packages on the current execution.
     *
     * @var array
     */
    protected $installedResources;

    /**
     * Array with the resources available for the --only options.
     *
     * @var array
     */
    protected $optOnlyResources;

    /**
     * Array with the resources available for the --type options.
     *
     * @var array
     */
    protected $optTypeResources;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        // Fill the array with the package resources.

        $this->pkgResources = [
            'assets' => new AssetsResource(),
            'config' => new ConfigResource(),
            'events' => new EventResource(),
        ];

        // Add the resources related to each available --type option.

        $basic = ['assets', 'config', 'events'];

        $this->optTypeResources = [
            'basic' => $basic,
        ];

        // Add the resources related to each available --only option.

        $this->optOnlyResources = [
            'assets' => ['assets'],
            'config' => ['config'],
            'events' => ['events'],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Reset the variable that keep track of the installed packages.

        $this->installedResources = [];

        // Check if option --only is used. In this case, install the specified
        // parts and return.

        if ($optValues = $this->option('only')) {
            $this->handleOptions($optValues, $this->optOnlyResources, 'only');

            return;
        }

        // Handle the --type option. Default value for this option is "basic"
        // installation type.

        $optValue = $this->option('type');
        $this->handleOption($optValue, $this->optTypeResources, 'type');

        $this->line('The installation is complete.');
    }

    /**
     * Handle multiple option values.
     *
     * @param  array  $values  An array with the option values
     * @param  array  $resources  An array with the resources of each option
     * @param  string  $opt  Descriptive name of the handled option
     * @return void
     */
    protected function handleOptions($values, $resources, $opt)
    {
        foreach ($values as $value) {
            $this->handleOption($value, $resources, $opt);
        }
    }

    /**
     * Handle an option value.
     *
     * @param  string  $value  A string with the option value
     * @param  array  $resources  An array with the resources of each option
     * @param  string  $opt  Descriptive name of the handled option
     * @return void
     */
    protected function handleOption($value, $resources, $opt)
    {
        if (!isset($resources[$value])) {
            $this->comment("The option --{$opt}={$value} is invalid!");

            return;
        }

        // Install all the resources related to the option value.

        $this->exportPackageResources(...$resources[$value]);
    }

    /**
     * Install multiple packages resources.
     *
     * @param  string  $resources  The resources to install
     * @return void
     */
    protected function exportPackageResources(...$resources)
    {
        foreach ($resources as $resource) {

            // Check if resource was already installed on the current command
            // execution. This can happen, for example, when using:
            // php artisan --type=full --with=auth_views

            if (isset($this->installedResources[$resource])) {
                continue;
            }

            $this->exportPackageResource($resource);
            $this->installedResources[$resource] = true;
        }
    }

    /**
     * Install a package resource.
     *
     * @param  string  $resource  The keyword of the resource to install
     * @return void
     */
    protected function exportPackageResource($resource)
    {
        $resource = $this->pkgResources[$resource];
        $installMsg = $resource->getInstallMessage('install');
        $overwriteMsg = $resource->getInstallMessage('overwrite');
        $successMsg = $resource->getInstallMessage('success');

        // Check if the --interactive option is enabled.

        if ($this->option('interactive') && !$this->confirm($installMsg)) {
            return;
        }

        // Check for overwrite warning.

        $isOverwrite = !$this->option('force') && $resource->exists();

        if ($isOverwrite && !$this->confirm($overwriteMsg)) {
            return;
        }

        // Install the resource.

        $resource->install();
        $this->info($successMsg);
    }
}
