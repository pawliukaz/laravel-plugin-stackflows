<?php

namespace Stackflows\StackflowsPlugin;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Stackflows\StackflowsPlugin\Commands\ServiceTaskSubscribeCommand;
use Stackflows\StackflowsPlugin\Exceptions\InvalidConfiguration;
use Stackflows\StackflowsPlugin\Http\Client\GatewayClient;

class StackflowsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('stackflows')
            ->hasConfigFile('stackflows')
            ->hasCommands([ServiceTaskSubscribeCommand::class]);
    }

    public function packageRegistered()
    {
        $this->app->bind(
            GatewayClient::class,
            function () {
                $this->guardAgainstInvalidConfiguration(config('stackflows'));

                return new GatewayClient(config('stackflows.authToken'), config('stackflows.gatewayHost'));
            }
        );


        $this->app->tag(config('stackflows.service_task_executors'), 'stackflows-service-task');
//        $this->app->tag(config('stackflows.user_task_sync'), 'stackflows-user-task');
    }

    /**
     * @throws InvalidConfiguration
     */
    protected function guardAgainstInvalidConfiguration(array $config = null)
    {
        if (empty($config['gatewayHost'])) {
            throw InvalidConfiguration::gatewayHostNotSpecified();
        }

        if (empty($config['authToken'])) {
            throw InvalidConfiguration::authTokenNotSpecified();
        }
    }
}
