<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Surat Peminjaman #{{ $loan->id }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        .header { text-align: center; margin-bottom: 12px; }
        .meta { margin-bottom: 8px; }
        .footer { margin-top: 18px; font-size: 11px; color: #555; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Surat Peminjaman Barang</h2>
        <div>Nomor: {{ $loan->id }}</div>
    </div>

    <div class="meta">
        <strong>Nama Peminjam:</strong> {{ $loan->user->name ?? '-' }}<br>
        <strong>Tanggal Pinjam:</strong> {{ optional($loan->loan_date)->format('d-m-Y H:i') ?? '-' }}<br>
        <strong>Jatuh Tempo:</strong> {{ optional($loan->due_date)->format('d-m-Y H:i') ?? '-' }}<br>
        <strong>Tujuan:</strong> {{ $loan->purpose ?? '-' }}<br>
        <strong>Status:</strong> {{ ucfirst($loan->status) }}
    </div>

    <h4>Daftar Barang</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Kode</th>
                <th>Kondisi</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loan->items as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->code }}</td>
                <td>{{ $item->condition }}</td>
                <td>{{ $item->pivot->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        surat dicetak oleh mesin pada {{ ($generated_at ?? now())->format('d-m-Y H:i:s') }}
    </div>
</body>
</html>
<script>
    // Trigger print dialog once the printable page loads
    window.addEventListener('load', function () {
        try {
            window.print();
        } catch (e) {
            console.error('Print failed', e);
        }
    });

    // Close the window after printing or if the user cancels print
    function closeAfterPrint() {
        try {
            window.close();
        } catch (e) {
            console.error('Window close failed', e);
        }
    }

    if ('onafterprint' in window) {
        window.onafterprint = closeAfterPrint;
    } else {
        // Fallback: detect print media change
        var mediaQueryList = window.matchMedia('print');
        mediaQueryList.addEventListener('change', function (mql) {
            if (!mql.matches) {
                // print dialog closed
                closeAfterPrint();
            }
        });
    }
</script>