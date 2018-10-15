<?php

use Illuminate\Database\Seeder;
use SecTheater\Marketplace\Models\EloquentRole;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = EloquentRole::create([
            'name'        => 'Normal User',
            'slug'        => 'user',
            'permissions' => [
                'create-category'      => false,
                'view-category'        => false,
                'update-category'      => false,
                'delete-category'      => false,
                'upgrade-user'    => false,
                'downgrade-user'  => false,
                'review-product' => false,
                'create-product' => false,
                'edit-product' => false,
                'delete-product' => false

            ],
        ]);
        $admin = EloquentRole::create([
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
        $moderator = EloquentRole::create([
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
        
    }
}
