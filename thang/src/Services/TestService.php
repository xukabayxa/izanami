<?php

namespace Thang\Services;

use Modules\STO\Helper\RoleHelper\RoleHelper;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TestService
{
    /**
     * @param array $permissions
     * @param string $roleName
     * @param string $guardName
     */
    public function setPermissionToRole(array $permissions, string $roleName, string $guardName = 'web')
    {
        if (!empty($permissions)) {
            foreach ($permissions as $key => $value) {
                Permission::query()->updateOrCreate(
                    ['name' => $key],
                    ['guard_name' => $value]
                );
            }

            /** @var Role $role */
            $role = Role::findOrCreate($roleName, $guardName);

            $role->givePermissionTo($this->getPermission($permissions));
        }
    }

    /**
     * @param $permissions
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getPermission($permissions)
    {
        $permissionsCurrent = Permission::all()->pluck('name')->toArray();

        $permissions = array_intersect($permissions, $permissionsCurrent);

        return Permission::query()->whereIn('name', $permissions)->get();
    }
}
