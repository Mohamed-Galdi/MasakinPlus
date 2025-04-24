<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run()
    {
        // اختيار 3 مستخدمين عشوائيين غير إداريين
        $users = User::whereNot('type', 'admin')->inRandomOrder()->take(3)->get();

        $subjects = [
            'استفسار عن طريقة الاستثمار في العقارات',
            'مشكلة في رفع إعلان عقار',
            'طلب تعديل على بيانات العقار',
            'عطل تقني في لوحة التحكم',
            'معلومات عن توزيع الأرباح الشهرية',
        ];

        foreach ($users as $index => $user) {
            // إنشاء تذكرة لكل مستخدم
            $ticket = Ticket::create([
                'user_id' => $user->id,
                'subject' => $subjects[$index],
                'status' => 'open',
                'admin_unread_count' => 0,
                'last_message_at' => now(),
            ]);

            // إنشاء رسائل لكل تذكرة
            $messages = $this->getMessagesBySubject($subjects[$index]);

            $messageCount = count($messages);
            foreach ($messages as $i => $message) {
                $createdAt = Carbon::now()->subHours($messageCount - $i);
                $newMessage = Message::create([
                    'ticket_id' => $ticket->id,
                    'sender' => $message['sender'],
                    'content' => $message['content'],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                $ticket->update(['last_message_at' => $createdAt]);

                if ($message['sender'] === 'user') {
                    $ticket->increment('admin_unread_count');
                }
            }
        }
    }

    private function getMessagesBySubject($subject)
    {
        switch ($subject) {
            case 'استفسار عن طريقة الاستثمار في العقارات':
                return [
                    ['sender' => 'user', 'content' => 'مرحباً، كيف أستطيع الاستثمار في العقارات عبر المنصة؟'],
                    ['sender' => 'admin', 'content' => 'أهلاً وسهلاً! يمكنك البدء من خلال تصفح المشاريع العقارية المتاحة واختيار المشروع الذي يناسبك.'],
                    ['sender' => 'user', 'content' => 'هل هناك حد أدنى للاستثمار؟'],
                    ['sender' => 'admin', 'content' => 'نعم، الحد الأدنى هو ٥٬٠٠٠ ريال سعودي لكل مشروع.'],
                    ['sender' => 'user', 'content' => 'شكراً لكم على التوضيح.'],
                    ['sender' => 'admin', 'content' => 'العفو، نحن هنا دائماً للمساعدة.'],
                ];

            case 'مشكلة في رفع إعلان عقار':
                return [
                    ['sender' => 'user', 'content' => 'السلام عليكم، أحاول رفع إعلان لعقار ولكن تظهر لي رسالة خطأ.'],
                    ['sender' => 'admin', 'content' => 'وعليكم السلام، هل يمكنك تزويدنا بنسخة من رسالة الخطأ؟'],
                    ['sender' => 'user', 'content' => 'الرسالة تقول: "حدث خطأ غير متوقع، يرجى المحاولة لاحقاً."'],
                    ['sender' => 'admin', 'content' => 'سنتحقق من الأمر فوراً ونعود إليك قريباً.'],
                    ['sender' => 'user', 'content' => 'أقدر دعمكم، شكراً جزيلاً.'],
                    ['sender' => 'admin', 'content' => 'على الرحب والسعة.'],
                ];

            case 'طلب تعديل على بيانات العقار':
                return [
                    ['sender' => 'user', 'content' => 'مرحباً، أود تعديل وصف العقار الذي قمت بإدراجه مؤخراً.'],
                    ['sender' => 'admin', 'content' => 'هل يمكنك تحديد العقار المطلوب تعديله؟'],
                    ['sender' => 'user', 'content' => 'نعم، هو "شقة تمليك بحي النرجس".'],
                    ['sender' => 'admin', 'content' => 'تم، يمكنك الآن تعديل البيانات من لوحة التحكم.'],
                    ['sender' => 'user', 'content' => 'تم التعديل، شكراً لتعاونكم.'],
                    ['sender' => 'admin', 'content' => 'أي وقت، نحن هنا للمساعدة.'],
                ];

            case 'عطل تقني في لوحة التحكم':
                return [
                    ['sender' => 'user', 'content' => 'السلام عليكم، لوحة التحكم لا تعمل بشكل صحيح.'],
                    ['sender' => 'admin', 'content' => 'هل يمكنك وصف المشكلة بمزيد من التفصيل؟'],
                    ['sender' => 'user', 'content' => 'لا يمكنني الدخول إلى صفحة إدارة العقارات.'],
                    ['sender' => 'admin', 'content' => 'جاري التحقق حالياً من النظام، وسنوافيك بالتحديثات.'],
                    ['sender' => 'user', 'content' => 'شكراً على المتابعة السريعة.'],
                    ['sender' => 'admin', 'content' => 'لا شكر على واجب.'],
                ];

            default:
                return [
                    ['sender' => 'user', 'content' => 'مرحباً، لدي استفسار عن توزيع الأرباح الشهرية.'],
                    ['sender' => 'admin', 'content' => 'أهلاً بك، يتم توزيع الأرباح في نهاية كل شهر على حسب نسبة الاستثمار.'],
                    ['sender' => 'user', 'content' => 'هل يتم الإيداع تلقائياً في الحساب البنكي؟'],
                    ['sender' => 'admin', 'content' => 'نعم، يتم التحويل تلقائياً للحساب المسجل في حسابك.'],
                    ['sender' => 'user', 'content' => 'رائع، شكراً لكم.'],
                    ['sender' => 'admin', 'content' => 'يسعدنا خدمتك دائماً.'],
                ];
        }
    }
}
