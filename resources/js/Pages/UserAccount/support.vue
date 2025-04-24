<script setup>
import { ref, computed, onMounted } from "vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import Tag from "primevue/tag";
import Avatar from "primevue/avatar";
import Sidebar from "primevue/sidebar";
import Textarea from "primevue/textarea";
import Tooltip from "primevue/tooltip";
import Badge from "primevue/badge";
import Dialog from "primevue/dialog";
import Card from "primevue/card";
import TabView from "primevue/tabview";
import TabPanel from "primevue/tabpanel";
import Divider from "primevue/divider";

defineOptions({
    layout: UserLayout,
});

const props = defineProps({
    tickets: {
        type: Array,
        default: () => [],
    },
    user: {
        type: Object,
        default: () => ({}),
    },
});

// Use provided tickets directly
const tickets = ref(props.tickets.length > 0 ? props.tickets : []);
const currentUser = ref(props.user);

// New ticket form
const showNewTicketDialog = ref(false);
const newTicket = ref({
    subject: "",
    message: "",
});

// Selected ticket and reply
const selectedTicket = ref(null);
const showTicketDrawer = ref(false);
const replyContent = ref("");

// Filters
const statusFilter = ref(null);
const search = ref("");

// Status options
const statusOptions = [
    { label: "الكل", value: null },
    { label: "مفتوح", value: "open" },
    { label: "قيد المعالجة", value: "in_progress" },
    { label: "مغلق", value: "closed" },
];

// Filtered tickets
const filteredTickets = computed(() => {
    return tickets.value.filter((ticket) => {
        // Apply search filter
        const matchesSearch =
            search.value === "" ||
            ticket.subject.toLowerCase().includes(search.value.toLowerCase()) ||
            ticket.messages[0]?.content
                .toLowerCase()
                .includes(search.value.toLowerCase());

        // Apply status filter
        const matchesStatus =
            statusFilter.value === null || ticket.status === statusFilter.value;

        return matchesSearch && matchesStatus;
    });
});

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

// Format date without time
const formatDateOnly = (dateString) => {
    if (!dateString) return "";
    const date = new Date(dateString);
    return new Intl.DateTimeFormat("ar-SA", {
        year: "numeric",
        month: "short",
        day: "numeric",
    }).format(date);
};

// Get status label and severity
const getStatusInfo = (status) => {
    switch (status) {
        case "open":
            return { label: "مفتوح", severity: "warning" };
        case "in_progress":
            return { label: "قيد المعالجة", severity: "info" };
        case "closed":
            return { label: "مغلق", severity: "success" };
        default:
            return { label: status, severity: "secondary" };
    }
};

// Open ticket drawer
const openTicket = (ticket) => {
    selectedTicket.value = ticket;
    showTicketDrawer.value = true;
    replyContent.value = "";

    // Here you would mark user messages as read
    // For demonstration, we'll just set user_unread_count to 0
    if (ticket.user_unread_count > 0) {
        ticket.user_unread_count = 0;
        // In a real app, you would call an API to mark messages as read
    }
};

// Send reply
const sendReply = () => {
    if (!replyContent.value.trim()) return;

    // Here you would send the reply to your backend
    // For demonstration, we'll just add it to the messages array
    const newMessage = {
        id: Math.max(...selectedTicket.value.messages.map((m) => m.id)) + 1,
        ticket_id: selectedTicket.value.id,
        sender: "user",
        content: replyContent.value,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
    };

    selectedTicket.value.messages.push(newMessage);

    // Update last_message_at
    selectedTicket.value.last_message_at = new Date().toISOString();

    // Increment admin_unread_count
    selectedTicket.value.admin_unread_count++;

    // Clear reply content
    replyContent.value = "";
};

// Close ticket
const closeTicket = () => {
    // Here you would send the request to close the ticket to your backend
    selectedTicket.value.status = "closed";

    // Add a system message
    const systemMessage = {
        id: Math.max(...selectedTicket.value.messages.map((m) => m.id)) + 1,
        ticket_id: selectedTicket.value.id,
        sender: "system",
        content: "تم إغلاق التذكرة",
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
    };

    selectedTicket.value.messages.push(systemMessage);

    // Update last_message_at
    selectedTicket.value.last_message_at = new Date().toISOString();

    // Increment admin_unread_count
    selectedTicket.value.admin_unread_count++;
};

