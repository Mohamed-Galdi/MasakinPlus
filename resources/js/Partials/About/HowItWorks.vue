<script setup lang="ts">
import { ref, onMounted } from "vue";
import { Building2, Users, Wallet } from "lucide-vue-next";

const connectionsRef = ref<HTMLElement | null>(null);

onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add("animate-in");
                }
            });
        },
        { threshold: 0.1 }
    );

    const elements = document.querySelectorAll(".animate-on-scroll");
    elements.forEach((el) => observer.observe(el));
});
</script>

<template>
    <section
        class="py-20 bg-gradient-to-b from-teal-950 to-gray-900 text-white overflow-hidden"
    >
        <div class="container mx-auto px-4">
            <div
                ref="connectionsRef"
                class="relative max-w-[1200px] h-[800px] mx-auto font-BeinNormal"
            >
                <!-- Center Platform Logo -->
                <div
                    class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 animate-on-scroll z-20"
                >
                    <img
                        src="/assets/logos/logo_white.png"
                        alt="MasakinPlus"
                        class="w-44 h-44 object-contain bg-teal-700 p-8 shadow-green-500 shadow-xl rounded-[3rem] border-2 border-white"
                    />
                </div>

                <!-- Connection Lines -->
                <svg class="absolute inset-0 w-full h-full" style="z-index: 10">
                    <line
                        x1="50%"
                        y1="50%"
                        x2="25%"
                        y2="30%"
                        class="connection-line"
                    />
                    <line
                        x1="50%"
                        y1="50%"
                        x2="75%"
                        y2="30%"
                        class="connection-line"
                    />
                    <line
                        x1="50%"
                        y1="50%"
                        x2="50%"
                        y2="75%"
                        class="connection-line"
                    />
                </svg>

                <!-- Property Owners Card -->
                <div
                    class="absolute top-[15%] left-[24%] -translate-x-1/2 animate-on-scroll z-20"
                >
                    <div
                        class="bg-slate-200 rounded-2xl p-2 w-80 text-center hover:scale-105 transition-transform shadow-2xl border border-teal-900/30"
                    >
                        <Icon
                            icon="fa-solid fa-house"
                            class="w-12 h-12 text-teal-800"
                        />
                        <h3 class="text-2xl font-bold text-teal-800 mb-3">
                            أصحاب العقارات
                        </h3>
                        <p class="text-gray-800 ">
                            زد من عائدات عقارك بسهولة من خلال منصة موثوقة تربطك
                            بالمستأجرين والمستثمرين.
                        </p>
                    </div>
                </div>

                <!-- Investors Card -->
                <div
                    class="absolute top-[15%] right-[24%] translate-x-1/2 animate-on-scroll z-20"
                >
                    <div
                        class="bg-slate-200 rounded-2xl p-2 w-80 text-center hover:scale-105 transition-transform shadow-2xl border border-teal-900/30"
                    >
                        <Icon
                            icon="fa-solid fa-coins"
                            class="w-12 h-12 text-teal-800"
                        />
                        <h3 class="text-2xl font-bold text-teal-800 mb-3">
                            المستثمرون
                        </h3>
                        <p class="text-gray-800 ">
                            استثمر في العقارات بسهولة واستمتع بعوائد ثابتة دون
                            الحاجة إلى إدارتها.
                        </p>
                    </div>
                </div>

                <!-- Tenants Card -->
                <div
                    class="absolute bottom-[5rem] left-1/2 -translate-x-1/2 animate-on-scroll z-20"
                >
                    <div
                        class="bg-slate-200 rounded-2xl p-2 w-80 text-center hover:scale-105 transition-transform shadow-2xl "
                    >
                        <Icon
                            icon="fa-solid fa-user"
                            class="w-12 h-12 text-teal-800"
                        />
                        <h3 class="text-2xl font-bold text-teal-800 mb-3">
                            المستأجرون
                        </h3>
                        <p class="text-gray-800 ">
                            اكتشف عقارات مميزة بأسعار تنافسية واستأجر منزلك
                            الجديد بكل سهولة وأمان.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.connection-line {
    stroke: white;
    stroke-width: 3;
    stroke-dasharray: 8;
    opacity: 0.6;
    animation: dash 20s linear infinite;
}

@keyframes dash {
    to {
        stroke-dashoffset: -1000;
    }
}
</style>
