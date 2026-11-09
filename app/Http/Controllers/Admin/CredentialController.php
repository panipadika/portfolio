<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCredentialRequest;
use App\Http\Requests\Admin\UpdateCredentialRequest;
use App\Models\Credential;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CredentialController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->input('category');
        $search = $request->input('search');

        $query = Credential::query();
        if ($category && in_array($category, ['certificate', 'achievement', 'tech_stack'], true)) {
            $query->where('category', $category);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('issuer_or_level', 'like', "%{$search}%")
                    ->orWhere('year', 'like', "%{$search}%");
            });
        }

        $items = $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc')->paginate(15)->withQueryString();

        $certificateCount = Credential::where('category', 'certificate')->count();
        $achievementCount = Credential::where('category', 'achievement')->count();
        $techStackCount = Credential::where('category', 'tech_stack')->count();

        return view('admin.credentials.index', compact('items', 'category', 'search', 'certificateCount', 'achievementCount', 'techStackCount'));
    }

    public function create(Request $request): View
    {
        $defaultCategory = $request->input('category', 'certificate');

        return view('admin.credentials.create', compact('defaultCategory'));
    }

    public function store(StoreCredentialRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('credentials', 'public');
            $validated['image_or_icon'] = '/storage/'.$path;
        } elseif (! empty($validated['image_or_icon'])) {
            $validated['image_or_icon'] = $this->processImageOrIcon($validated['image_or_icon']);
        }

        if (! isset($validated['sort_order'])) {
            $maxSort = Credential::where('category', $validated['category'])->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxSort + 1;
        }

        Credential::create($validated);

        return redirect()->route('admin.credentials.index', ['category' => $validated['category']])
            ->with('success', 'Data kredensial berhasil ditambahkan.');
    }

    public function edit(Credential $credential): View
    {
        return view('admin.credentials.edit', compact('credential'));
    }

    public function update(UpdateCredentialRequest $request, Credential $credential): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($credential->image_or_icon && str_starts_with($credential->image_or_icon, '/storage/credentials/')) {
                $oldPath = str_replace('/storage/', '', $credential->image_or_icon);
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('image')->store('credentials', 'public');
            $validated['image_or_icon'] = '/storage/'.$path;
        } elseif (array_key_exists('image_or_icon', $validated)) {
            $validated['image_or_icon'] = $this->processImageOrIcon($validated['image_or_icon'], $credential->image_or_icon);
        }

        $credential->update($validated);

        return redirect()->route('admin.credentials.index', ['category' => $credential->category])
            ->with('success', 'Data kredensial berhasil diperbarui.');
    }

    public function destroy(Credential $credential): RedirectResponse
    {
        $category = $credential->category;

        if ($credential->image_or_icon && str_starts_with($credential->image_or_icon, '/storage/credentials/')) {
            $oldPath = str_replace('/storage/', '', $credential->image_or_icon);
            Storage::disk('public')->delete($oldPath);
        }

        $credential->delete();

        return redirect()->route('admin.credentials.index', ['category' => $category])
            ->with('success', 'Data kredensial berhasil dihapus.');
    }

    /**
     * Cari logo SVG resmi dari Iconify / Devicon / Logos / Simple Icons untuk tech stack.
     */
    public function lookupIcon(Request $request): JsonResponse
    {
        $query = trim((string) $request->input('query', ''));
        if ($query === '') {
            return response()->json(['query' => $query, 'icons' => []]);
        }

        $slug = Str::slug($query);
        $stripped = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $query));

        $candidates = array_unique(array_filter([
            "devicon:{$slug}",
            "logos:{$slug}",
            "simple-icons:{$slug}",
            "skill-icons:{$slug}",
            $stripped !== $slug ? "devicon:{$stripped}" : null,
            $stripped !== $slug ? "logos:{$stripped}" : null,
            $stripped !== $slug ? "simple-icons:{$stripped}" : null,
        ]));

        $results = [];

        foreach ($candidates as $cand) {
            [$prefix, $name] = explode(':', $cand, 2);
            $url = "https://api.iconify.design/{$prefix}/{$name}.svg";
            try {
                $resp = Http::timeout(2)->get($url);
                if ($resp->successful() && str_contains($resp->body(), '<svg')) {
                    $results[] = [
                        'identifier' => $cand,
                        'url' => $url,
                        'collection' => $prefix,
                        'title' => ucwords(str_replace('-', ' ', $name)),
                    ];
                    if (count($results) >= 6) {
                        break;
                    }
                }
            } catch (\Throwable) {
                // Continue checking candidates
            }
        }

        if (count($results) < 4) {
            try {
                $searchResp = Http::timeout(3)->get('https://api.iconify.design/search?query='.urlencode($query).'&limit=15');
                if ($searchResp->successful()) {
                    $icons = $searchResp->json('icons') ?? [];
                    $priorities = ['devicon', 'logos', 'simple-icons', 'skill-icons', 'vscode-icons', 'thesvg-color', 'mdi'];
                    usort($icons, function ($a, $b) use ($priorities) {
                        $pA = explode(':', $a)[0] ?? '';
                        $pB = explode(':', $b)[0] ?? '';
                        $idxA = array_search($pA, $priorities, true);
                        $idxB = array_search($pB, $priorities, true);
                        $idxA = $idxA === false ? 99 : $idxA;
                        $idxB = $idxB === false ? 99 : $idxB;

                        return $idxA <=> $idxB;
                    });

                    foreach ($icons as $iconId) {
                        if (collect($results)->contains('identifier', $iconId)) {
                            continue;
                        }
                        [$pfx, $nm] = explode(':', $iconId, 2);
                        $results[] = [
                            'identifier' => $iconId,
                            'url' => "https://api.iconify.design/{$pfx}/{$nm}.svg",
                            'collection' => $pfx,
                            'title' => ucwords(str_replace('-', ' ', $nm)),
                        ];
                        if (count($results) >= 8) {
                            break;
                        }
                    }
                }
            } catch (\Throwable) {
                // Ignore search error
            }
        }

        return response()->json([
            'query' => $query,
            'icons' => $results,
        ]);
    }

    /**
     * Simpan tech stack baru secara instan dari form projek.
     */
    public function quickStoreTechStack(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'issuer_or_level' => ['nullable', 'string', 'max:100'],
            'image_or_icon' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:5120'],
        ]);

        $title = trim($validated['title']);

        // Cek apakah sudah ada tech stack dengan nama serupa
        $existing = Credential::where('category', 'tech_stack')
            ->whereRaw('LOWER(title) = ?', [strtolower($title)])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => true,
                'message' => "Teknologi '{$existing->title}' sudah ada di database.",
                'item' => [
                    'id' => $existing->id,
                    'title' => $existing->title,
                    'image_or_icon' => $existing->image_or_icon,
                    'issuer_or_level' => $existing->issuer_or_level,
                    'category' => $existing->category,
                ],
                'is_new' => false,
            ]);
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('credentials', 'public');
            $imagePath = '/storage/'.$path;
        } elseif (! empty($validated['image_or_icon'])) {
            $imagePath = $this->processImageOrIcon($validated['image_or_icon']);
        } else {
            // Otomatis cari & download SVG resmi dari Iconify/Devicon
            $imagePath = $this->autoResolveAndStoreIcon($title);
        }

        $maxSort = Credential::where('category', 'tech_stack')->max('sort_order') ?? 0;

        $credential = Credential::create([
            'category' => 'tech_stack',
            'title' => $title,
            'issuer_or_level' => $validated['issuer_or_level'] ?? 'Tools',
            'image_or_icon' => $imagePath,
            'sort_order' => $maxSort + 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Tech stack '{$credential->title}' berhasil dibuat dan disimpan.",
            'item' => [
                'id' => $credential->id,
                'title' => $credential->title,
                'image_or_icon' => $credential->image_or_icon,
                'issuer_or_level' => $credential->issuer_or_level,
                'category' => $credential->category,
            ],
            'is_new' => true,
        ], 201);
    }

    /**
     * Otomatis mencari dan mengunduh logo SVG resmi berdasarkan nama teknologi.
     */
    public function autoResolveAndStoreIcon(string $title): ?string
    {
        $slug = Str::slug($title);
        $stripped = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $title));

        $candidates = array_unique(array_filter([
            "devicon:{$slug}",
            "logos:{$slug}",
            "simple-icons:{$slug}",
            "skill-icons:{$slug}",
            $stripped !== $slug ? "devicon:{$stripped}" : null,
            $stripped !== $slug ? "logos:{$stripped}" : null,
            $stripped !== $slug ? "simple-icons:{$stripped}" : null,
        ]));

        foreach ($candidates as $cand) {
            [$prefix, $name] = explode(':', $cand, 2);
            $iconifyUrl = "https://api.iconify.design/{$prefix}/{$name}.svg";

            try {
                $response = Http::timeout(3)->get($iconifyUrl);
                if ($response->successful() && str_contains($response->body(), '<svg')) {
                    $svgBody = $response->body();
                    if (! str_contains($svgBody, 'xmlns=')) {
                        $svgBody = (string) preg_replace('/<svg\b/i', '<svg xmlns="http://www.w3.org/2000/svg"', $svgBody, 1);
                    }
                    if (str_contains($svgBody, 'xlink:') && ! str_contains($svgBody, 'xmlns:xlink=')) {
                        $svgBody = (string) preg_replace('/<svg\b/i', '<svg xmlns:xlink="http://www.w3.org/1999/xlink"', $svgBody, 1);
                    }

                    $filename = 'credentials/svg-'.Str::random(20).'.svg';
                    Storage::disk('public')->put($filename, $svgBody);

                    return '/storage/'.$filename;
                }
            } catch (\Throwable) {
                // Continue
            }
        }

        try {
            $searchResp = Http::timeout(4)->get('https://api.iconify.design/search?query='.urlencode($title).'&limit=10');
            if ($searchResp->successful()) {
                $icons = $searchResp->json('icons') ?? [];
                $priorities = ['devicon', 'logos', 'simple-icons', 'skill-icons', 'vscode-icons', 'thesvg-color', 'mdi'];
                usort($icons, function ($a, $b) use ($priorities) {
                    $pA = explode(':', $a)[0] ?? '';
                    $pB = explode(':', $b)[0] ?? '';
                    $idxA = array_search($pA, $priorities, true);
                    $idxB = array_search($pB, $priorities, true);
                    $idxA = $idxA === false ? 99 : $idxA;
                    $idxB = $idxB === false ? 99 : $idxB;

                    return $idxA <=> $idxB;
                });

                foreach ($icons as $iconId) {
                    [$pfx, $nm] = explode(':', $iconId, 2);
                    $url = "https://api.iconify.design/{$pfx}/{$nm}.svg";
                    $response = Http::timeout(3)->get($url);
                    if ($response->successful() && str_contains($response->body(), '<svg')) {
                        $svgBody = $response->body();
                        if (! str_contains($svgBody, 'xmlns=')) {
                            $svgBody = (string) preg_replace('/<svg\b/i', '<svg xmlns="http://www.w3.org/2000/svg"', $svgBody, 1);
                        }
                        if (str_contains($svgBody, 'xlink:') && ! str_contains($svgBody, 'xmlns:xlink=')) {
                            $svgBody = (string) preg_replace('/<svg\b/i', '<svg xmlns:xlink="http://www.w3.org/1999/xlink"', $svgBody, 1);
                        }

                        $filename = 'credentials/svg-'.Str::random(20).'.svg';
                        Storage::disk('public')->put($filename, $svgBody);

                        return '/storage/'.$filename;
                    }
                }
            }
        } catch (\Throwable) {
            // Ignore
        }

        return null;
    }

    /**
     * Process image_or_icon: saves raw SVG code or Iconify/Devicon SVGs to filesystem.
     */
    public function processImageOrIcon(?string $value, ?string $currentPath = null): ?string
    {
        if (empty($value)) {
            return null;
        }

        $trimmed = trim($value);

        // 1. Raw SVG code from Devicon or Iconify
        if (str_contains($trimmed, '<svg')) {
            if ($currentPath && str_starts_with($currentPath, '/storage/credentials/svg-')) {
                $oldPath = str_replace('/storage/', '', $currentPath);
                Storage::disk('public')->delete($oldPath);
            }

            // Ensure xmlns="http://www.w3.org/2000/svg" exists on <svg> tag for valid standalone SVG rendering in browsers
            if (! str_contains($trimmed, 'xmlns=')) {
                $trimmed = (string) preg_replace('/<svg\b/i', '<svg xmlns="http://www.w3.org/2000/svg"', $trimmed, 1);
            }
            if (str_contains($trimmed, 'xlink:') && ! str_contains($trimmed, 'xmlns:xlink=')) {
                $trimmed = (string) preg_replace('/<svg\b/i', '<svg xmlns:xlink="http://www.w3.org/1999/xlink"', $trimmed, 1);
            }

            $filename = 'credentials/svg-'.Str::random(20).'.svg';
            Storage::disk('public')->put($filename, $trimmed);

            return '/storage/'.$filename;
        }

        // 2. Iconify identifier (e.g. "devicon:react" or "logos:vuejs")
        if (preg_match('/^[a-z0-9_-]+:[a-z0-9_-]+$/i', $trimmed)) {
            [$prefix, $name] = explode(':', $trimmed, 2);
            $iconifyUrl = "https://api.iconify.design/{$prefix}/{$name}.svg";

            try {
                $response = Http::timeout(4)->get($iconifyUrl);
                if ($response->successful() && str_contains($response->body(), '<svg')) {
                    if ($currentPath && str_starts_with($currentPath, '/storage/credentials/svg-')) {
                        $oldPath = str_replace('/storage/', '', $currentPath);
                        Storage::disk('public')->delete($oldPath);
                    }

                    $svgBody = $response->body();
                    if (! str_contains($svgBody, 'xmlns=')) {
                        $svgBody = (string) preg_replace('/<svg\b/i', '<svg xmlns="http://www.w3.org/2000/svg"', $svgBody, 1);
                    }

                    $filename = 'credentials/svg-'.Str::random(20).'.svg';
                    Storage::disk('public')->put($filename, $svgBody);

                    return '/storage/'.$filename;
                }
            } catch (\Throwable) {
                // Network unavailable / timeout: fallback to direct Iconify CDN URL
            }

            return $iconifyUrl;
        }

        return $trimmed;
    }
}
