<?php

namespace Modules\Acl\app\Models;

use App\Models\User as AppUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use Modules\Acl\app\Models\Base\UserTrait;

/**
 * @mixin IdeHelperUser
 */
class User extends AppUser implements LdapAuthenticatable
{
    //use TraitAttributeAssignment;
    //use TraitBaseModel;
    use HasFactory, Notifiable, UserTrait, AuthenticatesWithLdap;

    /**
     *
     */
    const string ATTRIBUTE_MODEL_IDENT = AppUser::class;

    /**
     * guarded [] too slow in user ?!?!
     * This way we redefine fillable
     *
     * @var array
     */
    // protected $guarded = [];
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_visited_at',
        'shared_id',
        'is_enabled',
        'is_deleted',
        'options',
        'order_to_delete_at',
        'guid',
        'origin',
        'domain',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at'  => 'datetime:Y-m-d H:i:s',
        'last_visited_at'    => 'datetime:Y-m-d H:i:s',
        'order_to_delete_at' => 'datetime:Y-m-d H:i:s',
        'password'           => 'hashed',
        'options'            => 'array',
        'is_enabled'         => 'boolean',
        'is_deleted'         => 'boolean',
    ];

    /**
     * @todo: remove this later, use $fillable
     * @var array
     */
    protected $guarded = [];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        parent::booted();

        static::creating(function (self $user) {
            // Automatically fill the "extended public user id" if it is empty
            if (empty($user->shared_id)) {
                $user->shared_id = uniqid('epuid_');
            }
        });
    }

    /**
     * @return bool
     */
    public function canLogin(): bool
    {
        return ($this->is_enabled && !$this->is_deleted && !$this->order_to_delete_at);
    }

    /**
     * @return bool
     */
    public function isLdapUser(): bool
    {
        return !empty($this->guid);
    }

    /**
     * @return $this
     */
    public function makeWithDefaults(array $attributes): static
    {
        $this->setRawAttributes(array_merge([
            'name'              => Str::uuid()
                ->toString(),
            'email'             => Str::uuid()
                                       ->toString().'@local.test',
            'shared_id'         => uniqid('js_suid_'),
            'email_verified_at' => now(),
            'password'          => '1234567',
            'remember_token'    => Str::random(10),
        ], $attributes));

        return $this;
    }

    /**
     * User could be created by different channels like telegram, and may fake email.
     *
     * @return bool
     */
    public function hasFakeEmail(): bool
    {
        // .test and .local ar official reserved by IETF and are not part of DNS,
        // so it's not needed and NOT WANTED to declare them here
        if (preg_match('#^.+?@(fake\..*|example\..*)$#', $this->email)) {
            return true;
        }

        return false;
    }

    /**
     * True is user has a valid email.
     *
     * @return bool
     */
    public function canUseEmail(): bool
    {
        return !!$this->email && !$this->hasFakeEmail();
    }
}
