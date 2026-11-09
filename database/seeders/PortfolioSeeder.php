<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Credential;
use App\Models\Experience;
use App\Models\Project;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Seed data awal portofolio: projects, credentials (certificates, achievements, tech_stack),
     * dan experiences (education, experience).
     */
    public function run(): void
    {
        Project::truncate();
        Credential::truncate();
        Experience::truncate();

        /*
        |----------------------------------------------------------------------
        | Projects
        |----------------------------------------------------------------------
        */
        $projects = [
            [
                'title' => 'CYBER GYZN',
                'slug' => 'cyber-gyzn',
                'description' => 'CYBER GYZN adalah aplikasi mobile yang memantau dan mengelola data hasil link phishing melalui dashboard terintegrasi untuk memvisualisasikan informasi dan mendukung analisis keamanan siber.',
                'image_url' => '/assets/images/projects/cyber-gyzn.png',
                'tech_tags' => [
                    'HTML',
                    'CSS',
                    'JavaScript',
                    'Capacitor',
                    'Firebase',
                    'VSCode',
                    'Android Studio',
                    'Vercel',
                ],
                'demo_url' => 'https://cybergyzn.vercel.app',
                'repo_url' => 'https://github.com/panipadika/cyber-gyzn',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Bot Generate Artikel',
                'slug' => 'bot-generate-artikel',
                'description' => 'Website otomatisasi pembuatan artikel yang memanfaatkan kecerdasan buatan (AI) untuk membuat artikel secara otomatis berdasarkan kata kunci atau topik yang ditentukan, lengkap dengan optimasi SEO, pengelolaan konten, dan penjadwalan publikasi dalam satu dashboard.',
                'image_url' => '/assets/images/projects/bot-artikel.png',
                'tech_tags' => [
                    'Python',
                    'HTML',
                    'CSS',
                    'JavaScript',
                    'VScode',
                    'Gemini',
                    'Chromium',
                ],
                'demo_url' => null,
                'repo_url' => 'https://github.com/panipadika/bot-generate-artikel',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Database Siswa',
                'slug' => 'database-siswa',
                'description' => 'Aplikasi Dashboard Data Siswa merupakan platform berbasis website untuk memantau data siswa secara terpusat dengan filter data dan pelaporan dinamis.',
                'image_url' => '/assets/images/projects/database-siswa.png',
                'tech_tags' => [
                    'Laravel',
                    'MySQL',
                    'Tailwind CSS',
                    'PHP',
                    'Chart.js',
                ],
                'demo_url' => null,
                'repo_url' => 'https://github.com/panipadika/database-siswa',
                'is_featured' => true,
                'sort_order' => 3,
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
                'title' => 'Praktik Kerja Lapangan (PKL) STEKOM',
                'issuer_or_level' => 'Universitas STEKOM Semarang',
                'year' => '2026',
                'image_or_icon' => '/assets/images/certificates/sertifikat-stekom.png',
                'credential_url' => null,
                'sort_order' => 1,
            ],
            [
                'category' => 'certificate',
                'title' => 'CSS And Javascript Crash Course',
                'issuer_or_level' => 'Udemy (PROPER DOT INSTITUTE)',
                'year' => '2025',
                'image_or_icon' => '/assets/images/certificates/sertifikat-udemy-css-js.png',
                'credential_url' => null,
                'sort_order' => 2,
            ],
            [
                'category' => 'certificate',
                'title' => 'Ethically Hack the Planet Part 2',
                'issuer_or_level' => 'Udemy (Cyber Twinkle)',
                'year' => '2025',
                'image_or_icon' => '/assets/images/certificates/sertifikat-udemy-ethical-hack.png',
                'credential_url' => null,
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
                'title' => 'Juara III Kompetisi Sains Madrasah (KSM)',
                'issuer_or_level' => 'Kementerian Agama RI (Tingkat Kabupaten/Kota)',
                'year' => '2023',
                'image_or_icon' => '/assets/images/achievements/piagam-ksm-2023.png',
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
            ['category' => 'tech_stack', 'title' => 'React', 'issuer_or_level' => 'Frontend', 'sort_order' => 1],
            ['category' => 'tech_stack', 'title' => 'JavaScript', 'issuer_or_level' => 'Frontend', 'sort_order' => 2],
            ['category' => 'tech_stack', 'title' => 'HTML5', 'issuer_or_level' => 'Frontend', 'sort_order' => 3],
            ['category' => 'tech_stack', 'title' => 'CSS3', 'issuer_or_level' => 'Frontend', 'sort_order' => 4],
            ['category' => 'tech_stack', 'title' => 'Laravel', 'issuer_or_level' => 'Backend', 'sort_order' => 5],
            ['category' => 'tech_stack', 'title' => 'Tailwind CSS', 'issuer_or_level' => 'Frontend', 'sort_order' => 6],
            ['category' => 'tech_stack', 'title' => 'Laragon', 'issuer_or_level' => 'Tools', 'sort_order' => 7],
            ['category' => 'tech_stack', 'title' => 'PHP', 'issuer_or_level' => 'Backend', 'sort_order' => 8],
            ['category' => 'tech_stack', 'title' => 'Python', 'issuer_or_level' => 'Backend', 'sort_order' => 9],
            ['category' => 'tech_stack', 'title' => 'MySQL', 'issuer_or_level' => 'Database', 'sort_order' => 10],
            ['category' => 'tech_stack', 'title' => 'Supabase', 'issuer_or_level' => 'Database', 'sort_order' => 11],
            ['category' => 'tech_stack', 'title' => 'Firebase', 'issuer_or_level' => 'Backend', 'sort_order' => 12],
            ['category' => 'tech_stack', 'title' => 'XAMPP', 'issuer_or_level' => 'Tools', 'sort_order' => 13],
            ['category' => 'tech_stack', 'title' => 'Git', 'issuer_or_level' => 'Tools', 'sort_order' => 14],
            ['category' => 'tech_stack', 'title' => 'GitHub', 'issuer_or_level' => 'Tools', 'sort_order' => 15],
            ['category' => 'tech_stack', 'title' => 'Vite', 'issuer_or_level' => 'Frontend', 'sort_order' => 16],
            ['category' => 'tech_stack', 'title' => 'Vercel', 'issuer_or_level' => 'Deployment', 'sort_order' => 17],
            ['category' => 'tech_stack', 'title' => 'Font Awesome', 'issuer_or_level' => 'Frontend', 'sort_order' => 18],
        ];

        foreach ($techStack as $tech) {
            Credential::create($tech);
        }

        /*
        |----------------------------------------------------------------------
        | Experiences — Education & Work
        |----------------------------------------------------------------------
        */
        Experience::truncate();

        $education = [
            [
                'type' => 'education',
                'institution' => 'Universitas Bina Sarana Informatika',
                'role_or_major' => 'S1 - Sistem Informasi',
                'period' => '2024 – 2028',
                'description' => 'Menempuh pendidikan sarjana pada program studi Sistem Informasi.',
                'sort_order' => 1,
            ],
            [
                'type' => 'education',
                'institution' => 'SMKN 1 RAJADESA',
                'role_or_major' => 'Sekolah Menengah Kejuruan',
                'period' => '2021 – 2024',
                'description' => 'Fokus pada pengembangan rekayasa perangkat lunak dan keahlian teknologi.',
                'sort_order' => 2,
            ],
            [
                'type' => 'education',
                'institution' => 'SMPN 1 RAJADESA',
                'role_or_major' => 'Sekolah Menengah Pertama',
                'period' => '2018 – 2021',
                'description' => 'Pendidikan dasar menengah pertama dan fondasi akademik.',
                'sort_order' => 3,
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
                'role_or_major' => 'Magang / IT Staff',
                'period' => '2024 (3 Bulan)',
                'description' => 'Praktik kerja magang selama 3 bulan di Badan Perencanaan Pembangunan, Penelitian dan Pengembangan Daerah Kota Tasikmalaya.',
                'sort_order' => 1,
            ],
            [
                'type' => 'experience',
                'institution' => 'ASCOM (Artha Sarana Computer)',
                'role_or_major' => 'Siswa Magang / PKL',
                'period' => '2023 (3 Bulan)',
                'description' => 'Praktik Kerja Lapangan (PKL) saat SMK selama 3 bulan dalam perbaikan, instalasi, dan pemeliharaan perangkat komputer.',
                'sort_order' => 2,
            ],
        ];

        foreach ($experiences as $exp) {
            Experience::create($exp);
        }

        // 4. Default Site Settings (Contact & Social Links)
        $settings = [
            // Contact Info
            ['key' => 'contact_email', 'value' => 'panipadika1@gmail.com', 'group' => 'contact'],
            ['key' => 'contact_phone', 'value' => '+62 853-2276-1245', 'group' => 'contact'],
            ['key' => 'contact_whatsapp', 'value' => 'https://wa.me/6285322761245', 'group' => 'contact'],
            ['key' => 'contact_location', 'value' => 'Tasikmalaya, Jawa Barat, Indonesia', 'group' => 'contact'],
            ['key' => 'bio_headline', 'value' => 'Saya adalah Mahasiswa Sistem Informasi yang memiliki ketertarikan dalam dunia teknologi dan pengembangan web.', 'group' => 'general'],

            // Social Media Links
            ['key' => 'social_github', 'value' => 'https://github.com/panipadika', 'group' => 'social'],
            ['key' => 'social_linkedin', 'value' => 'https://www.linkedin.com/in/pani-padika-933908414', 'group' => 'social'],
            ['key' => 'social_instagram', 'value' => 'https://www.instagram.com/panipadika_', 'group' => 'social'],
            ['key' => 'social_tiktok', 'value' => 'https://www.tiktok.com/@panipadika_', 'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'group' => $setting['group']]
            );
        }
    }
}
