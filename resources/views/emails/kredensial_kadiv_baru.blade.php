<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kredensial Akun Baru Staffify</title>
</head>

<body
    style="margin: 0; padding: 0; background-color: #f8fafc; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #334155;">

    <table align="center" border="0" cellpadding="0" cellspacing="0" width="100%"
        style="max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #e2e8f0;">

        <tr>
            <td style="padding: 32px; background-color: #1e293b; text-align: center; border-bottom: 3px solid #2563eb;">
                <h1 style="margin: 0; color: #ffffff; font-size: 24px; font-weight: 800; tracking-wide: 1px;">STAFFIFY
                </h1>
                <p
                    style="margin: 4px 0 0 0; color: #94a3b8; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 2px;">
                    BEM Politeknik Negeri Jakarta</p>
            </td>
        </tr>

        <tr>
            <td style="padding: 40px 32px;">
                <h2 style="margin: 0 0 16px 0; color: #0f172a; font-size: 20px; font-weight: 700;">Halo,
                    {{ $name }}!</h2>
                <p style="margin: 0 0 24px 0; font-size: 14px; line-height: 1.6; color: #475569;">
                    Project Officer baru saja memperbarui data akun Kepala Divisi Anda di sistem **Staffify**.
                    Dikarenakan adanya perubahan email, sistem telah meng-generate kata sandi baru demi menjaga keamanan
                    akses Anda.
                </p>

                <table border="0" cellpadding="0" cellspacing="0" width="100__"
                    style="background-color: #f1f5f9; border-radius: 12px; padding: 20px; margin-bottom: 24px; border: 1px solid #e2e8f0;">
                    <tr>
                        <td style="padding: 6px 0; font-size: 13px; color: #64748b; font-weight: bold; width: 100px;">
                            Email Resmi:</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #0f172a; font-weight: bold;">
                            {{ $email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-size: 13px; color: #64748b; font-weight: bold;">Password Baru:
                        </td>
                        <td
                            style="padding: 6px 0; font-size: 14px; color: #2563eb; font-weight: black; font-family: 'Courier New', Courier, monospace; letter-spacing: 0.5px;">
                            {{ $password }}</td>
                    </tr>
                </table>

                <p style="margin: 0 0 32px 0; font-size: 13px; line-height: 1.6; color: #64748b; font-style: italic;">
                    *Catatan: Harap simpan informasi login ini dengan baik dan jangan membagikannya kepada siapa pun.
                    Anda dapat mengubah kata sandi ini kapan saja setelah berhasil masuk ke sistem.
                </p>

                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                    <tr>
                        <td align="center">
                            <a href="{{ route('login') }}" target="_blank"
                                style="display: inline-block; background-color: #2563eb; color: #ffffff; font-weight: bold; font-size: 13px; text-decoration: none; padding: 14px 40px; border-radius: 8px; text-transform: uppercase; letter-spacing: 1px; box-shadow: 0 4px 6px rgba(37, 99, 235, 0.2);">
                                Login ke Sistem Staffify
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td
                style="padding: 24px 32px; background-color: #f8fafc; border-top: 1px solid #e2e8f0; text-align: center;">
                <p style="margin: 0; font-size: 12px; font-weight: bold; color: #1e293b;">BEM POLITEKNIK NEGERI JAKARTA
                </p>
                <p style="margin: 4px 0 0 0; font-size: 11px; color: #64748b;">Kabinet Simpul Perubahan 2026</p>
            </td>
        </tr>
    </table>

</body>

</html>
