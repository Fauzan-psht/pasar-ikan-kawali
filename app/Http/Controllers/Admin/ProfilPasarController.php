<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilPasar;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilPasarController extends Controller
{
    public function index()
    {
        $profil = ProfilPasar::current();

        return view('admin.profil.index', compact('profil'));
    }

    public function update(Request $request, ImageService $imageService)
    {
        $profil = ProfilPasar::current();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'alamat' => 'nullable|string',
            'telepon' => ['nullable', 'regex:/^[0-9]{1,13}$/'],
            'whatsapp' => ['nullable', 'regex:/^[0-9]{1,13}$/'],
            'email' => 'nullable|email|max:255',
            'jam_buka' => 'nullable|string|max:255',
            'maps_url' => 'nullable|url',
            'maps_embed' => 'nullable|string',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'youtube' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ], [
            'telepon.regex' => 'Nomor telepon hanya boleh berisi angka dan maksimal 13 digit.',
            'whatsapp.regex' => 'Nomor WhatsApp hanya boleh berisi angka dan maksimal 13 digit.',
        ]);

        $oldImages = [];

        foreach (['logo', 'banner'] as $imageField) {
            if ($request->hasFile($imageField)) {
                $oldImages[$imageField] = $profil->{$imageField};
                $validated[$imageField] = $imageService->store(
                    $request->file($imageField),
                    'profil',
                    $imageField,
                );
            }
        }

        $profil->update($validated);

        foreach ($oldImages as $oldImage) {
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        return redirect()->route('admin.profil.index')->with('success', 'Profil pasar berhasil diperbarui!');
    }
}
