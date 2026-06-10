<?php

namespace Database\Seeders;

use App\Models\ProfileSetting;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'name' => 'Gibran Amadeus',
            'role' => 'photographer & graphic desainer',
            'bio' => 'Saya memotret momen, ruang, dan karakter dengan pendekatan editorial yang bersih.',
            'email' => 'gibran.amadeus06@gmail.com',
            'location' => 'Surabaya, Indonesia',
            'availability' => 'Menerima sesi portrait, editorial, produk, dan dokumentasi visual untuk brand maupun personal.',
            'social_linkedin' => '#',
            'social_instagram' => '#',
            'social_behance' => '#',
            'avatar' => null,
        ];

        foreach ($settings as $key => $value) {
            ProfileSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
