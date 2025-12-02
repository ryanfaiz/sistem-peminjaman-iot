@extends('layouts.app')

@section('header', 'Profil Saya')

@section('content')
<div class="container">
    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">WhatsApp</label>
            <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $user->whatsapp_number) }}" class="form-control" placeholder="0812xxxx (include country code if needed)">
            @error('whatsapp_number')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="form-label">KTM / Kartu Pegawai (unggah foto)</label>
            @if($user->id_card_photo_path)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $user->id_card_photo_path) }}" alt="KTM" style="max-width:200px;" class="img-thumbnail">
                </div>
            @endif
            <input type="file" name="id_card_photo" accept="image/*" class="form-control">
            @error('id_card_photo')<div class="text-danger small">{{ $message }}</div>@enderror
            <div class="form-text">Maks 2MB. Tipe gambar: jpg, png.</div>
        </div>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </form>

    <hr class="my-4">

    <h5>Ubah Kata Sandi</h5>
    <form action="{{ route('user.profile.password.update') }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Kata Sandi Saat Ini</label>
            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required>
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Kata Sandi Baru</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Kata Sandi Baru</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-danger">Ubah Kata Sandi</button>
    </form>
</div>
@endsection