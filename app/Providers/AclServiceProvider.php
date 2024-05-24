<?php

namespace Modules\Acl\app\Providers;

use Modules\Acl\app\Services\UserService;
use Modules\SystemBase\app\Providers\Base\ModuleBaseServiceProvider;

class AclServiceProvider extends ModuleBaseServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'Acl';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'acl';

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        parent::register();

        $this->app->register(RouteServiceProvider::class);
        app()->singleton(UserService::class, UserService::class);
    }

}
