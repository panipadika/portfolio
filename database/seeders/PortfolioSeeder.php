<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Credential;
use App\Models\Experience;
use App\Models\Project;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Seed data awal portofolio: projects, credentials (certificates, achievements, tech_stack),
     * dan experiences (education, experience).
     */
    public function run(): void
    {
        /*
        |----------------------------------------------------------------------
        | Projects
        |----------------------------------------------------------------------
        */
        $projects = [
            [
                'title' => 'CYBER GYZN',
                'slug' => 'cyber-gyzn',
                'description' => 'Website portofolio cyber security community dengan desain modern dark theme dan animasi interaktif.',
                'image_url' => '/assets/images/projects/cyber-gyzn.png',
                'tech_tags' => ['React', 'Tailwind CSS', 'Three.js'],
                'demo_url' => 'https://cybergyzn.example.com',
                'repo_url' => 'https://github.com/username/cyber-gyzn',
                'is_featured' => true,
            ],
            [
                'title' => 'Dashboard Sektoral',
                'slug' => 'dashboard-sektoral',
                'description' => 'Aplikasi dashboard data sektoral kota untuk visualisasi indikator pembangunan daerah secara interaktif.',
                'image_url' => '/assets/images/projects/dashboard-sektoral.png',
                'tech_tags' => ['Laravel', 'MySQL', 'Chart.js', 'Bootstrap'],
                'demo_url' => null,
                'repo_url' => 'https://github.com/username/dashboard-sektoral',
                'is_featured' => true,
            ],
            [
                'title' => 'Toko Online',
                'slug' => 'toko-online',
                'description' => 'Platform e-commerce full-stack dengan fitur keranjang belanja, pembayaran, dan manajemen produk.',
                'image_url' => '/assets/images/projects/toko-online.png',
                'tech_tags' => ['Laravel', 'MySQL', 'Midtrans', 'Tailwind CSS'],
                'demo_url' => null,
                'repo_url' => 'https://github.com/username/toko-online',
                'is_featured' => true,
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        /*
        |----------------------------------------------------------------------
        | Credentials — Certificates
        |----------------------------------------------------------------------
        */
        $certificates = [
            [
                'category' => 'certificate',
                'title' => 'Junior Web Developer',
                'issuer_or_level' => 'BNSP (Badan Nasional Sertifikasi Profesi)',
                'year' => '2025',
                'image_or_icon' => '/assets/images/certificates/bnsp-jwd.png',
                'credential_url' => null,
                'sort_order' => 1,
            ],
            [
                'category' => 'certificate',
                'title' => 'Belajar Dasar Pemrograman Web',
                'issuer_or_level' => 'Dicoding Indonesia',
                'year' => '2025',
                'image_or_icon' => '/assets/images/certificates/dicoding-web.png',
                'credential_url' => 'https://www.dicoding.com/certificates/example',
                'sort_order' => 2,
            ],
            [
                'category' => 'certificate',
                'title' => 'Belajar Membuat Aplikasi Back-End',
                'issuer_or_level' => 'Dicoding Indonesia',
                'year' => '2025',
                'image_or_icon' => '/assets/images/certificates/dicoding-backend.png',
                'credential_url' => 'https://www.dicoding.com/certificates/example2',
                'sort_order' => 3,
            ],
        ];

        foreach ($certificates as $cert) {
            Credential::create($cert);
        }

        /*
        |----------------------------------------------------------------------
        | Credentials — Achievements
        |----------------------------------------------------------------------
        */
        $achievements = [
            [
                'category' => 'achievement',
                'title' => 'Juara 1 Lomba Web Design Tingkat Kota',
                'issuer_or_level' => 'Dinas Pendidikan Kota Tasikmalaya',
                'year' => '2026',
                'image_or_icon' => '/assets/images/achievements/juara-webdesign.png',
                'credential_url' => null,
                'sort_order' => 1,
            ],
        ];

        foreach ($achievements as $achievement) {
            Credential::create($achievement);
        }

        /*
        |----------------------------------------------------------------------
        | Credentials — Tech Stack
        |----------------------------------------------------------------------
        */
        $techStack = [
            ['category' => 'tech_stack', 'title' => 'HTML5', 'issuer_or_level' => 'Frontend', 'sort_order' => 1],
            ['category' => 'tech_stack', 'title' => 'CSS3', 'issuer_or_level' => 'Frontend', 'sort_order' => 2],
            ['category' => 'tech_stack', 'title' => 'JavaScript', 'issuer_or_level' => 'Frontend', 'sort_order' => 3],
            ['category' => 'tech_stack', 'title' => 'React', 'issuer_or_level' => 'Frontend', 'sort_order' => 4],
            ['category' => 'tech_stack', 'title' => 'Tailwind CSS', 'issuer_or_level' => 'Frontend', 'sort_order' => 5],
            ['category' => 'tech_stack', 'title' => 'Three.js', 'issuer_or_level' => 'Frontend', 'sort_order' => 6],
            ['category' => 'tech_stack', 'title' => 'PHP', 'issuer_or_level' => 'Backend', 'sort_order' => 7],
            ['category' => 'tech_stack', 'title' => 'Laravel', 'issuer_or_level' => 'Backend', 'sort_order' => 8],
            ['category' => 'tech_stack', 'title' => 'MySQL', 'issuer_or_level' => 'Database', 'sort_order' => 9],
            ['category' => 'tech_stack', 'title' => 'Git', 'issuer_or_level' => 'Tools', 'sort_order' => 10],
            ['category' => 'tech_stack', 'title' => 'VS Code', 'issuer_or_level' => 'Tools', 'sort_order' => 11],
            ['category' => 'tech_stack', 'title' => 'Figma', 'issuer_or_level' => 'Tools', 'sort_order' => 12],
        ];

        foreach ($techStack as $tech) {
            Credential::create($tech);
        }

        /*
        |----------------------------------------------------------------------
        | Experiences — Education
        |----------------------------------------------------------------------
        */
        $education = [
            [
                'type' => 'education',
                'institution' => 'SMK Texmaco Tasikmalaya',
                'role_or_major' => 'Rekayasa Perangkat Lunak (RPL)',
                'period' => '2024 – 2027',
                'description' => 'Mempelajari dasar-dasar pemrograman, pengembangan web, dan basis data relasional.',
                'sort_order' => 1,
            ],
        ];

        foreach ($education as $edu) {
            Experience::create($edu);
        }

        /*
        |----------------------------------------------------------------------
        | Experiences — Work / Organization
        |----------------------------------------------------------------------
        */
        $experiences = [
            [
                'type' => 'experience',
                'institution' => 'Bappelitbangda Kota Tasikmalaya',
                'role_or_major' => 'Magang — Web Developer',
                'period' => '2026',
                'description' => 'Mengembangkan Dashboard Data Sektoral untuk visualisasi indikator pembangunan kota.',
                'sort_order' => 1,
            ],
        ];

        foreach ($experiences as $exp) {
            Experience::create($exp);
        }
    }
}
