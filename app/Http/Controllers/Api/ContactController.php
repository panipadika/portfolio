<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\Message;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Menyimpan pesan kontak dari pengunjung.
     *
     * Endpoint: POST /api/contact
     * Validasi melalui StoreContactRequest (name, email, message).
     * Input disanitasi menggunakan strip_tags() sebelum disimpan.
     * IP address dicatat untuk keperluan audit anti-spam.
     */
    public function store(StoreContactRequest $request): JsonResponse
    {
        $validated = $request->validated();

        Message::create([
            'name' => strip_tags($validated['name']),
            'email' => strip_tags($validated['email']),
            'message' => strip_tags($validated['message']),
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi saya.',
        ], 201);
    }
}
