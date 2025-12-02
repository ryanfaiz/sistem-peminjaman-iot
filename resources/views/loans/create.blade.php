@extends('layouts.app')

@section('header', 'Form Peminjaman Barang')

@section('content')
<div class="container">
    <h4>Ajukan Peminjaman Baru</h4>

    <!-- Pesan sukses/error -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('user.borrow.store') }}" method="POST">
        @csrf

        <!-- Tanggal Peminjaman -->
        <div class="mb-3">
            <label for="loan_date" class="form-label">Tanggal Peminjaman</label>
            <input type="date" name="loan_date" id="loan_date" class="form-control"
                   value="{{ now()->format('Y-m-d') }}"
                   min="{{ now()->format('Y-m-d') }}" required>
        </div>

        <!-- Tanggal Pengembalian -->
        <div class="mb-3">
            <label for="due_date" class="form-label">Tanggal Pengembalian</label>
            <input type="date" name="due_date" id="due_date" class="form-control" required>
        </div>

        <!-- Tujuan Peminjaman -->
        <div class="mb-3">
            <label for="purpose" class="form-label">Tujuan Peminjaman</label>
            <input type="text" name="purpose" id="purpose" class="form-control" required>
        </div>

        <!-- Daftar Barang -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Pilih</th>
                    <th>Nama Barang</th>
                    <th>Kode</th>
                    <th>Kondisi</th>
                    <th>Stok Tersedia</th>
                    <th>Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <tr>
                    <td>
                        <input type="checkbox" name="items[{{ $item->id }}][selected]" value="1"
                               class="item-checkbox"
                               @if($item->available_quantity == 0) disabled @endif>
                    </td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->condition }}</td>
                    <td>{{ $item->available_quantity }}</td>
                    <td>
                        <input type="number" name="items[{{ $item->id }}][quantity]" value="0"
                               min="1" max="{{ $item->available_quantity }}"
                               class="form-control item-quantity" readonly>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Ajukan Peminjaman</button>
    </form>
</div>

<script>
    // Enable quantity input only if checkbox checked, default 1
    document.querySelectorAll('.item-checkbox').forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            let quantityInput = this.closest('tr').querySelector('.item-quantity');
            quantityInput.readOnly = !this.checked;
            if (this.checked && quantityInput.value == 0) {
                quantityInput.value = 1; // default minimal 1
            }
            if (!this.checked) quantityInput.value = 0;
        });
    });

    // Client-side date constraints for `type="date"` inputs:
    // - loan_date cannot be in the past (min = today)
    // - due_date cannot be earlier than loan_date
    (function () {
        const loanInput = document.getElementById('loan_date');
        const dueInput = document.getElementById('due_date');

        function todayYMD() {
            const now = new Date();
            const y = now.getFullYear();
            const m = String(now.getMonth() + 1).padStart(2, '0');
            const d = String(now.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function setLoanMin() {
            if (!loanInput) return;
            loanInput.min = todayYMD();
        }

        function syncDueMin() {
            if (!loanInput || !dueInput) return;
            if (loanInput.value) {
                dueInput.min = loanInput.value;
                if (dueInput.value && dueInput.value < dueInput.min) {
                    dueInput.value = dueInput.min;
                }
            } else {
                dueInput.min = todayYMD();
            }
        }

        setLoanMin();
        syncDueMin();

        if (loanInput) loanInput.addEventListener('change', syncDueMin);
        if (dueInput) dueInput.addEventListener('change', function () {
            if (loanInput && this.value && loanInput.value && this.value < loanInput.value) {
                this.value = loanInput.value;
            }
        });
    })();
</script>
@endsection