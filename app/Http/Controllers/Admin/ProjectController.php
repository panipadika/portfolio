<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProjectRequest;
use App\Http\Requests\Admin\UpdateProjectRequest;
use App\Models\Credential;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->boolean('featured'));
        }

        $projects = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.projects.index', compact('projects'));
    }

    public function create(): View
    {
        $availableTechStacks = Credential::where('category', 'tech_stack')
            ->orderBy('sort_order', 'asc')
            ->orderBy('title', 'asc')
            ->get(['id', 'title', 'image_or_icon', 'issuer_or_level']);

        return view('admin.projects.create', compact('availableTechStacks'));
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('projects', 'public');
            $validated['image_url'] = '/storage/'.$path;
        }

        if (empty($validated['slug'])) {
            $slugBase = Str::slug($validated['title']);
            $slug = $slugBase;
            $counter = 1;
            while (Project::where('slug', $slug)->exists()) {
                $slug = "{$slugBase}-{$counter}";
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        if (isset($validated['tech_tags'])) {
            if (is_string($validated['tech_tags'])) {
                $validated['tech_tags'] = array_values(array_filter(array_map('trim', explode(',', $validated['tech_tags']))));
            } elseif (is_array($validated['tech_tags'])) {
                $validated['tech_tags'] = array_values(array_filter(array_map('trim', $validated['tech_tags'])));
            } else {
                $validated['tech_tags'] = [];
            }

            $this->ensureTechStacksExist($validated['tech_tags']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        Project::create($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projek baru berhasil ditambahkan.');
    }

    public function edit(Project $project): View
    {
        $availableTechStacks = Credential::where('category', 'tech_stack')
            ->orderBy('sort_order', 'asc')
            ->orderBy('title', 'asc')
            ->get(['id', 'title', 'image_or_icon', 'issuer_or_level']);

        return view('admin.projects.edit', compact('project', 'availableTechStacks'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            // Delete old uploaded image if stored in public storage
            if ($project->image_url && str_starts_with($project->image_url, '/storage/projects/')) {
                $oldPath = str_replace('/storage/', '', $project->image_url);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('projects', 'public');
            $validated['image_url'] = '/storage/'.$path;
        }

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        if (isset($validated['tech_tags'])) {
            if (is_string($validated['tech_tags'])) {
                $validated['tech_tags'] = array_values(array_filter(array_map('trim', explode(',', $validated['tech_tags']))));
            } elseif (is_array($validated['tech_tags'])) {
                $validated['tech_tags'] = array_values(array_filter(array_map('trim', $validated['tech_tags'])));
            } else {
                $validated['tech_tags'] = [];
            }

            $this->ensureTechStacksExist($validated['tech_tags']);
        }

        $validated['is_featured'] = $request->boolean('is_featured');

        $project->update($validated);

        return redirect()->route('admin.projects.index')
            ->with('success', 'Data projek berhasil diperbarui.');
    }

    /**
     * Pastikan semua teknologi yang dipilih atau diketik otomatis tersimpan di menu Sertifikat & Skill.
     *
     * @param  list<string>  $tags
     */
    private function ensureTechStacksExist(array $tags): void
    {
        $credentialController = app(CredentialController::class);

        foreach ($tags as $tag) {
            $name = trim($tag);
            if ($name === '') {
                continue;
            }

            $exists = Credential::where('category', 'tech_stack')
                ->whereRaw('LOWER(title) = ?', [strtolower($name)])
                ->exists();

            if (! $exists) {
                $icon = $credentialController->autoResolveAndStoreIcon($name);
                $maxSort = Credential::where('category', 'tech_stack')->max('sort_order') ?? 0;

                Credential::create([
                    'category' => 'tech_stack',
                    'title' => $name,
                    'issuer_or_level' => 'Tools',
                    'image_or_icon' => $icon,
                    'sort_order' => $maxSort + 1,
                ]);
            }
        }
    }

    public function destroy(Project $project): RedirectResponse
    {
        if ($project->image_url && str_starts_with($project->image_url, '/storage/projects/')) {
            $oldPath = str_replace('/storage/', '', $project->image_url);
            Storage::disk('public')->delete($oldPath);
        }

        $project->delete();

        return redirect()->route('admin.projects.index')
            ->with('success', 'Projek berhasil dihapus.');
    }
}
