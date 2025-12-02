@extends('layouts.app')

@section('title', 'Tambah Barang Baru')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary mb-0"><i class="fas fa-plus-circle me-2"></i> Tambah Barang Inventaris Baru</h2>
                <a href="{{ route('admin.items.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>

            <div class="card shadow-lg">
                <div class="card-body">
                    <form action="{{ route('admin.items.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                        @csrf
                        
                        <div class="col-md-6">
                            <label for="name" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="code" class="form-label">Kode Inventaris <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                            <div class="form-text">Contoh: ARDU-UNO-001</div>
                            @error('code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="total_quantity" class="form-label">Total Kuantitas <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('total_quantity') is-invalid @enderror" id="total_quantity" name="total_quantity" value="{{ old('total_quantity') }}" min="1" required>
                            @error('total_quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="condition" class="form-label">Kondisi Barang <span class="text-danger">*</span></label>
                            <select class="form-select @error('condition') is-invalid @enderror" id="condition" name="condition" required>
                                <option value="Baik" {{ old('condition') == 'Baik' ? 'selected' : '' }}>Baik</option>
                                <option value="Perlu Perawatan" {{ old('condition') == 'Perlu Perawatan' ? 'selected' : '' }}>Perlu Perawatan</option>
                                <option value="Rusak" {{ old('condition') == 'Rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
                            @error('condition') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi (Opsional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="photo" class="form-label">Foto Barang (Opsional)</label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror" id="photo" name="photo">
                            <div class="form-text">Maks 2MB, format JPG/PNG.</div>
                            @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i> Simpan Barang</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection