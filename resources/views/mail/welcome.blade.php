@extends('mail.base-layout')

@section('title', 'مرحبًا بك في مساكن بلس')

@section('content')
    <h2 style="font-size: 22px; font-weight: 600; color: #115e59; margin: 0 0 20px;">أهلاً {{ $name }}</h2>
    <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin: 0 0 20px;">
        مرحبًا بك في <span style="font-weight: 600; color: #115e59;">مساكن بلس</span>! حسابك مفعّل الآن، وأنت جاهز لاستكشاف مجموعتنا الواسعة من العقارات.
    </p>
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $otp }}" style="display: inline-block; padding: 12px 30px; background-color: #275f59; color: #ffffff; font-size: 16px; font-weight: 500; text-decoration: none; border-radius: 8px; transition: background-color 0.2s ease-in-out;">
            ابدأ الآن
        </a>
    </div>
    <p style="font-size: 14px; color: #6b7280; margin: 0;">
        إذا كنت بحاجة إلى مساعدة، لا تتردد في التواصل مع فريق الدعم لدينا.
    </p>
@endsection