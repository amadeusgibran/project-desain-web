<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            [
                'title' => 'Monolith Study',
                'slug' => 'monolith-study',
                'category' => 'Editorial',
                'cover_image' => 'images/portfolio_photography_gallery.png',
                'description' => 'Monolith Study adalah seri fotografi editorial yang membaca bentuk arsitektur sebagai ruang diam. Frame dibuat dengan garis tegas, kontras lembut, dan tone monokrom untuk membangun atmosfer kontemplatif. Fokus seri ini adalah menangkap hubungan antara cahaya, material, dan skala manusia.',
                'client' => 'Monolith Residence',
                'year' => '2026',
                'tools' => ['Canon EOS R6', '35mm Lens', 'Lightroom', 'Photoshop'],
                'images' => [
                    'images/portfolio_photography_gallery.png',
                    'images/contact_details.png',
                    'images/about_me_3d_character.png',
                ],
                'link' => 'https://example.com/monolith-study',
                'order' => 1,
                'is_published' => true,
            ],
            [
                'title' => 'Void & Volume',
                'slug' => 'void-volume',
                'category' => 'Architecture',
                'cover_image' => 'images/contact_details.png',
                'description' => 'Void & Volume adalah dokumentasi visual ruang interior dengan pendekatan tenang dan presisi. Setiap foto menekankan pertemuan antara bayangan, tekstur, dan volume ruangan. Seri ini dibuat untuk membantu studio desain menampilkan karakter ruang tanpa kehilangan nuansa naturalnya.',
                'client' => 'Volume House',
                'year' => '2026',
                'tools' => ['Sony A7 IV', '24-70mm Lens', 'Natural Light', 'Lightroom'],
                'images' => [
                    'images/contact_details.png',
                    'images/portfolio_photography_gallery.png',
                    'images/about_me_3d_character.png',
                ],
                'link' => 'https://example.com/void-volume',
                'order' => 2,
                'is_published' => true,
            ],
            [
                'title' => 'Helix Series',
                'slug' => 'helix-series',
                'category' => 'Portrait',
                'cover_image' => 'images/about_me_3d_character.png',
                'description' => 'Helix Series adalah sesi portrait konseptual yang mengeksplorasi gestur, siluet, dan identitas personal. Pengambilan gambar diarahkan untuk terasa intim, minimal, dan kuat secara karakter. Hasilnya digunakan sebagai materi profil kreatif, editorial personal, dan visual campaign.',
                'client' => 'Personal Commission',
                'year' => '2026',
                'tools' => ['Studio Light', '85mm Lens', 'Color Grading', 'Retouching'],
                'images' => [
                    'images/about_me_3d_character.png',
                    'images/portfolio_photography_gallery.png',
                    'images/contact_details.png',
                ],
                'link' => 'https://example.com/helix-series',
                'order' => 3,
                'is_published' => true,
            ],
        ];

        foreach ($projects as $project) {
            Project::updateOrCreate(
                ['slug' => $project['slug']],
                $project
            );
        }
    }
}
