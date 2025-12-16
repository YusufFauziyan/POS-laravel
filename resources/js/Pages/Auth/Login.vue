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
                        "Simply all the tools that my team and I need."
                    </p>
                    <footer class="text-sm">
                        <div class="font-semibold">Alex Jordan</div>
                        <div class="text-zinc-400">
                            Director of Digital Marketing
                        </div>
                    </footer>
                </blockquote>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex items-center justify-center p-8 bg-background">
            <div class="w-full max-w-md space-y-8">
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
                        Welcome back to POS System
                    </h1>
                    <p class="text-muted-foreground">
                        Build your business effortlessly with our powerful POS
                        application.
                    </p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="space-y-4">
                        <div class="space-y-2">
                            <Label for="email">Email</Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="alex.jordan@gmail.com"
                                required
                                :class="{
                                    'border-destructive': form.errors.email,
                                }"
                            />
                            <p
                                v-if="form.errors.email"
                                class="text-sm text-destructive"
                            >
                                {{ form.errors.email }}
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
                            />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-2">
                                <Switch
                                    id="remember"
                                    v-model:checked="form.remember"
                                />
                                <Label
                                    for="remember"
                                    class="text-sm font-normal cursor-pointer"
                                >
                                    Remember sign in details
                                </Label>
                            </div>

                            <a
                                href="#"
                                class="text-sm text-primary hover:underline"
                            >
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <Button
                            type="submit"
                            class="w-full"
                            :disabled="form.processing"
                        >
                            <span v-if="!form.processing">Log in</span>
                            <span v-else>Logging in...</span>
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
                    Don't have an account?{' '}
                    <Link
                        href="/register"
                        class="text-primary hover:underline font-medium"
                    >
                        Sign up
                    </Link>
                </p>

                <!-- Demo accounts info -->
                <div class="mt-6 p-4 bg-muted rounded-lg">
                    <p class="text-xs text-muted-foreground font-medium mb-1">
                        Demo accounts:
                    </p>
                    <p class="text-xs text-muted-foreground">
                        admin-a@company-a.test / cashier-a@company-a.test /
                        owner-a@company-a.test
                    </p>
                    <p class="text-xs text-muted-foreground">
                        Password: password
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
import { Switch } from "@/components/ui/switch";

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post("/login", {
        onFinish: () => form.reset("password"),
    });
};
</script>
