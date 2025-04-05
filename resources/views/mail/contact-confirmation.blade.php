@extends('mail.base-layout')

@section('title', 'تأكيد استلام رسالتك - مساكن بلس')

@section('content')
    <h2 style="font-size: 22px; font-weight: 600; color: #115e59; margin: 0 0 20px;">مرحبًا {{ $name }}</h2>
    <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin: 0 0 20px;">
        شكرًا لتواصلك مع <span style="font-weight: 600; color: #115e59;">مساكن بلس</span>. لقد تلقينا رسالتك التالية:
    </p>
    <div style="background-color: #f1f5f9; padding: 15px; border-radius: 8px; margin: 20px 0;">
        <p style="font-size: 16px; color: #4b5563; margin: 0;">{{ $otp }}</p>
    </div>
    <p style="font-size: 14px; color: #6b7280; margin: 0;">
        سنقوم بالرد عليك في أقرب وقت ممكن. إذا كنت بحاجة إلى مساعدة فورية، يمكنك الاتصال بنا مباشرة.
    </p>
@endsection