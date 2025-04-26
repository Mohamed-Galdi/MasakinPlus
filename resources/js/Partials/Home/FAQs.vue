<script setup>
import { ref, computed, onMounted, nextTick } from "vue";

const questions = ref([
    {
        question: "كيف يمكنني عرض عقاري على المنصة؟",
        answer: "يمكنك تسجيل الدخول إلى حسابك، ثم اختيار 'إضافة عقار' وإدخال التفاصيل المطلوبة مثل الموقع، السعر، والصور. بعد الموافقة عليه، سيتم عرضه للمستخدمين.",
    },
    {
        question: "هل يمكنني الاستثمار في العقارات دون امتلاكها؟",
        answer: "نعم، توفر منصتنا فرص استثمار عقاري حيث يمكنك المساهمة في تمويل العقارات والاستفادة من العوائد دون الحاجة إلى إدارتها بنفسك.",
    },
    {
        question: "ما هي طرق الدفع المتاحة عند استئجار عقار؟",
        answer: "نقبل عدة طرق للدفع، بما في ذلك التحويل البنكي، البطاقات الائتمانية، والمدفوعات الإلكترونية عبر المنصة لضمان عملية آمنة وسهلة.",
    },
    {
        question: "كيف يتم حساب العائد على الاستثمار؟",
        answer: "يتم حساب العائد بناءً على نسبة مساهمتك في العقار، وإجمالي العوائد الشهرية المحصلة من الإيجارات، مطروحا منها التكاليف التشغيلية والإدارية.",
    },
    {
        question: "هل يوجد رسوم عند نشر عقار على المنصة؟",
        answer: "نشر العقارات مجاني، ولكن هناك رسوم خدمة بسيطة عند إتمام عملية التأجير أو الاستثمار لضمان استمرارية المنصة وتحسين الخدمات.",
    },
    {
        question: "كيف يمكنني التواصل مع مالك العقار؟",
        answer: "يمكنك إرسال رسالة مباشرة إلى مالك العقار من خلال صفحة العقار على المنصة، كما يمكنك استخدام ميزة الدردشة الفورية إن كانت متاحة.",
    },
    {
        question: "هل يمكنني إلغاء عقد الإيجار قبل انتهائه؟",
        answer: "ذلك يعتمد على شروط العقد. يُفضل مراجعة العقد أو التواصل مع المالك لمعرفة الخيارات المتاحة لك.",
    },
]);

// Using a Map for better performance when storing refs
const answerHeights = ref(new Map());

// Toggle question state
const toggleQuestion = (index) => {
    questions.value[index].isOpen = !questions.value[index].isOpen;
};

// Set up answer refs using a function
const setAnswerRef = (el, index) => {
    if (el) {
        nextTick(() => {
            answerHeights.value.set(index, el.scrollHeight);
        });
    }
};

// Get content height with a fallback
const getContentHeight = (index) => {
    return answerHeights.value.get(index) || 0;
};
</script>

<template>
    <section id="faq" class="bg-gray-50 py-16" dir="rtl">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Title -->
            <h2
                class="text-center text-3xl sm:text-4xl md:text-5xl font-Bein text-teal-800 mb-12 animate-fade-in"
            >
                تريد معرفة المزيد<span class="text-yellow-500">؟</span>
            </h2>

            <!-- FAQ Items -->
            <div class="space-y-4">
                <div
                    v-for="(faq, index) in questions"
                    :key="`faq-${index}`"
                    class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300"
                >
                    <!-- Question -->
                    <button
                        @click="toggleQuestion(index)"
                        class="w-full flex justify-between items-center p-5 text-right focus:outline-none transition-colors duration-300 hover:bg-gray-100"
                        :class="{ 'bg-gray-100': faq.isOpen }"
                    >
                        <span
                            class="text-lg md:text-xl font-Bein text-teal-800"
                        >
                            {{ faq.question }}
                        </span>
                        <span
                            class="text-yellow-500 transform transition-transform duration-300"
                            :class="{ 'rotate-180': faq.isOpen }"
                            aria-hidden="true"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                class="w-6 h-6"
                                width="24"
                                height="24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                />
                            </svg>
                        </span>
                    </button>

                    <!-- Answer -->
                    <div
                        class="answer-container"
                        :style="{
                            height: faq.isOpen
                                ? `${getContentHeight(index)}px`
                                : '0',
                            opacity: faq.isOpen ? 1 : 0,
                        }"
                    >
                        <div
                            class="answer-content"
                            :ref="(el) => setAnswerRef(el, index)"
                        >
                            <p
                                class="text-gray-600 font-Bein text-sm md:text-base leading-relaxed pt-4"
                            >
                                {{ faq.answer }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
@keyframes fadeIn {
    0% {
        opacity: 0;
        transform: translateY(20px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

.answer-container {
    overflow: hidden;
    transition: height 0.3s ease-in-out, opacity 0.3s ease-in-out;
}

.answer-content {
    padding: 0 20px 20px 20px;
}
</style>
