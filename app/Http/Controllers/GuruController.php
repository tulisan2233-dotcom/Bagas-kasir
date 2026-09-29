<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\PDF;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        // Filter search
        $gurus = Guru::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nama_guru', 'like', "%{$search}%")
                    ->orWhere('nip', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('guru.create');
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_guru' => 'required|string|max:255',
            'nip' => 'required|string|unique:gurus|max:50',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'jabatan' => 'required|string|max:255',
            'unit_sekolah' => 'required|string|max:255',
        ]);

        // Ambil data guru
        $data = $request->only([
            'nama_guru',
            'nip',
            'email',
            'jabatan',
            'unit_sekolah',
            'foto',
        ]);

        // Upload foto
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('foto-guru', 'public');
        }

        // Simpan data
        Guru::create($data);

        return redirect()
            ->route('guru.index')
            ->with('success', 'Data guru berhasil ditambahkan.');
    }

    public function show(Guru $guru)
    {
        return view('guru.show', compact('guru'));
    }

    public function edit(Guru $guru)
    {
        return view('guru.edit', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        // Validasi data
        $request->validate([
            'nama_guru' => 'required|string|max:255',
            'nip'        => 'required|string|unique:gurus,nip|max:50',
            'email'      => 'nullable|email|unique:gurus,email|max:255',
            'foto'       => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'jabatan'    => 'required|string|max:255',
            'unit_sekolah' => 'required|string|max:255',
        ]);

        // Ambil data guru
        $data = $request->only([
            'nama_guru',
            'nip',
            'email',
            'jabatan',
            'unit_sekolah',
            'foto',
        ]);

        // Jika upload foto baru
        if ($request->hasFile('foto')) {

            // Hapus foto lama
            if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
                Storage::disk('public')->delete($guru->foto);
            }

            // Simpan foto baru
            $data['foto'] = $request->file('foto')
                ->store('foto-guru', 'public');
        }

        // Update data
        $guru->update($data);

        return redirect()
            ->route('guru.index')
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        // Hapus foto dari storage
        if ($guru->foto && Storage::disk('public')->exists($guru->foto)) {
            Storage::disk('public')->delete($guru->foto);
        }

        // Hapus data guru
        $guru->delete();

        return redirect()
            ->route('guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }
    public function cetakpdf()
    {
        $gurus = Guru::all();
        $pdf = PDF::loadView('guru.cetakpdf', compact('gurus')) ->setPaper('a4', 'landscape');

        return $pdf->download('data_guru.pdf');
    }
}
