<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Credential;
use App\Models\Experience;
use App\Models\Project;
use Illuminate\Http\JsonResponse;

class PortfolioController extends Controller
{
    /**
     * Mengambil seluruh data portofolio dalam satu panggilan (single payload).
     *
     * Endpoint: GET /api/portfolio
     * Response: 200 OK with stats, education, experience, projects, certificates, achievements, tech_stack
     */
    public function index(): JsonResponse
    {
        $projects = Project::where('is_featured', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $certificates = Credential::where('category', 'certificate')
            ->orderBy('sort_order')
            ->get();

        $achievements = Credential::where('category', 'achievement')
            ->orderBy('sort_order')
            ->get();

        $techStack = Credential::where('category', 'tech_stack')
            ->orderBy('sort_order')
            ->get();

        $education = Experience::where('type', 'education')
            ->orderBy('sort_order')
            ->get();

        $experience = Experience::where('type', 'experience')
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Data portofolio berhasil diambil.',
            'data' => [
                'stats' => [
                    'projects_count' => Project::count(),
                    'certificates_count' => Credential::where('category', 'certificate')->count(),
                    'awards_count' => Credential::where('category', 'achievement')->count(),
                ],
                'education' => $education,
                'experience' => $experience,
                'projects' => $projects,
                'certificates' => $certificates,
                'achievements' => $achievements,
                'tech_stack' => $techStack,
            ],
        ]);
    }
}
