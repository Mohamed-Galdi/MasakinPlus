<script setup>
const props = defineProps({
    contact: Object,
});

const content = JSON.parse(JSON.stringify(props.contact.content.cards));
</script>

<template>
    <section id="contact" class="py-24 bg-gray-50 relative overflow-hidden font-Bein">
        <!-- Background elements -->
        <div class="absolute inset-0">
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-teal-600/10 to-teal-800/10 rounded-full blur-3xl"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-teal-600/10 to-teal-800/10 rounded-full blur-3xl"
            ></div>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <div
                class="max-w-7xl mx-auto bg-white rounded-3xl shadow-2xl overflow-hidden"
            >
                <div class="flex flex-col md:flex-row">
                    <div
                        class="md:w-2/5 bg-gradient-to-br from-teal-700 to-teal-900 text-white p-12 relative overflow-hidden"
                    >
                        <!-- Decorative elements -->
                        <div
                            class="absolute top-0 left-0 w-full h-full opacity-10"
                        >
                            <div
                                class="absolute top-10 left-10 w-40 h-40 border-2 border-white/30 rounded-full"
                            ></div>
                            <div
                                class="absolute bottom-10 right-10 w-60 h-60 border-2 border-white/30 rounded-full"
                            ></div>
                            <div
                                class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-80 h-80 border-2 border-white/30 rounded-full"
                            ></div>
                        </div>

                        <div class="relative z-10">
                            <h2 class="text-3xl font-bold mb-8">تواصل معنا</h2>
                            <p class="mb-12 text-teal-100 text-lg font-BeinNormal">
                                نحن هنا للإجابة على استفساراتكم ومساعدتكم في كل
                                ما يتعلق بخدماتنا العقارية
                            </p>

                            <div class="space-y-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white/10 grid place-items-center"
                                    >
                                        <i class="pi pi-map-marker  "  style="font-size: 1.3rem;"/>
                                    </div>
                                    <div>
                                        <p class="font-bold">
                                           {{ content.address.title }}
                                        </p>
                                        <p class="text-teal-200 font-BeinNormal">
                                            {{ content.address.description }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white/10 grid place-items-center"
                                    >
                                        <i class="pi pi-whatsapp  "  style="font-size: 1.3rem;"/>
                                    </div>
                                    <div>
                                        <p class="font-bold text-end" dir="ltr">
                                            {{ content.phone.title }}
                                        </p>
                                        <p class="text-teal-200 font-BeinNormal">
                                            {{ content.phone.description }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-full bg-white/10 grid place-items-center"
                                    >
                                        <i class="pi pi-envelope  "  style="font-size: 1.3rem;"/>
                                    </div>
                                    <div>
                                        <p class="font-bold">
                                            {{ content.email.title }}
                                        </p>
                                        <p class="text-teal-200 font-BeinNormal">
                                            {{ content.email.description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="md:w-3/5 p-12">
                        <form class="space-y-6">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        for="name"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >الاسم</label
                                    >
                                    <input
                                        type="text"
                                        id="name"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all"
                                    />
                                </div>

                                <div>
                                    <label
                                        for="email"
                                        class="block text-sm font-medium text-gray-700 mb-1"
                                        >البريد الإلكتروني</label
                                    >
                                    <input
                                        type="email"
                                        id="email"
                                        class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all"
                                    />
                                </div>
                            </div>

                            <div>
                                <label
                                    for="subject"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >الموضوع</label
                                >
                                <input
                                    type="text"
                                    id="subject"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all"
                                />
                            </div>

                            <div>
                                <label
                                    for="message"
                                    class="block text-sm font-medium text-gray-700 mb-1"
                                    >الرسالة</label
                                >
                                <textarea
                                    id="message"
                                    rows="4"
                                    class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all"
                                ></textarea>
                            </div>

                            <button
                                type="submit"
                                class="w-full bg-gradient-to-r from-teal-600 to-teal-800 text-white py-4 rounded-xl font-bold hover:from-teal-700 hover:to-teal-900 transition-colors shadow-lg flex items-center justify-center gap-2"
                            >
                                <span>إرسال الرسالة</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
