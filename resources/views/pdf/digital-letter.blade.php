<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $letter->letter_type }} - {{ $letter->letter_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            padding: 2cm;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 10pt;
            margin: 2px 0;
        }
        
        .letter-info {
            margin-bottom: 30px;
        }
        
        .letter-info table {
            width: 100%;
            margin-bottom: 20px;
        }
        
        .letter-info td {
            padding: 3px 0;
            vertical-align: top;
        }
        
        .letter-info td:first-child {
            width: 150px;
        }
        
        .letter-info td:nth-child(2) {
            width: 20px;
            text-align: center;
        }
        
        .letter-title {
            text-align: center;
            margin: 30px 0;
        }
        
        .letter-title h3 {
            font-size: 14pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
        }
        
        .letter-title p {
            font-size: 12pt;
            margin-top: 5px;
        }
        
        .letter-content {
            text-align: justify;
            margin-bottom: 40px;
            line-height: 1.8;
        }
        
        .letter-content p {
            margin-bottom: 15px;
        }
        
        .signature {
            margin-top: -50px;
        }
        
        .signature-section {
            float: right;
            width: 250px;
            text-align: center;
        }
        
        .signature-section p {
            margin: 5px 0;
        }
        
        .signature-space {
            height: 10px;
            margin: 20px 0;
        }
        
        .signature-image {
            max-width: 150px;
            max-height: 10px;
            margin-top : -50px;
            margin: 10px auto;
        }
        
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .footer {
            margin-top: 30px;
            font-size: 9pt;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RUKUN TETANGGA (RT)</h1>
        <h2>RT {{ config('app.rt_number', '001') }} / RW {{ config('app.rw_number', '001') }}</h2>
        <p>{{ config('app.rt_address', 'Alamat RT') }}</p>
        <p>Telp: {{ config('app.rt_phone', '-') }}</p>
    </div>

    <div class="letter-info">
        <table>
            <tr>
                <td>Nomor</td>
                <td>:</td>
                <td>{{ $letter->letter_number }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td><strong>{{ ucfirst($letter->letter_type) }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="letter-title">
        <h3>SURAT KETERANGAN {{ strtoupper($letter->letter_type) }}</h3>
        <p>Nomor: {{ $letter->letter_number }}</p>
    </div>

    <div class="letter-content">
        <p>Yang bertanda tangan di bawah ini, Ketua RT {{ config('app.rt_number', '001') }} / RW {{ config('app.rw_number', '001') }}, menerangkan bahwa:</p>
        
        <table style="margin: 20px 0; width: 100%;">
            <tr>
                <td style="width: 150px; padding: 5px 0;">Nama</td>
                <td style="width: 20px;">:</td>
                <td><strong>{{ $letter->resident->name }}</strong></td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">NIK</td>
                <td>:</td>
                <td>{{ $letter->resident->nik }}</td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">No. KK</td>
                <td>:</td>
                <td>{{ $letter->resident->no_kk ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">Alamat</td>
                <td>:</td>
                <td>{{ $letter->resident->address }}</td>
            </tr>
            <tr>
                <td style="padding: 5px 0;">No. HP</td>
                <td>:</td>
                <td>{{ $letter->resident->phone_number }}</td>
            </tr>
        </table>
        <p style="margin-top: 20px;">Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
    </div>

    <div class="signature clearfix">
        <div class="signature-section">
            <p>{{ config('app.rt_city', 'Jakarta') }}, {{ \Carbon\Carbon::parse($letter->issued_date)->isoFormat('D MMMM Y') }}</p>
            <p>Ketua RT {{ config('app.rt_number', '001') }}</p>
            
            {{-- QR Code for verification --}}
            <div class="signature-space">
                <img src="{{ $qrCode }}" alt="QR Code Verifikasi" style="width: 80px; height: 60px; margin: 10px auto; margin-top : -22px;">
            </div>
            
            <p class="signature-name">{{ $letter->issuedBy->name ?? '(_____________________)' }}</p>
            <p style="font-size: 8pt; margin-top: 5px;">Scan QR Code untuk verifikasi</p>
        </div>
    </div>

    <div class="footer">
        <p>Surat ini diterbitkan pada {{ \Carbon\Carbon::parse($letter->issued_date)->isoFormat('dddd, D MMMM Y') }}</p>
        @if($letter->valid_until)
        <p>Berlaku hingga {{ \Carbon\Carbon::parse($letter->valid_until)->isoFormat('D MMMM Y') }}</p>
        @endif
        <p style="margin-top: 10px; font-size: 8pt;">Surat ini dilengkapi dengan QR Code untuk autentikasi digital</p>
    </div>
</body>
</html>
