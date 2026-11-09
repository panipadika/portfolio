<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAdminLoginSlugRequest;
use App\Http\Requests\Admin\UpdateAdminPasswordRequest;
use App\Http\Requests\Admin\UpdateAdminProfileRequest;
use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $loginSlug = SiteSetting::get('admin_login_slug', 'admin');

        return view('admin.account.index', compact('user', 'loginSlug'));
    }

    public function updateProfile(UpdateAdminProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = now();
        }

        $user->save();

        return redirect()->route('admin.account.index')
            ->with('success', 'Informasi profil dan email akun admin berhasil diperbarui.');
    }

    public function updatePassword(UpdateAdminPasswordRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->forceFill([
            'password' => Hash::make($request->validated()['password']),
        ])->save();

        return redirect()->route('admin.account.index')
            ->with('success', 'Password akun admin berhasil diubah.');
    }

    public function updateLoginSlug(UpdateAdminLoginSlugRequest $request): RedirectResponse
    {
        $slug = trim($request->validated()['login_slug'], '/');

        SiteSetting::set('admin_login_slug', $slug, 'admin');

        return redirect()->route('admin.account.index')
            ->with('success', "URL login admin berhasil diubah. Anda kini dapat mengakses login melalui: /{$slug}");
    }
}
