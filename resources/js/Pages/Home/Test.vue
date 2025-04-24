<script setup></script>

<template>
    <div class="">
        <div class="p-4 border-t border-gray-100">
            <h3 class="text-sm font-semibold text-gray-500 mb-3 px-2">
                معلومات الحساب
            </h3>

            <div class="flex flex-col gap-3 px-2">
                <div class="flex items-center gap-2">
                    <i class="pi pi-calendar text-gray-400"></i>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">تاريخ التسجيل</span>
                        <span class="text-sm">{{
                            formatDate(user.created_at)
                        }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <i class="pi pi-clock text-gray-400"></i>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">آخر تحديث</span>
                        <span class="text-sm">{{
                            user.updated_at
                                ? formatDate(user.updated_at)
                                : "لم يتم التحديث"
                        }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <i class="pi pi-check-circle text-gray-400"></i>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">تحقق البريد</span>
                        <span class="text-sm">{{
                            user.email_verified_at
                                ? formatDate(user.email_verified_at)
                                : "غير متحقق"
                        }}</span>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <i class="pi pi-id-card text-gray-400"></i>
                    <div class="flex flex-col">
                        <span class="text-xs text-gray-500">معرف المستخدم</span>
                        <span class="text-sm font-mono">{{ user.id }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
