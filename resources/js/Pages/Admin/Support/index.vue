<script setup>
import { ref, computed } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link } from "@inertiajs/vue3";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Select from "primevue/select";
import Tag from "primevue/tag";
import Avatar from "primevue/avatar";
import Drawer from "primevue/drawer";
import Textarea from "primevue/textarea";
import Tooltip from "primevue/tooltip";
import Badge from "primevue/badge";
import Divider from "primevue/divider";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    tickets: {
        type: Object,
        default: () => [],
    },
});

const tickets = ref(props.tickets);

// Selected ticket and reply
const selectedTicket = ref(null);
const showTicketDrawer = ref(false);
const replyContent = ref("");

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

// Get user type in Arabic
const getUserTypeArabic = (type) => {
    switch (type) {
        case "owner":
            return "مالك";
        case "investor":
            return "مستثمر";
        case "tenant":
            return "مستأجر";
        default:
            return type;
    }
};
const getTypeSeverity = (type) => {
    switch (type) {
        case "owner":
            return "success";
        case "investor":
            return "warning";
        case "tenant":
            return "info";
        default:
            return "secondary";
    }
};

// Open ticket drawer
const openTicket = (ticket) => {
    selectedTicket.value = ticket;
    showTicketDrawer.value = true;
    replyContent.value = "";
    // ########################################### Here you would mark admin messages as read
};

// Send reply
const sendReply = () => {
    // ############################################ Here you would send the reply to your backend
};

