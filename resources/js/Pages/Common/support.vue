<script setup>
import { ref, computed, watch, nextTick } from "vue";
import DynamicLayout from "@/Layouts/DynamicLayout.vue";
import { Link, router, usePage } from "@inertiajs/vue3";
import { debounce } from "lodash";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Tag from "primevue/tag";
import Drawer from "primevue/drawer";
import Textarea from "primevue/textarea";
import Badge from "primevue/badge";
import { useTextHelpers } from "@/plugins/textHelpers";
import FloatLabel from "primevue/floatlabel";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import Divider from "primevue/divider";

defineOptions({
    layout: DynamicLayout,
});

const props = defineProps({
    tickets: {
        type: Object,
        default: () => [],
    },
    search: {
        type: String,
        required: false,
        default: "",
    },
});

const toast = useToast();

const tickets = ref(props.tickets);

const isMobile = computed(() => window.innerWidth <= 768);
// ########################################################################################## search
const textHelpers = useTextHelpers();
const search = ref(props.search);

watch(
    search,
    debounce((value) => {
        router.get(
            route("support.index"),
            {
                search: value,
            },
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    }, 500)
);

// Reset filters
const resetFilters = () => {
    search.value = "";
};

// ########################################################################################## utils
// Format date
const formatDate = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("ar-SA", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "numeric",
        minute: "numeric",
    }).format(date);
};

// Get last message content (for description in table)
const getLastMessageContent = (ticket) => {
    return ticket.messages && ticket.messages.length > 0
        ? ticket.messages[ticket.messages.length - 1].content
        : "";
};

// ########################################################################################## Create ticket
const showNewTicketDrawer = ref(false);
const ticketForm = useForm({
    subject: "",
    content: "",
});

const sendTicket = () => {
    ticketForm.post(route("support.create"), {
        preserveState: false,
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "نجاح",
                detail: "تم إنشاء تذكرة جديدة بنجاح",
                life: 3000,
            });
            showNewTicketDrawer.value = false;
            ticketForm.reset();
        },
        onError: (errors) => {
            const errorMessage = Object.values(errors)[0];
            toast.add({
                severity: "error",
                summary: "خطأ",
                detail: errorMessage,
                life: 3000,
            });
        },
    });
};

// ########################################################################################## Reply ticket
const selectedTicket = ref(null);
const showReplyDrawer = ref(false);
const messages = ref([]);
const messagesContainer = ref(null);
const scrollAnchor = ref(null);

const replyForm = useForm({
    content: "",
});

const openTicket = (ticket) => {
    selectedTicket.value = ticket;
    messages.value = ticket.messages;
    showReplyDrawer.value = true; // Open drawer

    // resetUnreadCount
    router.post(
        route("support.resetUserUnreadCount", { ticketId: ticket.id }),
        {},
        {
            preserveState: true, // Prevent page reload
            onSuccess: () => {
                selectedTicket.value.user_unread_count = 0; // Update UI
            },
        }
    );
};

watch(
    () => messages,
    () => {
        nextTick(() => {
            scrollAnchor.value.scrollIntoView({ behavior: "smooth" });
        });
    },
    { deep: true }
);

// Send reply
const sendReply = () => {
    replyForm.post(
        route("support.reply", { ticketId: selectedTicket.value.id }),
        {
            onSuccess: () => {
                toast.add({
                    severity: "success",
                    summary: "نجاح",
                    detail: "تم إرسال الرد بنجاح",
                    life: 3000,
                });

                // Manually add the reply
                messages.value.push({
                    id: Date.now(), // Temporary ID until refresh
                    ticket_id: selectedTicket.value.id,
                    sender: "user",
                    content: replyForm.content,
                    created_at: new Date().toISOString(),
                    updated_at: new Date().toISOString(),
                });
                replyForm.reset(); // Clear reply form
            },
            onError: (errors) => {
                const errorMessage = Object.values(errors)[0];
                toast.add({
                    severity: "error",
                    summary: "خطأ",
                    detail: errorMessage,
                    life: 3000,
                });
            },
        }
    );
};
</script>

