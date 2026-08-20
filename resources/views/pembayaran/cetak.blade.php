<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran - Klinik Drg. Noviandri</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background-color: #fff;
            margin: 0;
            padding: 2cm;
        }

        /* Kop Surat */
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h1 {
            margin: 0;
            font-size: 18pt;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 2px 0;
            font-size: 11pt;
        }

        .judul-laporan {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        /* Tabel Data */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }

        /* Tanda Tangan */
        .tanda-tangan {
            margin-top: 50px;
            width: 100%;
        }
        .tanda-tangan td {
            border: none;
            padding: 0;
            width: 50%;
        }
        .tanda-tangan .kanan {
            text-align: right;
        }
        .ttd-space {
            height: 80px;
        }

        /* Tombol Cetak (hanya tampil di layar) */
        .btn-print {
            display: block;
            width: 200px;
            margin: 0 auto 30px;
            padding: 10px;
            background-color: #EA941D;
            color: white;
            text-align: center;
            text-decoration: none;
            font-family: Arial, sans-serif;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }

        @media print {
            body {
                padding: 0;
            }
            .btn-print {
                display: none;
            }
            @page {
                margin: 2cm;
            }
        }
    </style>
</head>
<body>

    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF</button>

    <div class="kop-surat">
        <h1>KLINIK GIGI DRG. NOVIANDRI</h1>
        <p>Jl.urip sumoharjo no 356 balai balai timur,kec padang panjang timur,kota padang panjang</p>
        <p>Telepon: 08126794403 | Email: Noviandri@gmail.com</p>
    </div>

    <div class="judul-laporan">
        LAPORAN TRANSAKSI PEMBAYARAN PASIEN
    </div>

    <p>Tanggal Transaksi: <strong>{{ \Carbon\Carbon::parse($tanggalFilter)->translatedFormat('d F Y') }}</strong><br>
    Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="8%">No. Antrian</th>
                <th width="10%">Tanggal</th>
                <th width="15%">Nama Pasien</th>
                <th width="15%">Layanan</th>
                <th width="12%">Biaya Layanan (Rp)</th>
                <th width="12%">Biaya Obat (Rp)</th>
                <th width="8%">Metode</th>
                <th width="5%">Status</th>
                <th width="10%">Total Bayar (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $totalSemua = 0; @endphp
            @foreach ($pembayaran as $index => $row)
                @php
                    $isLunas = strtolower(trim($row->status)) === 'lunas';
                    if($isLunas) $totalSemua += $row->total_bayar;
                    
                    $totalLayanan = 0;
                    if(optional($row->pendaftaran)->layanans){
                        foreach($row->pendaftaran->layanans as $lay){
                            $totalLayanan += $lay->harga * ($lay->pivot->jumlah ?? 1);
                        }
                    }
                @endphp
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td style="text-align: center;">{{ optional(optional($row->pendaftaran)->antrian)->nomor_antrian ?? '-' }}</td>
                    <td style="text-align: center;">{{ $row->tanggal_pembayaran ?? $row->created_at->format('d M Y') }}</td>
                    <td>{{ optional(optional($row->pendaftaran)->pasien)->nama_pasien ?? '-' }}</td>
                    <td>{{ optional($row->pendaftaran)->layanans ? $row->pendaftaran->layanans->pluck('nama_layanan')->implode(', ') : '-' }}</td>
                    <td style="text-align: right;">{{ number_format($totalLayanan, 0, ',', '.') }}</td>
                    <td style="text-align: right;">{{ number_format(optional(optional($row->pendaftaran)->rekamMedis)->biaya_obat ?? 0, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ $row->metode_pembayaran ? ucfirst($row->metode_pembayaran) : '-' }}</td>
                    <td style="text-align: center; {{ $isLunas ? 'color: green;' : 'color: red;' }}">
                        <strong>{{ strtoupper($row->status) }}</strong>
                    </td>
                    <td style="text-align: right;">{{ number_format($row->total_bayar, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            @if($pembayaran->isEmpty())
                <tr>
                    <td colspan="10" style="text-align: center; padding: 20px;">Tidak ada transaksi pada tanggal ini.</td>
                </tr>
            @else
                <tr>
                    <td colspan="9" style="text-align: right; font-weight: bold;">TOTAL PENDAPATAN:</td>
                    <td style="text-align: right; font-weight: bold; font-size: 14pt;">Rp {{ number_format($totalSemua, 0, ',', '.') }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="tanda-tangan">
        <tr>
            <td></td>
            <td class="kanan">
                Padang Panjang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Asisten Doker,<br>
                <div class="ttd-space"></div>
                <strong>{{ auth()->user()->name ?? 'weni' }}</strong>
            </td>
        </tr>
    </table>

    <script>
        // Opsional: Otomatis memunculkan dialog print saat halaman dimuat
        window.onload = function() {
            // window.print();
        }
    </script>
</body>
</html>
