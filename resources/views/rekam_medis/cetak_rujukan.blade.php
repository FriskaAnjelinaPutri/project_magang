<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat Rujukan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #000;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 0 auto;
            background: white;
            box-sizing: border-box;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 18pt;
            margin: 0;
            font-weight: bold;
        }
        .header p {
            font-size: 12pt;
            margin: 2px 0;
        }
        .line {
            border-top: 2px solid #000;
            margin-top: 10px;
            margin-bottom: 30px;
        }
        .title {
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 30px;
        }
        .content-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12pt;
            line-height: 1.8;
        }
        .content-table td {
            vertical-align: top;
        }
        .col-label {
            width: 180px;
            font-weight: bold;
        }
        .col-colon {
            width: 20px;
            text-align: center;
        }
        .col-value {
            border-bottom: 1px dotted #000;
        }
        .col-value.no-border {
            border-bottom: none;
        }
        .indent {
            text-indent: 40px;
            margin-top: 20px;
            margin-bottom: 20px;
            font-size: 12pt;
            line-height: 1.5;
        }
        .signature {
            float: right;
            text-align: center;
            margin-top: 30px;
            width: 250px;
            font-size: 12pt;
        }
        .signature-name {
            margin-top: 80px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 10px;
            margin-bottom: 5px;
        }
        .text-value {
            border-bottom: 1px dotted #000;
            min-height: 1.5em;
            display: inline-block;
            width: 100%;
        }
        
        @media print {
            body {
                background: none;
            }
            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="page">
        <div class="header">
            <h1>DOKTER GIGI NOVIANDRI</h1>
            <p>Jl. Urip Sumoharjo No. 365 Balai-Balai</p>
            <p>Padang Panjang HP. 08126794403</p>
        </div>
        <div class="line"></div>
        
        <div class="title">SURAT RUJUKAN</div>
        
        <table class="content-table" style="margin-bottom: 20px;">
            <tr>
                <td class="col-label" style="width: 130px;">Yth. Dokter Gigi</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $rekamMedis->rujukan_dokter }}</td>
            </tr>
            <tr>
                <td class="col-label" style="width: 130px;">Di RSU</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $rekamMedis->rujukan_rs }}</td>
            </tr>
        </table>
        
        <div class="indent">
            Mohon pemeriksaan dan pengobatan lebih lanjut terhadap penderita,
        </div>
        
        @php
            // Hitung umur
            $umur = '-';
            if ($rekamMedis->pasien && $rekamMedis->pasien->tanggal_lahir) {
                $umur = \Carbon\Carbon::parse($rekamMedis->pasien->tanggal_lahir)->age . ' Tahun';
            }
        @endphp
        
        <table class="content-table">
            <tr>
                <td class="col-label">Nama Pasien</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $rekamMedis->pasien->nama_pasien ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-label">Jenis Kelamin</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $rekamMedis->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : ($rekamMedis->pasien->jenis_kelamin == 'P' ? 'Perempuan' : '-') }}</td>
            </tr>
            <tr>
                <td class="col-label">Umur</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $umur }}</td>
            </tr>
            <tr>
                <td class="col-label">No. Telpon</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $rekamMedis->pasien->no_hp ?? '-' }}</td>
            </tr>
            <tr>
                <td class="col-label">Alamat Rumah</td>
                <td class="col-colon">:</td>
                <td class="col-value">{{ $rekamMedis->pasien->alamat ?? '-' }}</td>
            </tr>
        </table>
        
        <div class="section-title" style="margin-top: 20px;">Anamnese</div>
        <table class="content-table">
            <tr>
                <td class="col-label">Keluhan</td>
                <td class="col-colon">:</td>
                <td class="col-value" style="padding-bottom: 5px;">{{ $rekamMedis->keluhan }}</td>
            </tr>
            <tr>
                <td class="col-label">Diagnosa sementara</td>
                <td class="col-colon">:</td>
                <td class="col-value" style="padding-bottom: 5px;">{{ $rekamMedis->rujukan_diagnosa_sementara }}</td>
            </tr>
            <tr>
                <td class="col-label">Kasus</td>
                <td class="col-colon">:</td>
                <td class="col-value" style="padding-bottom: 5px;">{{ $rekamMedis->rujukan_kasus }}</td>
            </tr>
        </table>
        
        <div class="section-title" style="margin-top: 15px;">Terapi/Obat yang telah diberikan :</div>
        <div style="border-bottom: 1px dotted #000; min-height: 1.5em; width: 100%; margin-bottom: 25px; line-height: 1.5; font-size: 12pt;">
            {{ $rekamMedis->rujukan_terapi }}
        </div>
        
        <div class="indent" style="margin-top: 30px; text-align: justify;">
            Demikian surat rujukan ini kami kirim, kami mohon bantuan pada Bapak/Ibuk dan kerjasama yang baik. Atas perhatian Bapak/Ibuk kami ucapkan terima kasih.
        </div>
        
        <div class="signature">
            <p style="margin: 0;">Padang Panjang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <p style="margin: 0;">Hormat Kami</p>
            <p class="signature-name">(drg. Noviandri)</p>
        </div>
    </div>
</body>
</html>