// Reopen ticket
const reopenTicket = () => {
    // Here you would send the request to reopen the ticket to your backend
    selectedTicket.value.status = "open";

    // Add a system message
    const systemMessage = {
        id: Math.max(...selectedTicket.value.messages.map((m) => m.id)) + 1,
        ticket_id: selectedTicket.value.id,
        sender: "system",
        content: "تم إعادة فتح التذكرة",
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
    };

    selectedTicket.value.messages.push(systemMessage);

    // Update last_message_at
    selectedTicket.value.last_message_at = new Date().toISOString();

    // Increment admin_unread_count
    selectedTicket.value.admin_unread_count++;
};

// Reset filters
const resetFilters = () => {
    search.value = "";
    statusFilter.value = null;
};

// Create new ticket
const createNewTicket = () => {
    if (!newTicket.value.subject.trim() || !newTicket.value.message.trim())
        return;

    // Here you would send the new ticket to your backend
    // For demonstration, we'll just add it to the tickets array
    const newTicketObj = {
        id: Math.max(...tickets.value.map((t) => t.id)) + 1,
        user_id: currentUser.value.id,
        subject: newTicket.value.subject,
        status: "open",
        admin_unread_count: 1,
        user_unread_count: 0,
        last_message_at: new Date().toISOString(),
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString(),
        messages: [
            {
                id: Date.now(),
                ticket_id: Math.max(...tickets.value.map((t) => t.id)) + 1,
                sender: "user",
                content: newTicket.value.message,
                created_at: new Date().toISOString(),
                updated_at: new Date().toISOString(),
            },
        ],
        user: currentUser.value,
    };

    tickets.value.unshift(newTicketObj);

    // Reset form and close dialog
    newTicket.value = {
        subject: "",
        message: "",
    };
    showNewTicketDialog.value = false;
};

// Group messages by date
const groupMessagesByDate = (messages) => {
    const groups = {};

    messages.forEach((message) => {
        const date = new Date(message.created_at);
        const dateKey = `${date.getFullYear()}-${date.getMonth()}-${date.getDate()}`;

        if (!groups[dateKey]) {
            groups[dateKey] = {
                date: date,
                messages: [],
            };
        }

        groups[dateKey].messages.push(message);
    });

    return Object.values(groups).sort((a, b) => a.date - b.date);
};

// Get unread count
const getUnreadCount = computed(() => {
    return tickets.value.reduce(
        (total, ticket) => total + ticket.user_unread_count,
        0
    );
});
</script>

