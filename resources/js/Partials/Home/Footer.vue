<script setup>
import { Link } from "@inertiajs/vue3";

const currentYear = new Date().getFullYear();
</script>

<template>
    <footer class="bg-gray-50 w-full px-4 sm:px-6 lg:px-8 dir-rtl">
        <div
            class="bg-gradient-to-b from-teal-700 to-teal-950 text-slate-100 w-full rounded-t-[3rem] sm:rounded-t-[5rem] py-8 sm:py-12"
        >
            <div
                class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-12 items-start"
            >
                <!-- Logo Section -->
                <Link :href="route('home')" class="flex flex-col items-center gap-2">
                    <img
                        src="/assets/logos/logo_white.png"
                        alt="MasakinPlus"
                        class="w-16 h-16 sm:w-20 sm:h-20"
                    />
                    <p class="font-Bein font-semibold text-lg sm:text-xl">
                        مساكن <span class="text-yellow-500">بلس</span>
                    </p>
                </Link>

                <!-- Accounts Section -->
                <div class="flex flex-col items-center">
                    <p
                        class="text-yellow-500 font-BeinNormal text-base sm:text-lg mb-2"
                    >
                        الحسابات
                    </p>
                    <div class="flex flex-col items-start gap-1">
                        <Link
                            :href="route('register')"
                            class="font-Alarabiya text-base sm:text-lg hover:text-yellow-400 transition-colors"
                            >إنشاء حساب</Link
                        >
                        <Link
                            :href="route('login')"
                            class="font-Alarabiya text-base sm:text-lg hover:text-yellow-400 transition-colors"
                            >تسجيل الدخول</Link
                        >
                    </div>
                </div>

                <!-- About Section -->
                <div class="flex flex-col items-center">
                    <p
                        class="text-yellow-500 font-BeinNormal text-base sm:text-lg mb-2"
                    >
                        حول مساكن بلس
                    </p>
                    <div class="flex flex-col items-start gap-1">
                        <Link
                        :href="route('privacy-policy')"
                        class="font-Alarabiya text-base sm:text-lg hover:text-yellow-400 transition-colors"
                        >سياسة الخصوصية</Link
                        >
                        <a
                            :href="route('about-us')"
                            class="font-Alarabiya text-base sm:text-lg hover:text-yellow-400 transition-colors"
                            >من نحن</a
                        >
                       
                    </div>
                </div>

                <!-- Social Icons Section -->
                <div class="flex flex-col items-center">
                    <p
                        class="text-yellow-500 font-BeinNormal text-base sm:text-lg mb-5"
                    >
                        صفحاتنا الإجتماعية
                    </p>
                    <ul class="flex items-center gap-3 sm:gap-4 text-white ">
                        <li>
                           <a href="https://www.facebook.com/masakinplus" target="_blank">
                                <i style="font-size: 1.3rem;"
                                    class="pi pi-facebook hover:text-yellow-400 cursor-pointer transition-colors"
                                />
                           </a>
                        </li>
                        <li>
                           <a href="https://www.instagram.com/masakinplus/" target="_blank">
                                <i  style="font-size: 1.3rem;"
                                    class="pi pi-instagram hover:text-yellow-400 cursor-pointer transition-colors"
                                />
                           </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/masakinplus" target="_blank">
                                <i  style="font-size: 1.3rem;"
                                    class="pi pi-twitter hover:text-yellow-400 cursor-pointer transition-colors"
                                />
                            </a>
                        </li>
                        <li>
                            <a href="https://www.tiktok.com/@masakinplus" target="_blank">
                                <i  style="font-size: 1.3rem;"
                                    class="pi pi-tiktok hover:text-yellow-400 cursor-pointer transition-colors"
                                />
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div
                class="text-center text-white font-BeinNormal text-sm sm:text-base mt-8 sm:mt-12"
            >
                جميع الحقوق محفوظة لشركة مساكن بلس © {{ currentYear }}
            </div>
        </div>
    </footer>
</template>

<style scoped>
.dir-rtl {
    direction: rtl;
}
</style>
