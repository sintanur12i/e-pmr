<div class="mb-3">
    <label for="name" class="form-label">Nama Periode</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $period->name ?? '') }}">
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="angkatan" class="form-label">Angkatan</label>
    <input type="text" name="angkatan" id="angkatan" class="form-control @error('angkatan') is-invalid @enderror"
           value="{{ old('angkatan', $period->angkatan ?? '') }}" placeholder="Contoh: 23">
    @error('angkatan')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="start_date" class="form-label">Tanggal Mulai</label>
    <input type="date" name="start_date" id="start_date" class="form-control @error('start_date') is-invalid @enderror"
           value="{{ old('start_date', $period->start_date ?? '') }}">
    @error('start_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="end_date" class="form-label">Tanggal Selesai</label>
    <input type="date" name="end_date" id="end_date" class="form-control @error('end_date') is-invalid @enderror"
           value="{{ old('end_date', $period->end_date ?? '') }}">
    @error('end_date')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="status" class="form-label">Status</label>
    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
        <option value="active" {{ old('status', $period->status ?? '') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ old('status', $period->status ?? '') === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    <small class="text-muted">"Active" berarti periode ini sedang berjalan/dilayani (menentukan agenda, gabung unit, dll).</small>
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3 form-check">
    <input type="checkbox" name="registration_open" id="registration_open" value="1" class="form-check-input @error('registration_open') is-invalid @enderror"
           {{ old('registration_open', $period->registration_open ?? false) ? 'checked' : '' }}>
    <label for="registration_open" class="form-check-label">Buka Pendaftaran Calon Anggota</label>
    <small class="d-block text-muted">Centang ini kalau periode ini sedang menerima pendaftar baru. Hanya boleh 1 periode yang aktif menerima pendaftaran dalam satu waktu — mencentang ini otomatis menutup pendaftaran di periode lain.</small>
    @error('registration_open')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>