<template>
    <div class="user-support-page">
        <!-- Header Section -->
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6"
        >
            <div class="flex items-center gap-3">
                <div class="bg-primary/10 p-3 rounded-full">
                    <i
                        class="pi pi-comments text-primary"
                        style="font-size: 1.5rem"
                    ></i>
                </div>
                <div>
                    <h1 class="text-2xl font-semibold m-0">الدعم الفني</h1>
                    <p class="text-gray-500 text-sm m-0">
                        تواصل مع فريق الدعم الفني للمساعدة
                    </p>
                </div>
            </div>

            <Button
                label="إنشاء تذكرة جديدة"
                icon="pi pi-plus"
                class="w-full md:w-auto"
                @click="showNewTicketDialog = true"
            />
        </div>

        <!-- Tickets Section -->
        <TabView>
            <TabPanel header="تذاكر الدعم الفني">
                <!-- Filters Section -->
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6"
                >
                    <div class="flex flex-wrap items-center gap-4">
                        <!-- Search -->
                        <div class="search-container flex-1 min-w-[250px]">
                            <span class="p-input-icon-right w-full">
                                <i class="pi pi-search" />
                                <InputText
                                    v-model="search"
                                    class="w-full rounded-lg border-gray-200"
                                    placeholder="البحث في التذاكر..."
                                />
                            </span>
                        </div>

                        <!-- Status Filter -->
                        <div class="min-w-[180px]">
                            <Dropdown
                                v-model="statusFilter"
                                :options="statusOptions"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="حالة التذكرة"
                                class="w-full"
                            />
                        </div>

                        <!-- Reset Filters -->
                        <Button
                            v-tooltip="'إعادة ضبط الفلتر'"
                            icon="pi pi-filter-slash"
                            outlined
                            severity="secondary"
                            aria-label="إعادة ضبط الفلتر"
                            class="p-button-rounded"
                            @click="resetFilters"
                        />
                    </div>
                </div>

                <!-- Tickets Cards -->
                <div
                    v-if="filteredTickets.length > 0"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"
                >
                    <Card
                        v-for="ticket in filteredTickets"
                        :key="ticket.id"
                        class="ticket-card cursor-pointer hover:shadow-md transition-shadow"
                        @click="openTicket(ticket)"
                    >
                        <template #header>
                            <div
                                class="flex justify-between items-center p-3 bg-gray-50 border-b"
                            >
                                <span class="font-mono text-sm text-gray-500"
                                    >#{{ ticket.id }}</span
                                >
                                <Tag
                                    :value="getStatusInfo(ticket.status).label"
                                    :severity="
                                        getStatusInfo(ticket.status).severity
                                    "
                                />
                            </div>
                        </template>

                        <template #title>
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold truncate">
                                    {{ ticket.subject }}
                                </h3>
                                <Badge
                                    v-if="ticket.user_unread_count > 0"
                                    :value="ticket.user_unread_count"
                                    severity="danger"
                                />
                            </div>
                        </template>

                        <template #content>
                            <p class="text-gray-600 line-clamp-2 mb-4">
                                {{ ticket.messages[0]?.content || "" }}
                            </p>

                            <div
                                class="flex justify-between items-center text-sm text-gray-500"
                            >
                                <div>
                                    <i class="pi pi-calendar ml-1"></i>
                                    {{ formatDateOnly(ticket.created_at) }}
                                </div>
                                <div>
                                    <i class="pi pi-comments ml-1"></i>
                                    {{ ticket.messages.length }} ردود
                                </div>
                            </div>
                        </template>

                        <template #footer>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">
                                    <i class="pi pi-clock ml-1"></i>
                                    آخر تحديث:
                                    {{ formatDate(ticket.last_message_at) }}
                                </span>
                                <Button
                                    icon="pi pi-arrow-left"
                                    text
                                    rounded
                                    aria-label="عرض التفاصيل"
                                />
                            </div>
                        </template>
                    </Card>
                </div>

                <!-- Empty State -->
                <div
                    v-else
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 text-center"
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
                        لم يتم العثور على أي تذاكر مطابقة لمعايير البحث الحالية.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center gap-3">
                        <Button
                            icon="pi pi-filter-slash"
                            label="إعادة ضبط الفلتر"
                            outlined
                            @click="resetFilters"
                        />
                        <Button
                            icon="pi pi-plus"
                            label="إنشاء تذكرة جديدة"
                            @click="showNewTicketDialog = true"
                        />
                    </div>
                </div>
            </TabPanel>

            <TabPanel header="الأسئلة الشائعة">
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-100 p-6"
                >
                    <h2 class="text-xl font-semibold mb-4">الأسئلة الشائعة</h2>

                    <div class="faq-item mb-4 pb-4 border-b border-gray-100">
                        <h3 class="text-lg font-medium mb-2">
                            كيف يمكنني الاستثمار في العقارات؟
                        </h3>
                        <p class="text-gray-600">
                            يمكنك الاستثمار في العقارات من خلال تصفح المشاريع
                            المتاحة واختيار المشروع المناسب لك، ثم اتباع خطوات
                            الاستثمار المبينة في صفحة المشروع.
                        </p>
                    </div>

                    <div class="faq-item mb-4 pb-4 border-b border-gray-100">
                        <h3 class="text-lg font-medium mb-2">
                            ما هو الحد الأدنى للاستثمار؟
                        </h3>
                        <p class="text-gray-600">
                            الحد الأدنى للاستثمار هو ٥٬٠٠٠ ريال سعودي لكل مشروع.
                        </p>
                    </div>

                    <div class="faq-item mb-4 pb-4 border-b border-gray-100">
                        <h3 class="text-lg font-medium mb-2">
                            كيف يمكنني تحديث بياناتي الشخصية؟
                        </h3>
                        <p class="text-gray-600">
                            يمكنك تحديث بياناتك الشخصية من خلال الذهاب إلى صفحة
                            الملف الشخصي في لوحة التحكم الخاصة بك.
                        </p>
                    </div>

                    <div class="faq-item mb-4 pb-4 border-b border-gray-100">
                        <h3 class="text-lg font-medium mb-2">
                            كيف يمكنني الحصول على نسخة من العقد؟
                        </h3>
                        <p class="text-gray-600">
                            يمكنك تحميل نسخة من العقد من خلال الذهاب إلى صفحة
                            "عقاراتي" ثم اختيار العقار المطلوب والضغط على "تحميل
                            العقد".
                        </p>
                    </div>

                    <div class="faq-item">
                        <h3 class="text-lg font-medium mb-2">
                            كيف يمكنني التواصل مع فريق الدعم الفني؟
                        </h3>
                        <p class="text-gray-600">
                            يمكنك التواصل مع فريق الدعم الفني من خلال إنشاء
                            تذكرة دعم فني جديدة في هذه الصفحة.
                        </p>
                    </div>
                </div>
            </TabPanel>
        </TabView>

        <!-- New Ticket Dialog -->
        <Dialog
            v-model:visible="showNewTicketDialog"
            modal
            header="إنشاء تذكرة دعم فني جديدة"
            :style="{ width: '90vw', maxWidth: '550px' }"
            :closable="true"
            class="rtl-dialog"
        >
            <div class="p-3">
                <div class="mb-4">
                    <label
                        for="subject"
                        class="block text-sm font-medium text-gray-700 mb-1"
                        >موضوع التذكرة</label
                    >
                    <InputText
                        id="subject"
                        v-model="newTicket.subject"
                        class="w-full"
                        placeholder="أدخل موضوع التذكرة"
                    />
                </div>

                <div class="mb-4">
                    <label
                        for="message"
                        class="block text-sm font-medium text-gray-700 mb-1"
                        >الرسالة</label
                    >
                    <Textarea
                        id="message"
                        v-model="newTicket.message"
                        rows="5"
                        class="w-full"
                        placeholder="اكتب رسالتك هنا..."
                        autoResize
                    />
                </div>

                <div class="flex justify-end">
                    <Button
                        label="إلغاء"
                        icon="pi pi-times"
                        outlined
                        class="ml-2"
                        @click="showNewTicketDialog = false"
                    />
                    <Button
                        label="إرسال"
                        icon="pi pi-send"
                        @click="createNewTicket"
                        :disabled="
                            !newTicket.subject.trim() ||
                            !newTicket.message.trim()
                        "
                    />
                </div>
            </div>
        </Dialog>

        <!-- Ticket Drawer -->
        <Sidebar
            v-model:visible="showTicketDrawer"
            position="left"
            :style="{ width: '90vw', maxWidth: '550px' }"
            :dismissable="true"
            :showCloseIcon="true"
            class="ticket-drawer"
        >
            <template v-if="selectedTicket">
                <!-- Ticket Header -->
                <div
                    class="ticket-header p-4 border-b border-gray-200 sticky top-0 bg-white z-10"
                >
                    <div class="flex items-center justify-between mb-2">
                        <h2
                            class="text-xl font-bold m-0 flex items-center gap-2"
                        >
                            <span class="text-primary"
                                >#{{ selectedTicket.id }}</span
                            >
                            {{ selectedTicket.subject }}
                        </h2>
                        <Tag
                            :value="getStatusInfo(selectedTicket.status).label"
                            :severity="
                                getStatusInfo(selectedTicket.status).severity
                            "
                        />
                    </div>

                    <div class="text-sm text-gray-500 mt-2">
                        <i class="pi pi-calendar ml-1"></i>
                        تم الإنشاء: {{ formatDate(selectedTicket.created_at) }}
                    </div>
                </div>

                <!-- Ticket Content -->
                <div class="ticket-content p-4">
                    <!-- Messages -->
                    <div class="messages-container mb-4">
                        <div
                            v-for="(group, index) in groupMessagesByDate(
                                selectedTicket.messages
                            )"
                            :key="index"
                            class="message-group mb-6"
                        >
                            <!-- Date Header -->
                            <div class="date-header flex justify-center mb-4">
                                <div
                                    class="bg-gray-100 text-gray-600 text-sm py-1 px-3 rounded-full"
                                >
                                    {{ formatDateOnly(group.date) }}
                                </div>
                            </div>

                            <!-- Messages for this date -->
                            <div
                                v-for="message in group.messages"
                                :key="message.id"
                                class="message mb-4 last:mb-0"
                            >
                                <!-- System Message -->
                                <div
                                    v-if="message.sender === 'system'"
                                    class="flex justify-center my-4"
                                >
                                    <div
                                        class="bg-gray-100 text-gray-600 text-sm py-1 px-4 rounded-full flex items-center gap-2"
                                    >
                                        <i class="pi pi-info-circle"></i>
                                        {{ message.content }}
                                    </div>
                                </div>

                                <!-- User Message -->
                                <div
                                    v-else-if="message.sender === 'user'"
                                    class="flex flex-row-reverse gap-3 max-w-[80%] mr-auto"
                                >
                                    <Avatar
                                        :image="selectedTicket.user.image"
                                        size="normal"
                                        shape="circle"
                                    />
                                    <div class="flex-1">
                                        <div
                                            class="flex items-center justify-end gap-2"
                                        >
                                            <span
                                                class="text-xs text-gray-500"
                                                >{{
                                                    formatDate(
                                                        message.created_at
                                                    )
                                                }}</span
                                            >
                                            <span class="font-medium">أنت</span>
                                        </div>
                                        <div
                                            class="mt-1 p-3 bg-primary/10 text-primary-900 rounded-lg rounded-tl-none"
                                        >
                                            {{ message.content }}
                                        </div>
                                    </div>
                                </div>

                                <!-- Admin Message -->
                                <div v-else class="flex gap-3 max-w-[80%]">
                                    <Avatar
                                        image="/storage/users_images/default-user-image.webp"
                                        size="normal"
                                        shape="circle"
                                    />
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium"
                                                >فريق الدعم</span
                                            >
                                            <span
                                                class="text-xs text-gray-500"
                                                >{{
                                                    formatDate(
                                                        message.created_at
                                                    )
                                                }}</span
                                            >
                                        </div>
                                        <div
                                            class="mt-1 p-3 bg-gray-100 rounded-lg rounded-tr-none"
                                        >
                                            {{ message.content }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reply Form -->
                <div
                    class="reply-form p-4 border-t border-gray-200 sticky bottom-0 bg-white"
                >
                    <div v-if="selectedTicket.status !== 'closed'">
                        <Textarea
                            v-model="replyContent"
                            rows="3"
                            class="w-full mb-3"
                            placeholder="اكتب ردك هنا..."
                            autoResize
                        />
                        <div class="flex justify-between">
                            <Button
                                label="إغلاق التذكرة"
                                icon="pi pi-check-circle"
                                severity="success"
                                outlined
                                @click="closeTicket"
                            />
                            <Button
                                label="إرسال الرد"
                                icon="pi pi-send"
                                @click="sendReply"
                                :disabled="!replyContent.trim()"
                            />
                        </div>
                    </div>

                    <!-- Closed Ticket Actions -->
                    <div v-else class="closed-ticket-actions">
                        <div
                            class="bg-gray-50 border border-gray-200 rounded-lg p-4 flex items-center justify-between"
                        >
                            <div class="flex items-center gap-2">
                                <i
                                    class="pi pi-check-circle text-green-500"
                                ></i>
                                <span class="font-medium"
                                    >تم إغلاق هذه التذكرة</span
                                >
                            </div>
                            <Button
                                label="إعادة فتح التذكرة"
                                icon="pi pi-refresh"
                                outlined
                                @click="reopenTicket"
                            />
                        </div>
                    </div>
                </div>
            </template>
        </Sidebar>
    </div>
</template>

<style scoped>
.user-support-page {
    direction: rtl;
}

/* Card styling */
.ticket-card {
    transition: all 0.2s ease;
}

.ticket-card:hover {
    transform: translateY(-2px);
}

/* Dialog customization */
.rtl-dialog :deep(.p-dialog-header),
.rtl-dialog :deep(.p-dialog-content),
.rtl-dialog :deep(.p-dialog-footer) {
    direction: rtl;
    text-align: right;
}

/* Drawer customization */
.ticket-drawer {
    direction: rtl;
}

:deep(.p-sidebar-header) {
    display: none;
}

:deep(.p-sidebar-content) {
    padding: 0;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.ticket-content {
    flex: 1;
    overflow-y: auto;
}

/* Empty state animation */
.empty-state-icon {
    animation: pulse 2s infinite ease-in-out;
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
    100% {
        transform: scale(1);
    }
}

/* Ensure RTL support for PrimeVue components */
:deep(.p-inputtext),
:deep(.p-dropdown) {
    text-align: right;
}

/* Line clamp for truncating text */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Tab view customization */
:deep(.p-tabview-nav) {
    justify-content: center;
    margin-bottom: 1.5rem;
}

:deep(.p-tabview-nav-link) {
    padding: 1rem 1.5rem;
}

:deep(.p-tabview-panels) {
    padding: 0;
}
</style>
