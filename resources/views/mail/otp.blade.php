@extends('mail.base-layout')

@section('title', 'رمز التحقق - مساكن بلس')

@section('content')
    <h2 style="font-size: 22px; font-weight: 600; color: #115e59; margin: 0 0 20px;">مرحبًا </h2>
    <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin: 0 0 20px;">
        لقد طلبنا رمز تحقق لتأكيد هويتك في <span style="font-weight: 600; color: #115e59;">مساكن بلس</span>. استخدم الرمز أدناه لإكمال العملية:
    </p>
    <div style="text-align: center; margin: 30px 0;">
        <span style="display: inline-block; padding: 12px 30px; background-color: #f1f5f9; color: #115e59; font-size: 24px; font-weight: bold; border-radius: 8px;">
            {{ $otp }}
        </span>
    </div>
    <p style="font-size: 14px; color: #6b7280; margin: 0 0 20px;">
        هذا الرمز صالح لمدة 10 دقائق فقط.
    </p>
    <p style="font-size: 14px; color: #6b7280; margin: 0;">
        إذا لم تطلب هذا الرمز، يرجى التواصل معنا على الفور.
    </p>
@endsection