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

        \App\Models\User::factory()->create([
            'name'  => 'Kaung Myat Soe',
            'email' => 'kaungmyatsoe.m192@gmail.com',
            'photo' => $filePath,
        ]);

        \App\Models\User::factory()->create([
            'name'  => 'Ye Myat Sandi Oo',
            'email' => 'yemyatsandi@gmail.com',
            'photo' => $filePath,
        ]);

        \App\Models\User::factory()->create([
            'name'  => 'Thuyain Soe',
            'email' => 'thuyainsoe163361@gmail.com',
            'photo' => $filePath,
        ]);

        \App\Models\User::factory()->create([
            'name'  => 'Naing Aung Zaw',
            'email' => 'naingaung9863@gmail.com',
            'photo' => $filePath,
        ]);
    }
}
