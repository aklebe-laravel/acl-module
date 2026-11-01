<?php

namespace Modules\Acl\app\Providers;

use App\Models\User as AppUser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use LdapRecord\Laravel\Events\Import\Synchronized;
use LdapRecord\Laravel\Events\Import\Saved;
use Modules\Acl\app\Console\LdapConnect;
use Modules\Acl\app\Console\SyncLdapCommand;
use Modules\Acl\app\Ldap\LdapCustomUser;
use Modules\Acl\app\Ldap\Rules\OnlyAppUsersRule;
use Modules\Acl\app\Listeners\AssignFallbackEmail;
use Modules\Acl\app\Listeners\SyncLdapGroups;
use Modules\Acl\app\Models\AclGroupUser;
use Modules\Acl\app\Models\User as ModuleUser;
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
    public function register(): void
    {
        // add aliases before parent::register() ...
        $modelList = ModuleService::getAllClassesInPath($this->moduleName, 'model', true, [AclGroupUser::class]);
        $this->modelAliases = array_merge($this->modelAliases, $modelList);

        parent::register();

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(ScheduleServiceProvider::class);

        $this->app->singleton(UserService::class, UserService::class);
        $this->app->singleton('acl_user_service', UserService::class);

        // user model of this module, see SystemBaseServiceProvider::register()
        $this->app->bind(AppUser::class, ModuleUser::class);

        // Load Modules/Acl/config/ldap.php to the global config('ldap') Key
        $this->mergeConfigFrom(module_path('Acl', 'config/ldap.php'), 'ldap');
    }

    /**
     * @return void
     */
    public function boot(): void
    {
        parent::boot();

        if ($this->app->runningInConsole()) {
            $this->commands([
                LdapConnect::class,
                SyncLdapCommand::class,
            ]);
        }

        $this->registerAuthProviders();

        Event::listen(Synchronized::class,
            AssignFallbackEmail::class);
        Event::listen(Saved::class, SyncLdapGroups::class);

    }

    /**
     * Register ldap
     *
     * @return void
     */
    protected function registerAuthProviders(): void
    {
        if (config('ldap.enabled', false)) {
            // start provider tree
            Config::set('auth.providers.users', [
                // LDAP-Driver
                'driver' => 'ldap',

                // root ldap 'model':
                'model'  => LdapCustomUser::class,

                'rules' => [
                    OnlyAppUsersRule::class,
                ],

                'scopes'          => [],

                // which is the login field?
                'locate_users_by' => config('ldap.attributes.guid_auth_name', 'uid'), // 'uid' or 'sAMAccountName' or something else

                // database
                'database'        => [
                    // the local user eloquent model
                    'model'           => AppUser::class,

                    // save password hashes
                    'sync_passwords'  => true,

                    // db column to identify
                    'guid_column'     => 'guid',

                    // Assign: [ Local DB Column => LDAP Attribute ]
                    'sync_attributes' => [
                        'name'  => 'cn',
                        'email' => 'mail',
                    ],

                    // How to match db users?
                    'sync_existing'   => [
                        'email' => 'mail',
                    ],
                ],
            ]);
        } else {
            //// --- LDAP disabled ---
            //Config::set('auth.providers.users', [
            //    'driver' => 'eloquent',
            //    'model'  => env('AUTH_MODEL', AppUser::class),
            //]);

        }

        // Ensure 'web' Guard will use this provider
        Config::set('auth.guards.web.provider', 'users');
    }

}
