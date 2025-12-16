<template>
    <div class="h-screen overflow-hidden grid lg:grid-cols-2">
        <!-- Left Side - Hero Section -->
        <div
            class="hidden lg:flex flex-col justify-between bg-zinc-900 p-12 text-white relative overflow-hidden"
        >
            <div
                class="absolute inset-0 bg-gradient-to-br from-zinc-900/80 to-zinc-900/40 z-10"
            ></div>
            <img
                src="/images/auth-hero.png"
                alt="Hero"
                class="absolute inset-0 w-full h-full object-cover opacity-40"
            />

            <div class="relative z-20">
                <div class="flex items-center gap-2">
                    <img
                        src="/images/pos-logo.png"
                        alt="POS Logo"
                        class="h-10 w-10"
                    />
                    <span class="text-2xl font-bold">POS System</span>
                </div>
            </div>

            <div class="relative z-20">
                <blockquote class="space-y-2">
                    <p class="text-2xl font-medium leading-relaxed">
                        "This POS system transformed how we manage our business
                        operations."
                    </p>
                    <footer class="text-sm">
                        <div class="font-semibold">Sarah Chen</div>
                        <div class="text-zinc-400">
                            Business Owner & Entrepreneur
                        </div>
                    </footer>
                </blockquote>
            </div>
        </div>

        <!-- Right Side - Register Form -->
        <div
            class="flex items-center justify-center p-8 bg-background overflow-y-auto h-screen"
        >
            <div class="w-full max-w-md space-y-8 my-8">
                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center gap-2 mb-8">
                    <img
                        src="/images/pos-logo.png"
                        alt="POS Logo"
                        class="h-10 w-10"
                    />
                    <span class="text-2xl font-bold">POS System</span>
                </div>

                <div class="space-y-2">
                    <h1 class="text-3xl font-bold tracking-tight">
                        Create your company account
                    </h1>
                    <p class="text-muted-foreground">
                        Start managing your business with our powerful POS
                        system.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Company Information -->
                    <div class="space-y-4">
                        <div class="pb-2">
                            <h3 class="text-sm font-semibold">
                                Company Information
                            </h3>
                            <p class="text-xs text-muted-foreground">
                                Details about your business
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="company_name">Company Name</Label>
                            <Input
                                id="company_name"
                                v-model="form.company_name"
                                type="text"
                                placeholder="Acme Inc."
                                required
                                :class="{
                                    'border-destructive':
                                        form.errors.company_name,
                                }"
                            />
                            <p
                                v-if="form.errors.company_name"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.company_name }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="company_email">Company Email</Label>
                            <Input
                                id="company_email"
                                v-model="form.company_email"
                                type="email"
                                placeholder="contact@acme.com"
                                required
                                :class="{
                                    'border-destructive':
                                        form.errors.company_email,
                                }"
                            />
                            <p
                                v-if="form.errors.company_email"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.company_email }}
                            </p>
                        </div>
                    </div>

                    <Separator />

                    <!-- Owner Account -->
                    <div class="space-y-4">
                        <div class="pb-2">
                            <h3 class="text-sm font-semibold">Owner Account</h3>
                            <p class="text-xs text-muted-foreground">
                                Your personal login details
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="owner_name">Full Name</Label>
                            <Input
                                id="owner_name"
                                v-model="form.owner_name"
                                type="text"
                                placeholder="John Doe"
                                required
                                :class="{
                                    'border-destructive':
                                        form.errors.owner_name,
                                }"
                            />
                            <p
                                v-if="form.errors.owner_name"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.owner_name }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="owner_email">Your Email</Label>
                            <Input
                                id="owner_email"
                                v-model="form.owner_email"
                                type="email"
                                placeholder="john@acme.com"
                                required
                                :class="{
                                    'border-destructive':
                                        form.errors.owner_email,
                                }"
                            />
                            <p
                                v-if="form.errors.owner_email"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.owner_email }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="password">Password</Label>
                            <Input
                                id="password"
                                v-model="form.password"
                                type="password"
                                placeholder="••••••••"
                                required
                                :class="{
                                    'border-destructive': form.errors.password,
                                }"
                            />
                            <p
                                v-if="form.errors.password"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <div class="space-y-2">
                            <Label for="password_confirmation"
                                >Confirm Password</Label
                            >
                            <Input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                placeholder="••••••••"
                                required
                            />
                        </div>
                    </div>

                    <div class="space-y-4">
                        <Button
                            type="submit"
                            class="w-full"
                            :disabled="form.processing"
                        >
                            <span v-if="!form.processing">Create Account</span>
                            <span v-else>Creating...</span>
                        </Button>

                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <span class="w-full border-t" />
                            </div>
                            <div
                                class="relative flex justify-center text-xs uppercase"
                            >
                                <span
                                    class="bg-background px-2 text-muted-foreground"
                                    >OR</span
                                >
                            </div>
                        </div>

                        <Button type="button" variant="outline" class="w-full">
                            <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24">
                                <path
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                    fill="#4285F4"
                                />
                                <path
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                    fill="#34A853"
                                />
                                <path
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                    fill="#FBBC05"
                                />
                                <path
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                    fill="#EA4335"
                                />
                            </svg>
                            Continue with Google
                        </Button>
                    </div>
                </form>

                <p class="text-center text-sm text-muted-foreground">
                    Already have an account?{' '}
                    <Link
                        href="/login"
                        class="text-primary hover:underline font-medium"
                    >
                        Sign in
                    </Link>
                </p>

                <!-- Info box -->
                <div class="p-4 bg-muted rounded-lg">
                    <p class="text-xs text-muted-foreground">
                        <strong class="font-medium text-foreground"
                            >Note:</strong
                        >
                        After registration, 3 users will be created
                        automatically: Owner (you), Admin, and Cashier with
                        default password "password".
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, Link } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Separator } from "@/components/ui/separator";

const form = useForm({
    company_name: "",
    company_email: "",
    owner_name: "",
    owner_email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post("/register", {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>
