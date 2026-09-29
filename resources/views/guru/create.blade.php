@extends('adminlte::page')

@section('title', 'Tambah Guru')

@section('content_header')
    <h1>Tambah Guru</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
             <form action="{{ route('guru.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="nama_guru">Nama Guru</label>
                    <input type="text" name="nama_guru" class="form-control @error('nama_guru') is-invalid @enderror" id="nama_guru" required value="{{ old('nama_guru') }}">
                    @error('nama_guru')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nip">NIP</label>
                    <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" id="nip" required value="{{ old('nip') }}">
                    @error('nip')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="jabatan">Jabatan</label>
                    <input type="text" name="jabatan" class="form-control @error('jabatan') is-invalid @enderror" id="jabatan" required placeholder="Contoh: Guru Matematika" value="{{ old('jabatan') }}">
                    @error('jabatan')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="unit_sekolah">Unit Sekolah</label>
                    <input type="text" name="unit_sekolah" class="form-control @error('unit_sekolah') is-invalid @enderror" id="unit_sekolah" required placeholder="Contoh: SMK Negeri 1" value="{{ old('unit_sekolah') }}">
                    @error('unit_sekolah')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-group">
                     <label for="foto">Foto</label>
                     <input type="file" name="foto" class="form-control-file @error('foto') is-invalid @enderror" id="foto" accept="image/*">
                     <small class="form-text form-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                     @error('foto')
                       <span class="text-danger" role="alert">
                          <strong>{{ $message }}</strong>
                       </span>
                     @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{ old('email') }}">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('guru.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@stop
