<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\PDF;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        // Filter search
        $siswas = Siswa::query()
            ->when($request->search, function ($query, $search) {
                $query->where('nama_siswa', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            })
            ->paginate(10);

        return view('siswa.index', compact('siswas'));
    }

    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nis' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'jurusan' => 'required|string|max:255',
        ]);

        // Ambil data siswa
        $data = $request->only([
            'nama_siswa',
            'nis',
            'email',
            'jurusan',
        ]);

        // Upload foto
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')
                ->store('foto-siswa', 'public');
        }

        // Simpan data
        Siswa::create($data);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil ditambahkan.');
    }

    public function show(Siswa $siswa)
    {
        return view('siswa.show', compact('siswa'));
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.edit', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        // Validasi data
        $request->validate([
            'nama_siswa' => 'required|string|max:255',
            'nis'        => 'required|string|unique:siswas,nis|max:50',
            'email'      => 'nullable|email|unique:siswas,email|max:255',
            'kelas'      => 'required|string|max:50',
            'foto'       => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'jurusan'    => 'required|string|max:255',
        ]);

        // Ambil data siswa
        $data = $request->only([
            'nama_siswa',
            'nis',
            'email',
            'jurusan',
        ]);

        // Jika upload foto baru
        if ($request->hasFile('foto')) {

            // Hapus foto lama
            if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
                Storage::disk('public')->delete($siswa->foto);
            }

            // Simpan foto baru
            $data['foto'] = $request->file('foto')
                ->store('foto-siswa', 'public');
        }

        // Update data
        $siswa->update($data);

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        // Hapus foto dari storage
        if ($siswa->foto && Storage::disk('public')->exists($siswa->foto)) {
            Storage::disk('public')->delete($siswa->foto);
        }

        // Hapus data siswa
        $siswa->delete();

        return redirect()
            ->route('siswa.index')
            ->with('success', 'Data siswa berhasil dihapus.');
    }
    public function cetakpdf()
    {
        $siswas = Siswa::all();
        $pdf = PDF::loadView('siswa.cetakpdf', compact('siswas')) ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-data-siswa.pdf');
    }
}
