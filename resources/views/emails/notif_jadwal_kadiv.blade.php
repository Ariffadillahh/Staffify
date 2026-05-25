<h2>Halo Kadiv {{ $pendaftaran->divisi->nama_divisi }},</h2>
<p>Ada satu kandidat baru yang telah memilih jadwal wawancara di divisi Anda.</p>
<ul>
    <li><strong>Nama:</strong> {{ $pendaftaran->nama_lengkap }}</li>
    <li><strong>NIM:</strong> {{ $pendaftaran->nim }}</li>
    <li><strong>Hari/Tanggal:</strong> {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') }}</li>
    <li><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($jadwal->waktu_mulai)->format('H:i') }} -
        {{ \Carbon\Carbon::parse($jadwal->waktu_selesai)->format('H:i') }} WIB</li>
</ul>
<p>Silakan login ke sistem Staffify untuk melihat detail lengkapnya.</p>
