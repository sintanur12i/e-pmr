<div class="mb-3">
    <label for="name" class="form-label">Nama Periode</label>
    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $period->name ?? '') }}">
    @error('name')
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
    @error('status')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>