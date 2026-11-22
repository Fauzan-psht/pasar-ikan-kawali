<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfilPasar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilPasarController extends Controller
{
    public function index()
    {
        $profil = ProfilPasar::current();
        return view('admin.profil.index', compact('profil'));
    }

    public function update(Request $request)
    {
        $profil = ProfilPasar::current();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'sejarah' => 'nullable|string',
            'visi' => 'nullable|string',
            'misi' => 'nullable|string',
            'alamat' => 'nullable|string',
            'telepon' => 'nullable|string|max:30',
            'whatsapp' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'jam_buka' => 'nullable|string|max:255',
            'maps_url' => 'nullable|url',
            'maps_embed' => 'nullable|string',
            'facebook' => 'nullable|url',
            'instagram' => 'nullable|url',
            'youtube' => 'nullable|url',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('logo')) {
            if ($profil->logo) Storage::disk('public')->delete($profil->logo);
            $validated['logo'] = $request->file('logo')->store('profil', 'public');
        }

        if ($request->hasFile('banner')) {
            if ($profil->banner) Storage::disk('public')->delete($profil->banner);
            $validated['banner'] = $request->file('banner')->store('profil', 'public');
        }

        $profil->update($validated);

        return redirect()->route('admin.profil.index')->with('success', 'Profil pasar berhasil diperbarui!');
    }
}