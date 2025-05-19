<?php

namespace App\Enums;

enum PropertyStatus: string
{
    case Draft = 'draft'; // owner added the property in the platform
    case InvestmentRequested = 'investment_requested'; // owner requested to open investments on the property
    case InvestmentOfferSent = 'investment_offer_sent'; // admin sent an investment offer to the owner
    case OpenForInvestment = 'open_for_investment'; // owner accepted the investment offer, and the property is listed for investment
    case FundedUnderMaintenance = 'funded_under_maintenance'; // the property is funded and now it's under maintenance
    case RentRequested = 'rent_requested'; // owner requested to list the property for rent
    case RentRejected = 'rent_rejected'; // Admin rejected rent request
    case ListedForRent = 'listed_for_rent'; // the property is listed for rent
    case Rented = 'rented'; // Property has an active tenant
    case LeaseEnded = 'lease_ended'; // Lease ended, pending re-listing
    // case Unlisted = 'unlisted'; // Property is not listed for investment or rent

    public function label(): string
    {
        return match ($this) {
            self::Draft => ' غير جاهز',
            self::InvestmentRequested => 'طلب فتح استثمار',
            self::InvestmentOfferSent => 'عرض استثمار بانتظار الموافقة',
            self::OpenForInvestment => 'متاح للاستثمار',
            self::FundedUnderMaintenance => 'ممول ويجري تجهيزه',
            self::RentRequested => 'طلب إدراج للإيجار',
            self::RentRejected => 'تم رفض الإدراج للإيجار',
            self::ListedForRent => ' متاح للإيجار',
            self::Rented => ' مؤجر حالياً',
            self::LeaseEnded => 'انتهى عقد الإيجار',
            // self::Unlisted => ' عقار ملغي حالياً',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Draft => 'هذا هو الوضع الابتدائي للعقار بعد أن يقوم المالك بإضافته على المنصة. في هذه المرحلة، العقار غير مرئي لأي طرف آخر (مستثمرين أو مستأجرين)، ولا يمكن الاستثمار أو الإيجار فيه. يمكن للمالك تعديل البيانات أو إكمالها، ثم تقديم طلب لفتح باب الاستثمار. في حال رفض طلب الاستثمار أو رفض المالك عرض الاستثمار المرسل من الإدارة، يتم إرجاع العقار إلى هذه الحالة تلقائياً.',

            self::InvestmentRequested => 'المالك طلب من الإدارة فتح فرصة استثمار في هذا العقار. هذا يعني أن العقار سيخضع الآن لمراجعة من قبل فريق المنصة لتقييم مدى قابليته للتمويل من قبل المستثمرين. في هذه المرحلة، لا يمكن القيام بأي إجراء إضافي من قبل المالك. الإدارة إما أن ترسل عرض استثمار أو ترفض الطلب (وفي حال الرفض يعود العقار إلى "غير منشور").',

            self::InvestmentOfferSent => 'الإدارة قامت بمراجعة العقار وقررت إرسال عرض استثمار إلى المالك. يتضمن العرض تفاصيل مثل قيمة التمويل، النسبة المقترحة للمستثمرين، وأي شروط إضافية. على المالك الآن اتخاذ قرار: إما قبول العرض ليتم فتح باب الاستثمار، أو رفضه، وفي حال الرفض يعود العقار إلى وضع "غير منشور".',

            self::OpenForInvestment => 'تمت موافقة المالك على عرض الاستثمار، وأصبح العقار متاحاً الآن للمستثمرين للمساهمة في تمويله. يمكن لأي مستثمر مهتم الاطلاع على التفاصيل والمشاركة حتى يتم تغطية المبلغ المطلوب بالكامل. لا يمكن التعديل على العقار أو تغيير حالته إلا بعد اكتمال التمويل. في حال اكتمال التمويل، ينتقل العقار تلقائياً إلى مرحلة التجهيز أو الصيانة.',

            self::FundedUnderMaintenance => 'تم تمويل العقار بالكامل من قبل المستثمرين. في هذه المرحلة، يتم استخدام الأموال لتهيئة العقار (صيانة، تجهيز، تحسينات) ليكون جاهزاً للإيجار. لا يمكن عرض العقار للمستأجرين قبل الانتهاء من أعمال التجهيز. يمكن أن تستغرق هذه المرحلة وقتاً حسب حالة العقار ومتطلبات التأهيل.',

            self::RentRequested => 'قام المالك بطلب إدراج العقار للإيجار بعد الانتهاء من التجهيز. الطلب قيد المراجعة من قبل الإدارة للتحقق من جاهزية العقار وتوافقه مع معايير الإيجار على المنصة. خلال هذه المرحلة، العقار غير مرئي للمستأجرين ولا يمكن تقديم طلبات عليه. الإدارة قد توافق أو ترفض الطلب بناءً على التقييم.',

            self::RentRejected => 'قامت الإدارة برفض طلب إدراج العقار للإيجار. قد يكون السبب تقني (نقص في البيانات، عدم جاهزية العقار، صور غير واضحة، إلخ) أو إداري. يجب على المالك مراجعة سبب الرفض وإجراء التعديلات اللازمة ثم إعادة تقديم الطلب مرة أخرى. العقار يبقى غير متاح للمستأجرين خلال هذه المرحلة.',

            self::ListedForRent => 'العقار معروض حالياً على المنصة للمستأجرين. يمكن للمستخدمين تصفح العقار، الاطلاع على التفاصيل، وتقديم طلبات الإيجار. في حال تم قبول أحد الطلبات من قبل الإدارة أو المالك، يتم الانتقال إلى مرحلة "مؤجّر حالياً". المالك لا يمكنه التعديل على العقار في هذه المرحلة دون إيقاف الإدراج.',

            self::Rented => 'العقار مؤجّر حالياً ويوجد عقد إيجار نشط مع مستأجر. خلال فترة الإيجار، لا يمكن إدراج العقار مرة أخرى أو فتح باب الاستثمار. يتم توزيع الإيرادات الشهرية بين المالك والمستثمرين بناءً على نسبة كل طرف، وتخصم المنصة رسوم الخدمة بشكل تلقائي. العقار يبقى في هذه الحالة حتى انتهاء مدة العقد.',

            self::LeaseEnded => 'انتهت مدة عقد الإيجار ولم يتم تجديده أو توقيع عقد جديد بعد. العقار حالياً غير متاح للمستأجرين، ويمكن للمالك طلب إعادة إدراجه للإيجار أو تعديله. من الممكن أيضاً فتح دورة صيانة جديدة أو تحديث بيانات العقار قبل عرضه مرة أخرى.',

            // self::Unlisted => 'العقار غير مفعل حالياً، ولا هو معروض للاستثمار ولا للإيجار. قد يتم إدخاله في هذه الحالة بشكل يدوي من قبل الإدارة أو بعد انتهاء دورة الإيجار دون اتخاذ إجراء. يمكن استخدام هذه الحالة لتجميد العقار مؤقتاً دون حذفه، سواء من قبل المالك أو النظام.',
        };
    }

    public static function labels(): array
    {
        return array_map(fn ($status) => $status->label(), self::cases());
    }

    public static function labelFor(string $value): string
    {
        return self::tryFrom($value)?->label() ?? 'غير معروف';
    }

    public static function descriptionFor(string $value): string
    {
        return self::tryFrom($value)?->description() ?? 'وصف غير متاح';
    }

    public static function options(): array
    {
        return array_map(fn ($type) => [
            'label' => $type->label(),
            'value' => $type->value,
            'description' => $type->description(),
        ], self::cases());
    }
}
