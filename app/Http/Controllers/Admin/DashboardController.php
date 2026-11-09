<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Credential;
use App\Models\Experience;
use App\Models\Message;
use App\Models\Project;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'projects_count' => Project::count(),
            'featured_projects_count' => Project::where('is_featured', true)->count(),
            'certificates_count' => Credential::where('category', 'certificate')->count(),
            'achievements_count' => Credential::where('category', 'achievement')->count(),
            'tech_stack_count' => Credential::where('category', 'tech_stack')->count(),
            'education_count' => Experience::where('type', 'education')->count(),
            'experience_count' => Experience::where('type', 'experience')->count(),
            'unread_messages_count' => Message::where('is_read', false)->count(),
            'total_messages_count' => Message::count(),
        ];

        $recentMessages = Message::orderBy('created_at', 'desc')->take(5)->get();
        $recentProjects = Project::orderBy('created_at', 'desc')->take(4)->get();

        return view('admin.dashboard', compact('stats', 'recentMessages', 'recentProjects'));
    }
}
