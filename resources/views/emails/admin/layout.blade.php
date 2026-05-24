<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $slot ?? 'Notifikasi Subly' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            padding: 20px;
            line-height: 1.6;
        }
        .wrapper {
            max-width: 620px;
            margin: 0 auto;
        }
        .header {
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #a855f7 100%);
            border-radius: 16px 16px 0 0;
            padding: 32px 40px;
            text-align: center;
        }
        .header .logo {
            font-size: 28px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.5px;
        }
        .header .logo span {
            color: #c4b5fd;
        }
        .header .subtitle {
            margin-top: 6px;
            color: #ddd6fe;
            font-size: 13px;
        }
        .body {
            background: #1e293b;
            padding: 36px 40px;
        }
        .badge {
            display: inline-block;
            padding: 5px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 20px;
        }
        .badge-purple { background: #4c1d95; color: #c4b5fd; }
        .badge-blue   { background: #1e3a5f; color: #93c5fd; }
        .badge-green  { background: #064e3b; color: #6ee7b7; }
        .badge-orange { background: #431407; color: #fdba74; }

        h1 {
            font-size: 22px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 10px;
        }
        p {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 16px;
        }
        .info-card {
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 12px;
            padding: 20px 24px;
            margin: 24px 0;
        }
        .info-card table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-card tr td {
            padding: 8px 0;
            font-size: 14px;
            border-bottom: 1px solid #1e293b;
        }
        .info-card tr:last-child td {
            border-bottom: none;
        }
        .info-card td:first-child {
            color: #64748b;
            width: 40%;
            padding-right: 12px;
        }
        .info-card td:last-child {
            color: #e2e8f0;
            font-weight: 500;
        }
        .btn {
            display: inline-block;
            padding: 13px 28px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            margin-top: 8px;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
        }
        .message-box {
            background: #0f172a;
            border-left: 3px solid #6366f1;
            border-radius: 0 8px 8px 0;
            padding: 16px 20px;
            margin: 20px 0;
            font-size: 14px;
            color: #cbd5e1;
            font-style: italic;
        }
        .stars {
            color: #fbbf24;
            font-size: 18px;
            margin-bottom: 8px;
        }
        .footer {
            background: #0f172a;
            border-radius: 0 0 16px 16px;
            padding: 24px 40px;
            text-align: center;
            border-top: 1px solid #1e293b;
        }
        .footer p {
            font-size: 12px;
            color: #475569;
            margin: 0;
        }
        .footer a { color: #6366f1; text-decoration: none; }
        .divider {
            height: 1px;
            background: #1e293b;
            margin: 24px 0;
        }
        .highlight {
            color: #a78bfa;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <div class="logo">Sub<span>ly</span></div>
            <div class="subtitle">Admin Notification System</div>
        </div>
        <div class="body">
            {{ $slot }}
        </div>
        <div class="footer">
            <p>Email ini dikirim otomatis oleh sistem <a href="{{ config('app.url') }}">Subly</a>.<br>
            &copy; {{ date('Y') }} Subly. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
