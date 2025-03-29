<script setup>
import { Link } from "@inertiajs/vue3";

const cards = [
    {
        title: "لملاك العقارات",
        listItems: [
            "وصول فوري للمستأجرين والمستثمرين الجادين",
            "اعرض عقارك بطريقة احترافية وجذابة",
            "تحكم كامل في التأجير والإدارة بكل بساطة",
            "ضاعف أرباحك بأقل جهد وبدون وسيط",
        ],
        icon: "fa-solid fa-home",
    },
    {
        title: "للمستثمرين",
        listItems: [
            "حقق أرباحًا مجزية باستثمارات مدروسة",
            "استثمر دون الحاجة لإدارة العقارات بنفسك",
            "استفد من فرص مضمونة بعوائد ثابتة",
            "انضم إلى نخبة المستثمرين في السوق العقاري",
        ],
        icon: "fa-solid fa-coins",
    },
    {
        title: "للباحث عن سكن",
        listItems: [
            "ابحث بسهولة عن شقة تناسب احتياجاتك",
            "احصل على أفضل الأسعار والعروض الحصرية",
            "استمتع بتجربة بحث سلسة وسريعة",
            "تواصل مباشرة مع الملاك والمستثمرين",
        ],
        icon: "fa-solid fa-user",
    },
];
</script>

<template>
    <div class="h-[40rem] py-16">
        <div
            class="grid lg:grid-cols-3 md:grid-cols-2 grid-cols-1 justify-center w-full max-w-7xl mx-auto md:px-4 px-8"
        >
            <Link
                href="#"
                v-for="card in cards"
                :key="card.id"
                class="group relative cursor-pointer h-[30rem]"
            >
                <!-- Background Layer -->
                <div class="absolute w-full h-full z-0 bg-teal-950"></div>

                <!-- Content Layer -->
                <div
                    class="relative py-6 px-10 bg-teal-800 z-50 border-2 border-slate-800 hover:border-slate-800 w-full h-full hover:translate-x-4 hover:-translate-y-4 transition-all duration-500 ease-in-out space-y-6"
                >
                    <div class="flex items-center justify-center">
                        <Icon :icon="card.icon" class="text-white w-16 h-16" />
                    </div>
                    <p class="text-slate-100 font-secondary font-bold text-xl">
                        {{ card.title }}
                    </p>
                    <ul class="list-disc ml-">
                        <li
                            v-for="item in card.listItems"
                            :key="item"
                            class="text-slate-300 font-BeinNormal text-lg font-light"
                        >
                            {{ item }}
                        </li>
                    </ul>
                </div>
            </Link>
        </div>
    </div>
</template>

<style scoped></style>
