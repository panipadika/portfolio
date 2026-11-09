<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreExperienceRequest;
use App\Http\Requests\Admin\UpdateExperienceRequest;
use App\Models\Experience;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->input('type');

        $query = Experience::query();
        if ($type && in_array($type, ['education', 'experience'], true)) {
            $query->where('type', $type);
        }

        $items = $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $educationCount = Experience::where('type', 'education')->count();
        $experienceCount = Experience::where('type', 'experience')->count();

        return view('admin.experiences.index', compact('items', 'type', 'educationCount', 'experienceCount'));
    }

    public function create(Request $request): View
    {
        $defaultType = $request->input('type', 'education');

        return view('admin.experiences.create', compact('defaultType'));
    }

    public function store(StoreExperienceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        if (! isset($validated['sort_order'])) {
            $maxSort = Experience::where('type', $validated['type'])->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxSort + 1;
        }

        Experience::create($validated);

        return redirect()->route('admin.experiences.index', ['type' => $validated['type']])
            ->with('success', 'Data riwayat berhasil ditambahkan.');
    }

    public function edit(Experience $experience): View
    {
        return view('admin.experiences.edit', compact('experience'));
    }

    public function update(UpdateExperienceRequest $request, Experience $experience): RedirectResponse
    {
        $validated = $request->validated();
        $experience->update($validated);

        return redirect()->route('admin.experiences.index', ['type' => $experience->type])
            ->with('success', 'Data riwayat berhasil diperbarui.');
    }

    public function destroy(Experience $experience): RedirectResponse
    {
        $type = $experience->type;
        $experience->delete();

        return redirect()->route('admin.experiences.index', ['type' => $type])
            ->with('success', 'Data riwayat berhasil dihapus.');
    }
}
