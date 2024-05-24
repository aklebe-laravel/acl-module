<?php


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Acl\app\Services\UserService;
use Modules\SystemBase\tests\TestCase;

class UserServiceTest extends TestCase
{
    /**
     * The Illuminate\Foundation\Testing\RefreshDatabase trait does not migrate your database if your schema is up to date.
     * Instead, it will only execute the test within a database transaction. Therefore,
     * any records added to the database by test cases that do not use this trait may still exist in the database.
     * @see: https://laravel.com/docs/10.x/database-testing
     *
     * But seems like user table was truncated after assert failed.
     */
    // use RefreshDatabase;

    /**
     * Testing results of ModuleService::getModuleInfo()
     */
    public function test_get_next_available_user_name(): void
    {
        $success = true;
        DB::beginTransaction();
        try {
            $userService = app(UserService::class);
            $randomUser = app(\App\Models\User::class)->with([])->inRandomOrder()->first();

            // dynamic list of all suffixes
            $list = [];
            foreach ($userService->nameSuffixes as $nameSuffix) {
                $list[] = [
                    'name'   => $randomUser->name,
                    'expect' => $randomUser->name.' '.$nameSuffix,
                ];
            }
            // and one more to check counting
            foreach ($userService->nameSuffixes as $nameSuffix) {
                $list[] = [
                    'name'   => $randomUser->name,
                    'expect' => $randomUser->name.' '.$nameSuffix.' 2',
                ];
                break;
            }

            $usersCreated = [];
            foreach ($list as $item) {
                $name = $userService->getNextAvailableUserName($item['name']);
                Log::debug('Name and expected name: ', [$name, $item['expect']]);
                if ($name !== $item['expect']) {
                    $success = false;
                    break;
                }
                $user = app(\App\Models\User::class)->makeWithDefaults([
                    'name' => $name,
                ]);
                Log::debug('user saved:', [get_class($user), $user->name]);
                $user->save();
                $usersCreated[] = $user;
            }

            // foreach ($usersCreated as $user) {
            //     $user->delete();
            // }

        } catch (Exception $exception) {
            report($exception);
        }

        // unconditionally rollback
        DB::rollBack();

        $this->assertTrue($success);
    }
}
