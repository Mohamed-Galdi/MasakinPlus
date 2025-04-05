<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title ?? 'مساكن بلس' }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #eef2f7; direction: rtl; text-align: right; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, 'Noto Sans Arabic', sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';">
    <div style="max-width: 600px; margin: 40px auto; padding: 0 10px;">
        <!-- Header -->
        <div style="text-align: center; padding: 20px 0;">
            <h1 style="color: #115e59; font-size: 1.875rem; margin: 0; font-weight: bold;">
                مساكن <span style="color: #eab308;">بلس</span>
            </h1>
        </div>

        <!-- Card -->
        <div style="background-color: #ffffff; border-top: 4px solid #115e59; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden;">
            <div style="padding: 30px;">
                @yield('content')
            </div>
        </div>

        <!-- Footer -->
        <div style="text-align: center; padding: 20px 0; color: #6b7280; font-size: 0.875rem;">
            <p style="margin: 5px 0 0;">© {{ date('Y') }} مساكن بلس. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>