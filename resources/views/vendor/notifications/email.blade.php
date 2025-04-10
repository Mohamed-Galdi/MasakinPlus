@extends('mail.base-layout')

@section('title', 'إعادة تعيين كلمة المرور - مساكن بلس')

@section('content')
    <div dir="rtl">
        <h2 style="font-size: 22px; font-weight: 600; color: #115e59; margin: 0 0 20px;">مرحبًا</h2>
        <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin: 0 0 20px;">
            لقد تلقينا طلبًا لإعادة تعيين كلمة المرور لحسابك في <span style="font-weight: 600; color: #115e59;">مساكن بلس</span>. اضغط على الزر أدناه لمتابعة العملية:
        </p>
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $actionUrl }}" style="display: inline-block; padding: 12px 30px; background-color: #275f59; color: #ffffff; font-size: 16px; font-weight: 500; text-decoration: none; border-radius: 8px; transition: background-color 0.2s ease-in-out;">
                إعادة تعيين كلمة المرور
            </a>
        </div>
        <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin: 0 0 20px;">
            إذا لم يعمل الزر، يمكنك نسخ الرابط التالي ولصقه في متصفحك:
        </p>
        <p style="font-size: 14px; color: #275f59; margin: 0 0 20px; word-break: break-all;">
            <a href="{{ $actionUrl }}" style="color: #275f59; text-decoration: underline;">{{ $displayableActionUrl }}</a>
        </p>
        <p style="font-size: 14px; color: #6b7280; margin: 0 0 20px;">
            هذا الرابط صالح لمدة 60 دقيقة فقط.
        </p>
        <p style="font-size: 14px; color: #6b7280; margin: 0;">
            إذا لم تطلب إعادة التعيين، يمكنك تجاهل هذا البريد الإلكتروني.
        </p>
    </div>
@endsection