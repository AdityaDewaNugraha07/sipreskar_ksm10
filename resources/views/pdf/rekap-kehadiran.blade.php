<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Kehadiran KSM 10</title>
    <style>
        /* 1. RESET FONT KE SANS-SERIF (MODERN) */
        @page { margin: 40px 50px; }
        body {
            font-family: Helvetica, Arial, sans-serif; /* Paksa DomPDF pakai Sans-Serif */
            font-size: 12px;
            color: #435971; /* Warna teks abu kebiruan modern */
            line-height: 1.4;
        }

        /* 2. HEADER KOP SURAT LEBIH CASUAL */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #696cff; /* Aksen ungu Sneat */
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-cell {
            width: 65px;
            padding-right: 20px; /* Jarak lega antara logo dan teks */
        }
        .title-cell h2 {
            margin: 0 0 5px 0;
            font-weight: bold;
            color: #32475c;
            letter-spacing: 0.5px;
        }
        .title-cell h4 {
            margin: 0;
            font-weight: bold;
            color: #696cff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .date-cell {
            text-align: right;
            font-size: 11px;
        }
        .date-cell strong {
            color: #696cff;
            font-size: 12px;
            font-weight: bold;
        }

        /* 3. KOTAK INFO PERIODE (Lebih Kekinian dengan Outline Biru/Ungu) */
        .info-box {
            background-color: transparent; /* Hilangkan background abu-abu */
            border: 0.5px solid #696cff; /* Tambahkan garis outline warna Sneat */
            padding: 12px 15px;
            border-radius: 8px; /* Sudut melengkung */
            margin-bottom: 20px;
        }
        .info-table { width: 100%; }
        .info-table td {
            padding: 2px 0;
            font-size: 12px;
            color: #566a7f;
        }
        .info-label {
            font-weight: bold;
            width: 80px;
        }

        /* 4. TABEL DATA (Clean & Simple) */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            padding: 12px 10px;
        }
        .data-table th {
            background-color: #e7e7ff; /* Background ungu sangat soft */
            color: #696cff; /* Teks ungu terang */
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            /* border-bottom: 2px solid #d9dbfb; */
            text-align: left;
        }
        .data-table td {
            border-bottom: 1px solid #ebeef0;
            color: #566a7f;
        }
        
        .text-center { text-align: center !important; }
        
        /* Style Badge untuk Status */
        .status-hadir {
            color: #18a74e;
            font-weight: bold;
        }
        .status-other {
            color: #8592a3;
            font-weight: bold;
        }
    </style>
</head>
<body>

    @php
        // Konversi logo ke Base64
        $pathLogo = public_path('assets/img/logo-ksm-new.png');
        $type = pathinfo($pathLogo, PATHINFO_EXTENSION);
        if (file_exists($pathLogo)) {
            $dataImg = file_get_contents($pathLogo);
            $base64Logo = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
        } else {
            $base64Logo = '';
        }

        // Format Tanggal Periode
        $periodeDates = explode(' s/d ', $periode);
        $periodeFormatted = count($periodeDates) == 2 
            ? \Carbon\Carbon::parse($periodeDates[0])->translatedFormat('d F Y') . ' s/d ' . \Carbon\Carbon::parse($periodeDates[1])->translatedFormat('d F Y') 
            : $periode;
    @endphp

    <!-- KOP HEADER -->
    <table class="header-table">
        <tr>
            <td class="logo-cell">
                @if($base64Logo)
                    <img src="{{ $base64Logo }}" width="65">
                @endif
            </td>
            <td class="title-cell">
                <h2>KARANG TARUNA KSM 10</h2>
                <h4>Laporan Rekapitulasi Kehadiran</h4>
            </td>
            <td class="date-cell">
                Dicetak pada:<br>
                <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</strong><br>
                {{ \Carbon\Carbon::now()->format('H:i') }} WIB
            </td>
        </tr>
    </table>

    <!-- KOTAK INFO -->
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="info-label">Periode</td>
                <td>: {{ $periodeFormatted }}</td>
            </tr>
            <tr>
                <td class="info-label">Total Data</td>
                <td>: {{ count($presensi) }} Kehadiran</td>
            </tr>
        </table>
    </div>

    <!-- TABEL DATA -->
    <table class="data-table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th width="35%">Nama Anggota</th>
                <th class="text-center" width="25%">Tanggal</th>
                <th class="text-center" width="15%">Jam Hadir</th>
                <th class="text-center" width="20%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($presensi as $index => $p)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $p->anggota->nama ?? '-' }}</strong></td>
                <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</td>
                <td class="text-center">{{ \Carbon\Carbon::parse($p->jam_hadir)->format('H:i') }} WIB</td>
                <td class="text-center">
                    @if(strtolower($p->status) == 'hadir')
                        <span class="status-hadir">HADIR</span>
                    @else
                        <span class="status-other">{{ strtoupper($p->status) }}</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center" style="padding: 30px; color: #a1acb8;">
                    Wah, belum ada data presensi di tanggal ini nih.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>