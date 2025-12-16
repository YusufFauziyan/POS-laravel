<template>
    <div class="flex min-h-screen bg-background">
        <!-- Desktop Sidebar -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 border-r bg-card">
            <!-- Logo & User Info -->
            <div class="p-6 border-b">
                <h1 class="text-2xl font-bold">POS System</h1>
                <div class="mt-4 flex items-center gap-3">
                    <Avatar>
                        <AvatarFallback>{{ userInitials }}</AvatarFallback>
                    </Avatar>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium truncate">
                            {{ $page.props.auth.user.name }}
                        </p>
                        <p class="text-xs text-muted-foreground capitalize">
                            {{ $page.props.auth.user.role }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4 space-y-1">
                <Link
                    href="/dashboard"
                    :class="
                        cn(
                            buttonVariants({
                                variant:
                                    $page.url === '/dashboard'
                                        ? 'secondary'
                                        : 'ghost',
                            }),
                            'w-full justify-start'
                        )
                    "
                >
                    <Home class="mr-2 h-4 w-4" />
                    Dashboard
                </Link>

                <!-- POS - Admin & Cashier only -->
                <Link
                    v-if="isAdmin || isCashier"
                    href="/pos"
                    :class="
                        cn(
                            buttonVariants({
                                variant: $page.url.startsWith('/pos')
                                    ? 'secondary'
                                    : 'ghost',
                            }),
                            'w-full justify-start'
                        )
                    "
                >
                    <ShoppingCart class="mr-2 h-4 w-4" />
                    POS
                </Link>

                <!-- Orders - All roles -->
                <Link
                    href="/orders"
                    :class="
                        cn(
                            buttonVariants({
                                variant: $page.url.startsWith('/orders')
                                    ? 'secondary'
                                    : 'ghost',
                            }),
                            'w-full justify-start'
                        )
                    "
                >
                    <FileText class="mr-2 h-4 w-4" />
                    Orders
                </Link>

                <!-- Categories - Admin only -->
                <Link
                    v-if="isAdmin"
                    href="/categories"
                    :class="
                        cn(
                            buttonVariants({
                                variant: $page.url.startsWith('/categories')
                                    ? 'secondary'
                                    : 'ghost',
                            }),
                            'w-full justify-start'
                        )
                    "
                >
                    <Tag class="mr-2 h-4 w-4" />
                    Categories
                </Link>

                <!-- Products - All roles -->
                <Link
                    href="/products"
                    :class="
                        cn(
                            buttonVariants({
                                variant: $page.url.startsWith('/products')
                                    ? 'secondary'
                                    : 'ghost',
                            }),
                            'w-full justify-start'
                        )
                    "
                >
                    <Package class="mr-2 h-4 w-4" />
                    Products
                </Link>

                <!-- Inventory - Admin & Owner only -->
                <Link
                    v-if="isAdmin || isOwner"
                    href="/inventory"
                    :class="
                        cn(
                            buttonVariants({
                                variant: $page.url.startsWith('/inventory')
                                    ? 'secondary'
                                    : 'ghost',
                            }),
                            'w-full justify-start'
                        )
                    "
                >
                    <ClipboardList class="mr-2 h-4 w-4" />
                    Inventory
                </Link>

                <!-- User Management - Admin & Owner only -->
                <Link
                    v-if="isAdmin || isOwner"
                    href="/users"
                    :class="
                        cn(
                            buttonVariants({
                                variant: $page.url.startsWith('/users')
                                    ? 'secondary'
                                    : 'ghost',
                            }),
                            'w-full justify-start'
                        )
                    "
                >
                    <Users class="mr-2 h-4 w-4" />
                    Users
                </Link>
            </nav>

            <!-- Logout -->
            <div class="p-4 border-t">
                <Link
                    href="/logout"
                    method="post"
                    as="button"
                    :class="
                        cn(
                            buttonVariants({ variant: 'outline' }),
                            'w-full justify-start'
                        )
                    "
                >
                    <LogOut class="mr-2 h-4 w-4" />
                    Logout
                </Link>
            </div>
        </aside>

        <!-- Mobile Header & Sidebar -->
        <Sheet v-model:open="sidebarOpen">
            <header
                class="lg:hidden fixed top-0 left-0 right-0 bg-card border-b z-40"
            >
                <div class="flex items-center justify-between px-4 py-3">
                    <SheetTrigger as-child>
                        <Button variant="ghost" size="icon">
                            <Menu class="h-6 w-6" />
                        </Button>
                    </SheetTrigger>
                    <h1 class="text-lg font-bold">POS System</h1>
                    <div class="w-10"></div>
                </div>
            </header>

            <SheetContent side="left" class="w-64 p-0">
                <div class="flex flex-col h-full">
                    <!-- User Info -->
                    <div class="p-6 border-b">
                        <div class="flex items-center gap-3">
                            <Avatar>
                                <AvatarFallback>{{
                                    userInitials
                                }}</AvatarFallback>
                            </Avatar>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium truncate">
                                    {{ $page.props.auth.user.name }}
                                </p>
                                <p
                                    class="text-xs text-muted-foreground capitalize"
                                >
                                    {{ $page.props.auth.user.role }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation -->
                    <nav class="flex-1 p-4 space-y-1">
                        <Link
                            href="/dashboard"
                            @click="sidebarOpen = false"
                            :class="
                                cn(
                                    buttonVariants({
                                        variant:
                                            $page.url === '/dashboard'
                                                ? 'secondary'
                                                : 'ghost',
                                    }),
                                    'w-full justify-start'
                                )
                            "
                        >
                            <Home class="mr-2 h-4 w-4" />
                            Dashboard
                        </Link>

                        <Link
                            v-if="isAdmin || isCashier"
                            href="/pos"
                            @click="sidebarOpen = false"
                            :class="
                                cn(
                                    buttonVariants({
                                        variant: $page.url.startsWith('/pos')
                                            ? 'secondary'
                                            : 'ghost',
                                    }),
                                    'w-full justify-start'
                                )
                            "
                        >
                            <ShoppingCart class="mr-2 h-4 w-4" />
                            POS
                        </Link>

                        <Link
                            href="/orders"
                            @click="sidebarOpen = false"
                            :class="
                                cn(
                                    buttonVariants({
                                        variant: $page.url.startsWith('/orders')
                                            ? 'secondary'
                                            : 'ghost',
                                    }),
                                    'w-full justify-start'
                                )
                            "
                        >
                            <FileText class="mr-2 h-4 w-4" />
                            Orders
                        </Link>

                        <Link
                            v-if="isAdmin"
                            href="/categories"
                            @click="sidebarOpen = false"
                            :class="
                                cn(
                                    buttonVariants({
                                        variant: $page.url.startsWith(
                                            '/categories'
                                        )
                                            ? 'secondary'
                                            : 'ghost',
                                    }),
                                    'w-full justify-start'
                                )
                            "
                        >
                            <Tag class="mr-2 h-4 w-4" />
                            Categories
                        </Link>

                        <Link
                            href="/products"
                            @click="sidebarOpen = false"
                            :class="
                                cn(
                                    buttonVariants({
                                        variant: $page.url.startsWith(
                                            '/products'
                                        )
                                            ? 'secondary'
                                            : 'ghost',
                                    }),
                                    'w-full justify-start'
                                )
                            "
                        >
                            <Package class="mr-2 h-4 w-4" />
                            Products
                        </Link>

                        <Link
                            v-if="isAdmin || isOwner"
                            href="/inventory"
                            @click="sidebarOpen = false"
                            :class="
                                cn(
                                    buttonVariants({
                                        variant: $page.url.startsWith(
                                            '/inventory'
                                        )
                                            ? 'secondary'
                                            : 'ghost',
                                    }),
                                    'w-full justify-start'
                                )
                            "
                        >
                            <ClipboardList class="mr-2 h-4 w-4" />
                            Inventory
                        </Link>

                        <Link
                            v-if="isAdmin || isOwner"
                            href="/users"
                            @click="sidebarOpen = false"
                            :class="
                                cn(
                                    buttonVariants({
                                        variant: $page.url.startsWith('/users')
                                            ? 'secondary'
                                            : 'ghost',
                                    }),
                                    'w-full justify-start'
                                )
                            "
                        >
                            <Users class="mr-2 h-4 w-4" />
                            Users
                        </Link>
                    </nav>

                    <!-- Logout -->
                    <div class="p-4 border-t">
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            :class="
                                cn(
                                    buttonVariants({ variant: 'outline' }),
                                    'w-full justify-start'
                                )
                            "
                        >
                            <LogOut class="mr-2 h-4 w-4" />
                            Logout
                        </Link>
                    </div>
                </div>
            </SheetContent>
        </Sheet>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col lg:ml-0 pt-14 lg:pt-0">
            <!-- Header -->
            <header class="hidden lg:block border-b bg-card">
                <div class="px-8 py-4">
                    <slot name="header">
                        <h2 class="text-2xl font-bold">
                            {{ title }}
                        </h2>
                    </slot>
                </div>
            </header>

            <!-- Flash Messages -->
            <div
                v-if="showFlash"
                :class="[
                    'mx-4 lg:mx-8 mt-4 border-l-4 p-4 rounded-lg',
                    flashType === 'success'
                        ? 'bg-green-50 border-green-500 text-green-700'
                        : 'bg-red-50 border-red-500 text-red-700',
                ]"
            >
                <p>{{ flashMessage }}</p>
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-8">
                <slot />
            </main>
        </div>

        <!-- Mobile Bottom Navigation (Cashier only) -->
        <nav
            v-if="isCashier"
            class="lg:hidden fixed bottom-0 left-0 right-0 bg-card border-t z-30"
        >
            <div class="grid grid-cols-4">
                <Link
                    href="/dashboard"
                    class="flex flex-col items-center py-3 px-2 transition-colors"
                    :class="
                        $page.url === '/dashboard'
                            ? 'text-primary bg-primary/10'
                            : 'text-muted-foreground hover:text-primary'
                    "
                >
                    <Home class="h-6 w-6 mb-1" />
                    <span class="text-xs font-medium">Home</span>
                </Link>

                <Link
                    href="/pos"
                    class="flex flex-col items-center py-3 px-2 transition-colors"
                    :class="
                        $page.url.startsWith('/pos')
                            ? 'text-primary bg-primary/10'
                            : 'text-muted-foreground hover:text-primary'
                    "
                >
                    <ShoppingCart class="h-6 w-6 mb-1" />
                    <span class="text-xs font-medium">POS</span>
                </Link>

                <Link
                    href="/orders"
                    class="flex flex-col items-center py-3 px-2 transition-colors"
                    :class="
                        $page.url.startsWith('/orders')
                            ? 'text-primary bg-primary/10'
                            : 'text-muted-foreground hover:text-primary'
                    "
                >
                    <FileText class="h-6 w-6 mb-1" />
                    <span class="text-xs font-medium">Orders</span>
                </Link>

                <Link
                    href="/products"
                    class="flex flex-col items-center py-3 px-2 transition-colors"
                    :class="
                        $page.url.startsWith('/products')
                            ? 'text-primary bg-primary/10'
                            : 'text-muted-foreground hover:text-primary'
                    "
                >
                    <Package class="h-6 w-6 mb-1" />
                    <span class="text-xs font-medium">Products</span>
                </Link>
            </div>
        </nav>
    </div>
</template>

<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import { Button, buttonVariants } from "@/components/ui/button";
import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet";
import { Avatar, AvatarFallback } from "@/components/ui/avatar";
import { cn } from "@/lib/utils";
import {
    Home,
    ShoppingCart,
    FileText,
    Tag,
    Package,
    ClipboardList,
    Users,
    LogOut,
    Menu,
} from "lucide-vue-next";

defineProps({
    title: String,
});

const page = usePage();
const showFlash = ref(false);
const flashMessage = ref("");
const flashType = ref("success");
const sidebarOpen = ref(false);

// Role checks
const user = computed(() => page.props.auth?.user);
const userRole = computed(() => user.value?.role);
const isAdmin = computed(() => userRole.value === "admin");
const isCashier = computed(() => userRole.value === "cashier");
const isOwner = computed(() => userRole.value === "owner");

// User initials for avatar
const userInitials = computed(() => {
    const name = user.value?.name || "";
    return name
        .split(" ")
        .map((n) => n[0])
        .join("")
        .toUpperCase()
        .slice(0, 2);
});

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success || flash?.error) {
            flashMessage.value = flash.success || flash.error;
            flashType.value = flash.success ? "success" : "error";
            showFlash.value = true;
            setTimeout(() => {
                showFlash.value = false;
            }, 3000);
        }
    },
    { deep: true }
);
</script>
