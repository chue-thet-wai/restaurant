<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nowDate = now();
        $permissions = [
            [//table management
                'main_menu' => "Table Management",
                "sub_menu"  => "Table Management",
                "action"    => "list",
                "method"    => "TableManagementController@index",
                "menu_route" => "table-management.index",
                "type"       => "route"
            ],
            [
                'main_menu' => "Table Management",
                "sub_menu"  => "Table Management",
                "action"    => "create",
                "method"    => "TableManagementController@addTable"
            ],
            [
                'main_menu' => "Table Management",
                "sub_menu"  => "Table Management",
                "action"    => "store",
                "method"    => "TableManagementController@addTable"
            ],
            [
                'main_menu' =>"Table Management",
                "sub_menu"  =>"Table Management",
                "action"    =>"edit",
                "method"    =>"TableManagementController@toggleAvailability"
            ],
            [
                'main_menu' => "Table Management",
                "sub_menu"  => "Table Management",
                "action"    => "update",
                "method"    => "TableManagementController@toggleAvailability"
            ],
            [
                'main_menu' =>"Table Management",
                "sub_menu"  =>"Table Management",
                "action"    =>"delete",
                "method"    =>"TableManagementController@destroy"
            ],
            [//reservation
                'main_menu' => "Reservation",
                "sub_menu"  => "Reservation",
                "action"    => "list",
                "method"    => "ReservationController@index",
                "menu_route" => "admin_reservation.index",
                "type"       => "route"
            ],
            [
                'main_menu' => "Reservation",
                "sub_menu"  => "Reservation",
                "action"    => "create",
                "method"    => "ReservationController@create"
            ],
            [
                'main_menu' => "Reservation",
                "sub_menu"  => "Reservation",
                "action"    => "store",
                "method"    => "ReservationController@store"
            ],
            [
                'main_menu' =>"Reservation",
                "sub_menu"  =>"Reservation",
                "action"    =>"edit",
                "method"    =>"ReservationController@edit"
            ],
            [
                'main_menu' => "Reservation",
                "sub_menu"  => "Reservation",
                "action"    => "update",
                "method"    => "ReservationController@update"
            ],
            [//order management
                'main_menu' => "Order Management",
                "sub_menu"  => "Order Management",
                "action"    => "list",
                "method"    => "OrderManagementController@index",
                "menu_route" => "order_management.index",
                "type"       => "route"
            ],
            [
                'main_menu' => "Order Management",
                "sub_menu"  => "Order Management",
                "action"    => "view",
                "method"    => "OrderManagementController@show"
            ],
            [//restaurant menu
                'main_menu' => "Restaurant Menu",
                "sub_menu"  => "Restaurant Menu",
                "action"    => "list",
                "method"    => "RestaurantMenuController@index",
                "menu_route" => "restaurant_menu.index",
                "type"       => "route"
            ],
            [
                'main_menu' => "Restaurant Menu",
                "sub_menu"  => "Restaurant Menu",
                "action"    => "create",
                "method"    => "RestaurantMenuController@create"
            ],
            [
                'main_menu' => "Restaurant Menu",
                "sub_menu"  => "Restaurant Menu",
                "action"    => "store",
                "method"    => "RestaurantMenuController@store"
            ],
            [
                'main_menu' =>"Restaurant Menu",
                "sub_menu"  =>"Restaurant Menu",
                "action"    =>"edit",
                "method"    =>"RestaurantMenuController@edit"
            ],
            [
                'main_menu' => "Restaurant Menu",
                "sub_menu"  => "Restaurant Menu",
                "action"    => "update",
                "method"    => "RestaurantMenuController@update"
            ],
            [
                'main_menu' =>"Restaurant Menu",
                "sub_menu"  =>"Restaurant Menu",
                "action"    =>"delete",
                "method"    =>"RestaurantMenuController@destroy"
            ],
            [//branch
                'main_menu' => "Branch",
                "sub_menu"  => "Branch",
                "action"    => "list",
                "method"    => "BranchController@index",
                "menu_route" => "branch.index",
                "type"       => "route"
            ],
            [
                'main_menu' => "Branch",
                "sub_menu"  => "Branch",
                "action"    => "create",
                "method"    => "BranchController@create"
            ],
            [
                'main_menu' => "Branch",
                "sub_menu"  => "Branch",
                "action"    => "store",
                "method"    => "BranchController@store"
            ],
            [
                'main_menu' =>"Branch",
                "sub_menu"  =>"Branch",
                "action"    =>"edit",
                "method"    =>"BranchController@edit"
            ],
            [
                'main_menu' => "Branch",
                "sub_menu"  => "Branch",
                "action"    => "update",
                "method"    => "BranchController@update"
            ],
            [
                'main_menu' =>"Branch",
                "sub_menu"  =>"Branch",
                "action"    =>"delete",
                "method"    =>"BranchController@destroy"
            ],
            [//admin user management
                'main_menu' => "User Management",
                "sub_menu"  => "User Management",
                "action"    => "list",
                "method"    => "UserController@index",
                "menu_route" => "users.index",
                "type"       => "route"
            ],
            [
                'main_menu' => "User Management",
                "sub_menu"  => "User Management",
                "action"    => "create",
                "method"    => "UserController@create"
            ],
            [
                'main_menu' => "User Management",
                "sub_menu"  => "User Management",
                "action"    => "store",
                "method"    => "UserController@store"
            ],
            /*[
                'main_menu' =>"User Management",
                "sub_menu"  =>"User Management",
                "action"    =>"view",
                "method"    =>"UserController@show"
            ],*/
            [
                'main_menu' =>"User Management",
                "sub_menu"  =>"User Management",
                "action"    =>"edit",
                "method"    =>"UserController@edit"
            ],
            [
                'main_menu' => "User Management",
                "sub_menu"  => "User Management",
                "action"    => "update",
                "method"    => "UserController@update"
            ],
            [
                'main_menu' =>"User Management",
                "sub_menu"  =>"User Management",
                "action"    =>"delete",
                "method"    =>"UserController@destroy"
            ],
            [//role management
                'main_menu'  => "Role Management",
                "sub_menu"   => "Role Management",
                "action"     => "list",
                "method"     => "RoleController@index",
                "menu_route" => "roles.index",
                "type"       => "route"
            ],
            [
                'main_menu' => "Role Management",
                "sub_menu"  => "Role Management",
                "action"    => "create",
                "method"    => "RoleController@create"
            ],
            [
                'main_menu' => "Role Management",
                "sub_menu"  => "Role Management",
                "action"    => "store",
                "method"    => "RoleController@store"
            ],
            /*[
                'main_menu' =>"Role Management",
                "sub_menu"  =>"Role Management",
                "action"    =>"view",
                "method"    =>"RoleController@show"
            ],*/
            [
                'main_menu' =>"Role Management",
                "sub_menu"  =>"Role Management",
                "action"    =>"edit",
                "method"    =>"RoleController@edit"
            ],
            [
                'main_menu' => "Role Management",
                "sub_menu"  => "Role Management",
                "action"    => "update",
                "method"    => "RoleController@update"
            ],
            [
                'main_menu' =>"Role Management",
                "sub_menu"  =>"Role Management",
                "action"    =>"delete",
                "method"    =>"RoleController@destroy"
            ],
            [//setting
                'main_menu' =>"Setting",
                "sub_menu"  =>"Setting",
                "action"    =>"edit",
                "method"    =>"SettingController@editProfile"
            ],
            [
                'main_menu' => "Setting",
                "sub_menu"  => "Setting",
                "action"    => "update",
                "method"    => "SettingController@updateProfile"
            ],
            [
                'main_menu' =>"Reporting",
                "sub_menu"  =>"User List",
                "action"    =>"download",
                "method"    =>"UserListReportController@userListReport"
            ],
        ];

        foreach ($permissions as $permission) {
            $menu = Menu::firstOrCreate(
                [
                    'main_menu' => $permission['main_menu'],
                    'sub_menu'  => $permission['sub_menu']
                ],
                [
                    'menu_route' => isset($permission['menu_route']) ? $permission['menu_route'] : null,
                    'type'       => isset($permission['type']) ? $permission['type'] : null,
                    'created_at' => $nowDate,
                    'updated_at' => $nowDate
                ]
            );

            $perm = Permission::firstOrCreate(
                [
                    'menu_id' => $menu->id,
                    'name' => $permission['method']
                ],
                [
                    'action'     => $permission['action'],
                    'created_at' => $nowDate,
                    'updated_at' => $nowDate
                ]
            );

            $role = Role::firstOrCreate(['name' => 'admin']);
            $role->permissions()->syncWithoutDetaching([$perm->id]);
        }
    }
}
