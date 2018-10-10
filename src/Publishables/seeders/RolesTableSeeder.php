<?php

use Illuminate\Database\Seeder;
use App\Role;
class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create([
            'slug'        => 'admin',
            'name'        => 'Administrator',
            'permissions' => [
                'create-category'      => true,
                'update-category'      => true,
                'delete-category'      => true,
                'view-category'        => true,
                'upgrade-user'    => true,
                'downgrade-user'  => true,
                'review-product' => true,
                'create-product' => true,
                'edit-product' => true,
                'delete-product' => true
            ],
        ]);
        $moderator = Role::create([
            'slug'        => 'moderator',
            'name'        => 'Moderator',
            'permissions' => [
                'create-category'      => true,
                'update-category'      => true,
                'view-category'        => true,
                'delete-category'      => true,
                'upgrade-user'    => false,
                'downgrade-user'  => false,
                'review-product' => true,
                'create-product' => true,
                'edit-product' => true,
                'delete-product' => false

            ],
        ]);
        $user = Role::create([
            'name'        => 'Normal User',
            'slug'        => 'user',
            'permissions' => [
                'create-category'      => false,
                'view-category'        => false,
                'update-category'      => false,
                'delete-category'      => false,
                'upgrade-user'    => false,
                'downgrade-user'  => false,
                'review-product' => true,
                'create-product' => false,
                'edit-product' => false,
                'delete-product' => false

            ],
        ]);
    }
}
