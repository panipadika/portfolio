<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingsRequest;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class SettingController extends Controller
{
    public function index(): View
    {
        $settings = SiteSetting::allMap();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $groupMapping = [
            'contact_email' => 'contact',
            'contact_phone' => 'contact',
            'contact_whatsapp' => 'contact',
            'contact_location' => 'contact',
            'bio_headline' => 'general',
            'social_github' => 'social',
            'social_linkedin' => 'social',
            'social_instagram' => 'social',
            'social_tiktok' => 'social',
        ];

        foreach ($validated as $key => $value) {
            $group = $groupMapping[$key] ?? 'general';
            SiteSetting::set($key, $value, $group);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan kontak & sosial media berhasil disimpan.');
    }
}
