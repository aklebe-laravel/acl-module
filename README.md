## Acl Module

A module for the [Mercy Scaffold](https://github.com/aklebe-laravel/mercy-scaffold).

Access Control List: groups and resources in the database allow or deny users or user groups access to objects.
Optional: users and groups from an LDAP server (`directorytree/ldaprecord-laravel`).

## Installation

Add the module to `requires` in the project's `setup.yaml`, see
[Require modules and themes](https://github.com/aklebe-laravel/deploy-env-module#require-modules-and-themes).

## LDAP

### Configuration

With the Mercy deployment the main LDAP values come from the `ldap` section of `setup.yaml`,
the deployment writes them into the `.env` (do not edit them there):

| `setup.yaml`                     | Env                              | Example                                           |
|----------------------------------|----------------------------------|---------------------------------------------------|
| `ldap.enabled`                   | `LDAP_ENABLED`                   | `true`                                            |
| `ldap.host`                      | `LDAP_HOST`                      | `ldap.example.com`                                |
| `ldap.port`                      | `LDAP_PORT`                      | `389`                                             |
| `ldap.ssl`                       | `LDAP_SSL`                       | `true`                                            |
| `ldap.username`                  | `LDAP_USERNAME`                  | `cn=ldap-search-user,ou=users,dc=ldap,dc=example` |
| `ldap.password`                  | `LDAP_PASSWORD`                  |                                                   |
| `ldap.base_dn`                   | `LDAP_BASE_DN`                   | `dc=ldap,dc=example`                              |
| `ldap.guid_auth_name`            | `LDAP_GUID_AUTH_NAME`            | `cn`, `uid`, `sAMAccountName`                     |
| `ldap.users_allowed_from_groups` | `LDAP_USERS_ALLOWED_FROM_GROUPS` | `Members,Admins`                                  |
| `ldap.import_cron`               | `LDAP_IMPORT_CRON`               | `25 */4 * * *` (sync by the scheduler)            |
| `ldap.object_class_groups`       | `LDAP_OBJECT_CLASS_GROUPS`       | `posixGroup`                                      |
| `ldap.object_class_users`        | `LDAP_OBJECT_CLASS_USERS`        | `posixAccount`                                    |

Further env keys (defaults in the env templates): `LDAP_CACHE`, `LDAP_LOGGING`, `LDAP_CONNECTION`,
`LDAP_OPT_X_TLS_REQUIRE_CERT`, `LDAP_TIMEOUT`, `LDAP_CONNECT_TIMEOUT`, `LDAP_TLS`.

### Console

Import the groups and the users of the groups in `LDAP_USERS_ALLOWED_FROM_GROUPS`
(order: groups, users, then the links between them). Users whose email already belongs to another user
are skipped and logged; the command ends with a summary and a non-zero exit code if something failed:

```
php artisan ldap:sync-all
```

Test a login:

```
php artisan ldap:connect login "peter" "<password>"
```

Show a user from the LDAP server:

```
php artisan ldap:connect get "peter"
```

Save the groups of an LDAP user to the local database:

```
php artisan ldap:connect groups "peter"
```

In a dev environment prefix the commands with `sail` (`sail artisan ldap:sync-all`).

## License

MIT, see [LICENSE.md](LICENSE.md).
