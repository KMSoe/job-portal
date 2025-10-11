<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Modules\Storage\App\Classes\LocalStorage;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $localFilePath = public_path('sample_files/avator.png');
        $localFile     = new File($localFilePath);

        $storage  = new LocalStorage();
        $filePath = $storage->store('user_profiles', $localFile);

        // \App\Models\User::factory()->create([
        //     'name'  => 'Test User',
        //     'email' => 'test@example.com',
        //     'photo' => $filePath,
        // ]);

        \App\Models\User::factory()->create([
            'name'  => 'Alice',
            'email' => 'alice@example.com',
            'photo' => $filePath,
        ]);

        \App\Models\User::factory()->create([
            'name'  => 'Bob',
            'email' => 'bob@example.com',
            'photo' => $filePath,
        ]);

        \App\Models\User::factory()->create([
            'name'  => 'John',
            'email' => 'john@example.com',
            'photo' => $filePath,
        ]);

        \App\Models\User::factory()->create([
            'name'  => 'Mary',
            'email' => 'mary@example.com',
            'photo' => $filePath,
        ]);
    }
}
