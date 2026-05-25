<h2>Halo {{ $user->name }},</h2>
<p>Anda telah ditunjuk sebagai Kepala Divisi <strong>{{ $divisi->nama_divisi }}</strong>.</p>
<p>Berikut adalah detail login Anda:</p>
<ul>
    <li>Email: {{ $user->email }}</li>
    <li>Password: {{ $password }}</li>
</ul>
<p>Silakan login di sistem Staffify untuk mengatur kriteria penilaian divisi Anda.</p>
<p>Terima kasih,</p>
<p>Tim Staffify</p>