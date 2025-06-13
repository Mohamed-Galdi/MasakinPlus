<script setup>
import { ref, computed, watch, nextTick } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { debounce } from "lodash";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Select from "primevue/select";
import Tag from "primevue/tag";
import Avatar from "primevue/avatar";
import Drawer from "primevue/drawer";
import Textarea from "primevue/textarea";
import Badge from "primevue/badge";
import { useTextHelpers } from "@/plugins/textHelpers";
import FloatLabel from "primevue/floatlabel";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import Toast from "primevue/toast";
import MultiSelect from "primevue/multiselect";
import Header from "@/Components/AdminDashboard/Header.vue";

defineOptions({
    layout: AdminLayout,
});

const props = defineProps({
    tickets: {
        type: Object,
        default: () => [],
    },
    users: {
        type: Array,
        default: () => [],
    },
    usersTypes: {
        type: Array,
        required: true,
    },
    search: {
        type: String,
        required: false,
        default: "",
    },
    userType: {
        type: String,
        required: false,
        default: "",
    },
});

const toast = useToast();

const tickets = ref(props.tickets);
const UsersTypes = ref(props.usersTypes);

const isMobile = computed(() => window.innerWidth <= 768);
// ########################################################################################## search and filter
const textHelpers = useTextHelpers();
const search = ref(props.search);
const userType = ref(props.userType);

watch(
    [search, userType],
    debounce(([search, userType]) => {
        // Update the table with both filters
        router.get(
            route("admin.support.index"),
            {
                search: search,
                userType: userType,
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
    userType.value = "";
};

// ########################################################################################## utils

// User Type
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
    users_id: [],
});

const sendTicket = () => {
    ticketForm.post(route("admin.support.create"), {
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
        onError: () => {
            const errorMessage = Object.values(ticketForm.errors)[0];
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
        route("admin.support.resetUnreadCount", { ticketId: ticket.id }),
        {},
        {
            preserveState: true, // Prevent page reload
            onSuccess: () => {
                selectedTicket.value.admin_unread_count = 0; // Update UI
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
        route("admin.support.reply", { ticketId: selectedTicket.value.id }),
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
                    sender: "admin",
                    content: replyForm.content,
                    created_at: new Date().toISOString(),
                    updated_at: new Date().toISOString(),
                });
                replyForm.reset(); // Clear reply form
            },
            onError: () => {
                const errorMessage = Object.values(replyForm.errors)[0];
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
                    <!-- Users Selection -->
                    <div>
                        <MultiSelect
                            filter
                            emptyFilterMessage="لم يتم العثور على أي مستخدم"
                            v-model="ticketForm.users_id"
                            :options="users"
                            optionLabel="name"
                            optionValue="id"
                            placeholder="اختر المستخدمين"
                            class="w-full"
                        />
                    </div>

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
                        الرد على التذكرة
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
                                    ? 'bg-sky-100 text-teal-900 self-start'
                                    : 'bg-gray-100 text-gray-900 self-end',
                            ]"
                        >
                            <p class="text-sm font-medium mb-1">
                                {{
                                    message.sender === "admin"
                                        ? "الإدارة"
                                        : selectedTicket.user.name
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

        <!-- Header Section -->
        <Header
            icon="pi-comments"
            title="الدعم الفني"
            subtitle=" إدارة تذاكر الدعم الفني والاستفسارات"
        >
            <button
                @click="showNewTicketDrawer = !showNewTicketDrawer"
                class="btn bg-slate-200 hover:bg-slate-100 text-slate-800"
            >
                <p>إنشاء تذكرة</p>
                <i class="pi pi-plus"></i>
            </button>
        </Header>

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
                        placeholder="البحث عن تذكرة أو مستخدم ..."
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
                        <span class="font-mono">#{{ slotProps.data.id }}</span>
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
                                    {{ getLastMessageContent(slotProps.data) }}
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
                                <div
                                    class="font-medium"
                                    v-html="
                                        textHelpers.highlightText(
                                            slotProps.data.user.name,
                                            search
                                        )
                                    "
                                ></div>
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
                            :severity="
                                getTypeSeverity(slotProps.data.user.type)
                            "
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
                            {{ $formatDate(slotProps.data.created_at) }}
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
                            {{ $formatDate(slotProps.data.last_message_at) }}
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
                        <span class="text-teal-600 font-bold text-lg">{{
                            props.tickets.from
                        }}</span>
                        إلى
                        <span class="text-teal-600 font-bold text-lg">{{
                            props.tickets.to
                        }}</span>
                        من أصل {{ props.tickets.total }} تذكرة
                    </p>
                </div>
                <nav class="order-first md:order-last">
                    <div class="flex items-center -space-x-px h-8 text-sm">
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
                                    'bg-teal-600 text-white hover:bg-teal-600':
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
</template>
