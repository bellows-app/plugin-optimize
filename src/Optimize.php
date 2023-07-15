<?php

namespace Bellows\Plugins;

use Bellows\PluginSdk\Contracts\Deployable;
use Bellows\PluginSdk\Facades\Artisan;
use Bellows\PluginSdk\Facades\Deployment;
use Bellows\PluginSdk\Facades\DeployScript;
use Bellows\PluginSdk\Plugin;
use Bellows\PluginSdk\PluginResults\CanBeDeployed;
use Bellows\PluginSdk\PluginResults\DeploymentResult;

class Optimize extends Plugin implements Deployable
{
    use CanBeDeployed;

    public function defaultForDeployConfirmation(): bool
    {
        return true;
    }

    public function deploy(): ?DeploymentResult
    {
        return DeploymentResult::create()->updateDeployScript(
            fn () => DeployScript::addBeforePHPReload([
                Artisan::inDeployScript('config:cache'),
                Artisan::inDeployScript('route:cache'),
                Artisan::inDeployScript('view:cache'),
                Artisan::inDeployScript('event:cache'),
            ])
        );
    }

    public function shouldDeploy(): bool
    {
        return !Deployment::site()->isInDeploymentScript([
            'config:cache',
            'route:cache',
            'view:cache',
            'event:cache',
        ]);
    }
}
