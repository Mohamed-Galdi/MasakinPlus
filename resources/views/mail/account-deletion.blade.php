@extends('mail.base-layout')

@section('title', 'تأكيد حذف الحساب - مساكن بلس')

@section('content')
    <h2 style="font-size: 22px; font-weight: 600; color: #115e59; margin: 0 0 20px;">مرحبًا {{ $name }}</h2>
    <p style="font-size: 16px; color: #4b5563; line-height: 1.6; margin: 0 0 20px;">
        لقد تلقينا طلبًا لحذف حسابك في <span style="font-weight: 600; color: #115e59;">مساكن بلس</span>. لتأكيد هذا الإجراء، اضغط على الزر أدناه:
    </p>
    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $otp }}" style="display: inline-block; padding: 12px 30px; background-color: #275f59; color: #ffffff; font-size: 16px; font-weight: 500; text-decoration: none; border-radius: 8px; transition: background-color 0.2s ease-in-out;">
            تأكيد الحذف
        </a>
    </div>
    <p style="font-size: 14px; color: #6b7280; margin: 0 0 20px;">
        هذا الرابط صالح لمدة 24 ساعة. بعد التأكيد، سيتم حذف حسابك وبياناتك نهائيًا.
    </p>
    <p style="font-size: 14px; color: #6b7280; margin: 0;">
        إذا لم تطلب هذا، يرجى التواصل معنا على الفور.
    </p>
@endsection