<template>
    <div>
        <Toast position="top-center" />
        <!-- New Ticket Drawer -->
        <Drawer
            v-model:visible="showNewTicketDrawer"
            :style="{ width: isMobile ? '100%' : '50vw' }"
        >
            <template #header>
                <div class="flex justify-center items-center gap-2">
                    <i
                        class="pi pi-ticket text-teal-800"
                        style="font-size: 1.5rem"
                    ></i>
                    <h1 class="text-2xl font-bold m-0 text-teal-800 font-Bein">
                        إنشاء تذكرة جديدة
                    </h1>
                </div>
            </template>
            <div class="p-8 h-full">
                <form
                    class="flex flex-col gap-6 h-full"
                    @submit.prevent="sendTicket"
                >
                    <!-- Subject Field -->
                    <div>
                        <FloatLabel variant="on" class="w-full">
                            <InputText
                                id="subject"
                                v-model="ticketForm.subject"
                                class="w-full"
                            />
                            <label for="subject" class="text-gray-600"
                                >الموضوع</label
                            >
                        </FloatLabel>
                    </div>

                    <!-- Message Content -->
                    <div class="form-group flex-1">
                        <FloatLabel variant="on" class="w-full h-full">
                            <Textarea
                                class="w-full h-full"
                                id="message"
                                v-model="ticketForm.content"
                                rows="8"
                                style="resize: none"
                            />
                            <label for="message" class="text-gray-600"
                                >محتوى التذكرة</label
                            >
                        </FloatLabel>
                    </div>

                    <!-- Submit Button -->
                    <Button
                        label="إرسال التذكرة"
                        icon="pi pi-send"
                        :loading="ticketForm.processing"
                        type="submit"
                    />
                </form>
            </div>
        </Drawer>

        <!-- Reply Drawer -->
        <Drawer
            v-model:visible="showReplyDrawer"
            :style="{ width: isMobile ? '100%' : '50vw' }"
        >
            <template #header>
                <div class="flex justify-center items-center gap-2">
                    <i
                        class="pi pi-comments text-teal-800"
                        style="font-size: 1.5rem"
                    ></i>
                    <h1 class="text-2xl font-bold m-0 text-teal-800 font-Bein">
                        {{ selectedTicket?.subject }}
                    </h1>
                </div>
            </template>
            <div class="p-8 h-full flex flex-col">
                <!-- Messages Area -->
                <div
                    class="flex-1 bg-white rounded-xl shadow-sm p-6 border border-gray-100 overflow-y-auto"
                    ref="messagesContainer"
                >
                    <div class="flex flex-col gap-4">
                        <div
                            v-for="message in messages"
                            :key="message.id"
                            :class="[
                                'p-4 rounded-lg max-w-[75%] animate-fade-in',
                                message.sender === 'admin'
                                    ? 'bg-gray-100 text-gray-900 self-end'
                                    :'bg-teal-50 text-teal-900 self-start' ,
                            ]"
                        >
                            <p class="text-sm font-medium mb-1">
                                {{
                                    message.sender === "admin"
                                        ? "الإدارة"
                                        : "أنت"
                                }}
                            </p>
                            <p class="text-sm mb-2">
                                {{ message.content }}
                            </p>
                            <span class="text-xs text-gray-500">
                                {{
                                    new Date(message.created_at).toLocaleString(
                                        "ar-SA",
                                        {
                                            dateStyle: "short",
                                            timeStyle: "short",
                                        }
                                    )
                                }}
                            </span>
                        </div>
                        <!-- Dummy div to scroll to -->
                        <div ref="scrollAnchor"></div>
                    </div>
                </div>

                <!-- Reply Form -->
                <form
                    action=""
                    class="mt-6 flex flex-col gap-4"
                    @submit.prevent="sendReply"
                >
                    <div>
                        <FloatLabel variant="on" class="w-full">
                            <Textarea
                                class="w-full"
                                id="reply"
                                v-model="replyForm.content"
                                rows="4"
                                style="resize: none"
                            />
                            <label for="reply" class="text-gray-600"
                                >اكتب ردك</label
                            >
                        </FloatLabel>
                    </div>

                    <!-- Submit Button -->
                    <Button
                        label="إرسال الرد"
                        icon="pi pi-send"
                        :loading="replyForm.processing"
                        type="submit"
                    />
                </form>
            </div>
        </Drawer>

        <!-- Main Content -->
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Left Sidebar -->
            <div class="md:w-1/4 w-full">
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <div class="flex items-center gap-3 mb-6">
                        <i
                            class="pi pi-comments text-teal-700"
                            style="font-size: 1.5rem"
                        ></i>
                        <div>
                            <h2 class="text-xl font-semibold m-0 text-teal-800">
                                مركز الدعم
                            </h2>
                            <p class="text-gray-500 text-sm m-0">
                                نحن هنا لمساعدتك
                            </p>
                        </div>
                    </div>

                    <Button
                        label="تذكرة جديدة"
                        icon="pi pi-plus"
                        class="w-full bg-teal-700 hover:bg-teal-800 mb-6"
                        @click="showNewTicketDrawer = true"
                    />

                    <div
                        class="flex justify-between items-center border-gray-100"
                    >
                        <span class="text-gray-600">إجمالي التذاكر</span>
                        <span class="font-semibold">{{
                            props.tickets.total
                        }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold mb-4 text-teal-800">
                        روابط مفيدة
                    </h3>
                    <div class="flex flex-col gap-3">
                        <Link
                            :href="route('home') + '#faq'"
                            class="flex items-center gap-2 text-gray-600 hover:text-teal-700"
                        >
                            <i class="pi pi-question-circle"></i>
                            <span>الأسئلة الشائعة</span>
                        </Link>
                        <Link
                            :href="route('about-us') + '#contact'"
                            class="flex items-center gap-2 text-gray-600 hover:text-teal-700"
                        >
                            <i class="pi pi-phone"></i>
                            <span>اتصل بنا</span>
                        </Link>
                        <Link
                            :href="route('privacy-policy')"
                            class="flex items-center gap-2 text-gray-600 hover:text-teal-700"
                        >
                            <i class="pi pi-book"></i>
                            <span>سياسة الخصوصية </span>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Right Content Area -->
            <div class="md:w-3/4 w-full">
                <!-- Filters Section -->
                <div
                    class="filter-section bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6"
                >
                    <div class="flex flex-wrap items-center gap-4">
                        <!-- Search -->
                        <div class="search-container flex-1 min-w-[250px]">
                            <span class="p-input-icon-right w-full">
                                <InputText
                                    v-model="search"
                                    class="w-full rounded-lg border-gray-200"
                                    placeholder="البحث في التذاكر..."
                                />
                            </span>
                        </div>
                        <!-- Reset Button -->
                        <div>
                            <Button
                                icon="pi pi-filter-slash"
                                label="إعادة ضبط"
                                outlined
                                severity="secondary"
                                @click="resetFilters"
                            />
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div
                    v-if="props.tickets.data.length === 0"
                    class="p-8 text-center bg-white rounded-xl"
                >
                    <div
                        class="empty-state-icon bg-gray-50 inline-flex items-center justify-center w-20 h-20 rounded-full mb-4"
                    >
                        <i
                            class="pi pi-inbox text-gray-300"
                            style="font-size: 2.5rem"
                        ></i>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-700 mb-2">
                        لا توجد تذاكر
                    </h2>
                    <p class="text-gray-500 max-w-md mx-auto mb-6">
                        لم تقم بإنشاء أي تذاكر دعم فني بعد. يمكنك إنشاء تذكرة
                        جديدة للحصول على المساعدة.
                    </p>
                    <Button
                        icon="pi pi-plus"
                        label="إنشاء تذكرة جديدة"
                        outlined
                        class="border-teal-700 text-teal-700 hover:bg-teal-50"
                        @click="showNewTicketDrawer = true"
                    />
                </div>

                <!-- Tickets Table -->
                <div
                    v-else
                    class="rounded-xl overflow-hidden flex flex-col justify-between min-h-[75vh]"
                >
                    <!-- Tickets Table -->
                    <DataTable
                        :value="props.tickets.data"
                        stripedRows
                        showGridlines
                        tableStyle="min-width: 50rem"
                        class="border-none"
                        @row-click="(event) => openTicket(event.data)"
                        :rowHover="true"
                        :rowClass="() => 'cursor-pointer'"
                    >
                        <Column field="id" header="#" class="w-[60px]">
                            <template #body="slotProps">
                                <span class="font-mono"
                                    >#{{ slotProps.data.id }}</span
                                >
                            </template>
                        </Column>

                        <Column field="subject" header="الموضوع">
                            <template #body="slotProps">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1">
                                        <div
                                            class="font-medium"
                                            v-html="
                                                textHelpers.highlightText(
                                                    slotProps.data.subject,
                                                    search
                                                )
                                            "
                                        ></div>
                                        <div
                                            class="text-sm text-gray-500 truncate max-w-md"
                                        >
                                            {{
                                                getLastMessageContent(
                                                    slotProps.data
                                                )
                                            }}
                                        </div>
                                    </div>
                                    <div
                                        v-if="
                                            slotProps.data.user_unread_count > 0
                                        "
                                        class="flex-shrink-0"
                                    >
                                        <Badge
                                            :value="
                                                slotProps.data.user_unread_count
                                            "
                                            severity="danger"
                                        ></Badge>
                                    </div>
                                </div>
                            </template>
                        </Column>

                        <Column
                            field="created_at"
                            header="تاريخ الإنشاء"
                            class="w-[180px]"
                        >
                            <template #body="slotProps">
                                <div class="text-sm">
                                    <i class="pi pi-calendar ml-1"></i>
                                    {{ formatDate(slotProps.data.created_at) }}
                                </div>
                            </template>
                        </Column>

                        <Column
                            field="last_message_at"
                            header="آخر رسالة"
                            class="w-[180px]"
                        >
                            <template #body="slotProps">
                                <div class="text-sm">
                                    <i class="pi pi-clock ml-1"></i>
                                    {{
                                        formatDate(
                                            slotProps.data.last_message_at
                                        )
                                    }}
                                </div>
                            </template>
                        </Column>
                    </DataTable>

                    <!-- Pagination -->
                    <div
                        v-if="props.tickets.data.length > 0"
                        dir="ltr"
                        class="my-8 flex md:flex-row flex-col md:gap-0 gap-2 justify-between items-center w-full"
                    >
                        <div class="order-last md:order-first">
                            <p class="text-base text-slate-600 rtl:text-right">
                                عرض
                                <span class="text-teal-700 font-bold text-lg">{{
                                    props.tickets.from
                                }}</span>
                                إلى
                                <span class="text-teal-700 font-bold text-lg">{{
                                    props.tickets.to
                                }}</span>
                                من أصل {{ props.tickets.total }} تذكرة
                            </p>
                        </div>
                        <nav class="order-first md:order-last">
                            <div
                                class="flex items-center -space-x-px h-8 text-sm"
                            >
                                <template
                                    v-for="(link, index) in props.tickets.links"
                                    :key="link.url"
                                >
                                    <Link
                                        :preserve-scroll="true"
                                        v-if="link.url"
                                        :href="link.url"
                                        v-html="link.label"
                                        class="flex items-center justify-center px-3 h-8 leading-tight border border-gray-300 transition-colors"
                                        :class="{
                                            'text-teal-800 bg-white hover:bg-gray-100 hover:text-teal-900':
                                                !link.active,
                                            'bg-teal-700 text-white hover:bg-teal-700':
                                                link.active,
                                            'rounded-l-lg': index === 0,
                                            'rounded-r-lg':
                                                index ===
                                                props.tickets.links.length - 1,
                                        }"
                                    />
                                    <p
                                        v-else
                                        v-html="link.label"
                                        class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-slate-200 border border-gray-300"
                                        :class="{
                                            'rounded-l-lg': index === 0,
                                            'rounded-r-lg':
                                                index ===
                                                props.tickets.links.length - 1,
                                        }"
                                    />
                                </template>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.btn {
    @apply flex items-center gap-2 px-4 py-2 rounded-lg font-medium transition-colors;
}

/* Add animation for new messages */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out forwards;
}
</style>
