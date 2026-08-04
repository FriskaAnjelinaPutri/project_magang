<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Rekam Medis - Klinik Drg. Noviandri</title>
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
            text-align: left;
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

    <button class="btn-print" onclick="window.print()">🖨️ Cetak / Simpan PDF Sekarang</button>

    <div class="kop-surat">
        <h1>KLINIK GIGI DRG. NOVIANDRI</h1>
        <p>Jl. Contoh Alamat Klinik No. 123, Kota Anda, Provinsi</p>
        <p>Telepon: (021) 1234567 | Email: info@kliniknoviandri.com</p>
    </div>

    <div class="judul-laporan">
        LAPORAN REKAM MEDIS PASIEN
    </div>
    
    <p>Tanggal Pemeriksaan: <strong>{{ \Carbon\Carbon::parse($tanggalFilter)->translatedFormat('d F Y') }}</strong><br>
    Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="12%">Tgl Periksa</th>
                <th width="18%">Nama Pasien</th>
                <th width="15%">Layanan</th>
                <th width="15%">Keluhan</th>
                <th width="17%">Tindakan</th>
                <th width="18%">Resep Obat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekamMedis as $index => $rm)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($rm->tanggal_periksa)->translatedFormat('d M Y') }}</td>
                    <td>{{ $rm->pasien->nama_pasien ?? '-' }}</td>
                    <td>{{ $rm->pendaftaran->layanan->nama_layanan ?? '-' }}</td>
                    <td>
                        {{ $rm->keluhan }}
                    </td>
                    <td>{{ $rm->tindakan }}</td>
                    <td>{{ $rm->resep_obat }}</td>
                </tr>
            @endforeach
            @if($rekamMedis->isEmpty())
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">Belum ada data rekam medis.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <table class="tanda-tangan">
        <tr>
            <td></td>
            <td class="kanan">
                Kota Anda, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Pimpinan Klinik,<br>
                <div class="ttd-space"></div>
                <strong>drg. Noviandri</strong>
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