// Get first message content (for description in table)
const getFirstMessageContent = (ticket) => {
    return ticket.messages && ticket.messages.length > 0
        ? ticket.messages[0].content
        : "";
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
</script>

<template>
    <div class="support-page">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <i
                    class="pi pi-comments text-teal-800 h-full"
                    style="font-size: 2rem"
                ></i>
                <div class="">
                    <h1 class="text-3xl font-semibold m-0 text-teal-800">
                        الدعم الفني
                    </h1>
                    <p class="text-gray-500 text-sm m-0">
                        إدارة تذاكر الدعم الفني والاستفسارات
                    </p>
                </div>
            </div>
            <div>
                <Link
                    :href="route('admin.support.index')"
                    class="btn bg-teal-600 hover:bg-teal-700 text-white"
                >
                    <p>إنشاء تذكرة</p>
                    <i class="pi pi-plus"></i>
                </Link>
            </div>
        </div>

        <!-- Filters Section -->
        <div
            class="filter-section bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-6"
        >
            <div class="flex flex-wrap items-center gap-4">
                <!-- Search -->
                <div class="search-container flex-1 min-w-[250px]">
                    <InputText
                        v-model="search"
                        class="w-full rounded-lg border-gray-200"
                        placeholder="البحث عن رسالة ..."
                    />
                </div>
                <!-- Filters -->
                <div
                    class="filter-controls flex items-center gap-3 md:w-72 w-full"
                >
                    <Select
                        v-model="userType"
                        :options="UsersTypes"
                        optionLabel="label"
                        optionValue="value"
                        placeholder="نوع الحساب"
                        class="md:w-60 w-full rounded-lg"
                    />
                    <Button
                        icon="pi pi-filter-slash"
                        outlined
                        severity="secondary"
                        class="p-button-rounded w-44"
                        @click="resetFilters"
                    />
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="tickets.length === 0" class="p-8 text-center bg-white rounded-xl">
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
            <Button
                icon="pi pi-filter-slash"
                label="إعادة ضبط الفلتر"
                outlined
                @click="resetFilters"
            />
        </div>

        <!-- Tickets Table -->
        <div
            v-else
            class=" rounded-xl overflow-hidden flex flex-col justify-between min-h-[75vh]"
        >
            <DataTable
                :value="tickets.data"
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
                        <span class="font-mono">#{{ slotProps.data.id }}</span>
                    </template>
                </Column>

                <Column field="subject" header="الموضوع">
                    <template #body="slotProps">
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <div class="font-medium">
                                    {{ slotProps.data.subject }}
                                </div>
                                <div
                                    class="text-sm text-gray-500 truncate max-w-md"
                                >
                                    {{ getFirstMessageContent(slotProps.data) }}
                                </div>
                            </div>
                            <div
                                v-if="slotProps.data.admin_unread_count > 0"
                                class="flex-shrink-0"
                            >
                                <Badge
                                    :value="slotProps.data.admin_unread_count"
                                    severity="danger"
                                ></Badge>
                            </div>
                        </div>
                    </template>
                </Column>

                <Column field="user" header="المستخدم">
                    <template #body="slotProps">
                        <div class="flex items-center gap-2">
                            <Avatar
                                :image="slotProps.data.user.image"
                                size="normal"
                                shape="circle"
                            />
                            <div>
                                <div class="font-medium">
                                    {{ slotProps.data.user.name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ slotProps.data.user.email }}
                                </div>
                            </div>
                        </div>
                    </template>
                </Column>

                <Column
                    field="user.type"
                    header="نوع المستخدم"
                    class="w-[120px]"
                >
                    <template #body="slotProps">
                        <Tag
                            :value="getUserTypeArabic(slotProps.data.user.type)"
                            :severity="getTypeSeverity(slotProps.data.user.type)"
                        />
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
                            {{ formatDate(slotProps.data.last_message_at) }}
                        </div>
                    </template>
                </Column>

            </DataTable>
            <!-- Pagination -->
            <div
                v-if="1"
                dir="ltr"
                class="my-8 flex md:flex-row flex-col md:gap-0 gap-2 justify-between items-center w-full"
            >
                <div class="order-last md:order-first">
                    <p class="text-base text-slate-600 rtl:text-right">
                        عرض
                        <span class="text-teal-600 font-bold text-lg">{{
                            tickets.from
                        }}</span>
                        إلى
                        <span class="text-teal-600 font-bold text-lg">{{
                            tickets.to
                        }}</span>
                        من أصل {{ tickets.total }} مستخدم
                    </p>
                </div>
                <nav class="order-first md:order-last">
                    <div class="flex items-center -space-x-px h-8 text-sm">
                        <template
                            v-for="(link, index) in tickets.links"
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
                                    'bg-teal-600 text-white hover:bg-teal-600':
                                        link.active,
                                    'rounded-l-lg': index === 0,
                                    'rounded-r-lg':
                                        index === tickets.links.length - 1,
                                }"
                            />
                            <p
                                v-else
                                v-html="link.label"
                                class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-slate-200 border border-gray-300"
                                :class="{
                                    'rounded-l-lg': index === 0,
                                    'rounded-r-lg':
                                        index === tickets.links.length - 1,
                                }"
                            />
                        </template>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Ticket Drawer -->
        <Drawer
            v-model:visible="showTicketDrawer"
            :style="{ width: '50vw' }"
            position="left"
            :dismissable="true"
            :showCloseIcon="true"
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
                            <span class="">#{{ selectedTicket.id }}</span>
                            {{ selectedTicket.subject }}
                        </h2>
                        <Tag
                            :value="getStatusInfo(selectedTicket.status).label"
                            :severity="
                                getStatusInfo(selectedTicket.status).severity
                            "
                        />
                    </div>

                    <div class="flex items-center gap-3 mt-4">
                        <Avatar
                            :image="selectedTicket.user.image"
                            size="large"
                            shape="circle"
                        />
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-semibold m-0">
                                    {{ selectedTicket.user.name }}
                                </h3>
                                <Tag
                                    :value="
                                        getUserTypeArabic(
                                            selectedTicket.user.type
                                        )
                                    "
                                    severity="secondary"
                                    class="text-xs"
                                />
                            </div>
                            <p class="text-sm text-gray-500 m-0">
                                {{ selectedTicket.user.email }}
                            </p>
                        </div>
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
                                    class="flex gap-3 max-w-[80%]"
                                >
                                    <Avatar
                                        :image="selectedTicket.user.image"
                                        size="normal"
                                        shape="circle"
                                    />
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium">{{
                                                selectedTicket.user.name
                                            }}</span>
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

                                <!-- Admin Message -->
                                <div
                                    v-else
                                    class="flex flex-row-reverse gap-3 max-w-[80%] mr-auto"
                                >
                                    <Avatar
                                        image="/storage/users_images/default-user-image.webp"
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
                                            <span class="font-medium"
                                                >المشرف</span
                                            >
                                        </div>
                                        <div
                                            class="mt-1 p-3 rounded-lg rounded-tl-none"
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
                    class="reply-form p-4 border-t border-gray-200 sticky bottom-0 bg-slate-100 -mt-8"
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
        </Drawer>
    </div>
</template>
