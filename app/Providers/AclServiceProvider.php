<?php

namespace Modules\Acl\app\Providers;

use Modules\Acl\app\Models\AclGroupUser;
use Modules\Acl\app\Services\UserService;
use Modules\SystemBase\app\Providers\Base\ModuleBaseServiceProvider;
use Modules\SystemBase\app\Services\ModuleService;

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
        // add aliases before parent::register() ...
        $modelList = ModuleService::getAllClassesInPath($this->moduleName, 'model', true, [AclGroupUser::class]);
        $this->modelAliases = array_merge($this->modelAliases, $modelList);

        //// or manually ...
        //$this->modelAliases = array_merge($this->modelAliases, [
        //    'acl_group'    => AclGroup::class,
        //    'acl_resource' => AclResource::class,
        //]);

        parent::register();

        $this->app->register(RouteServiceProvider::class);
        app()->singleton(UserService::class, UserService::class);
    }

}
