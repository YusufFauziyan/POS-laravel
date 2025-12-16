<template>
    <div class="min-h-screen bg-background">
        <!-- Navigation -->
        <nav
            class="fixed top-0 left-0 right-0 bg-background/80 backdrop-blur-lg border-b z-50"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center gap-2">
                        <img
                            src="/images/pos-logo.png"
                            alt="POS Logo"
                            class="h-10 w-10"
                        />
                        <span class="text-xl font-bold">POS System</span>
                    </div>
                    <div class="hidden md:flex items-center space-x-8">
                        <a
                            href="#features"
                            class="text-sm font-medium hover:text-primary transition"
                            >Features</a
                        >
                        <a
                            href="#pricing"
                            class="text-sm font-medium hover:text-primary transition"
                            >Pricing</a
                        >
                        <a
                            href="#contact"
                            class="text-sm font-medium hover:text-primary transition"
                            >Contact</a
                        >
                        <Link href="/login">
                            <Button variant="ghost">Sign In</Button>
                        </Link>
                        <Link href="/register">
                            <Button>Get Started</Button>
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center space-y-8">
                    <Badge variant="secondary" class="mb-4">
                        <span class="text-xs font-semibold"
                            >Modern Point of Sale Solution</span
                        >
                    </Badge>

                    <h1
                        class="text-4xl md:text-6xl lg:text-7xl font-bold tracking-tight"
                    >
                        Streamline Your Business
                        <span class="block text-primary mt-2"
                            >with Modern POS</span
                        >
                    </h1>

                    <p
                        class="text-xl md:text-2xl text-muted-foreground max-w-3xl mx-auto"
                    >
                        Powerful, easy-to-use point of sale system designed to
                        help you manage sales, track inventory, and grow your
                        business.
                    </p>

                    <div
                        class="flex flex-col sm:flex-row gap-4 justify-center pt-4"
                    >
                        <Link href="/register">
                            <Button size="lg" class="text-lg px-8">
                                Start Free Trial
                            </Button>
                        </Link>
                        <a href="#features">
                            <Button
                                size="lg"
                                variant="outline"
                                class="text-lg px-8"
                            >
                                Learn More
                            </Button>
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-4xl font-bold text-primary"
                                >99.9%</CardTitle
                            >
                            <CardDescription>Uptime Guarantee</CardDescription>
                        </CardHeader>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-4xl font-bold text-primary"
                                >10k+</CardTitle
                            >
                            <CardDescription>Active Users</CardDescription>
                        </CardHeader>
                    </Card>
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-4xl font-bold text-primary"
                                >24/7</CardTitle
                            >
                            <CardDescription>Support Available</CardDescription>
                        </CardHeader>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-muted/50">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16 space-y-4">
                    <h2 class="text-3xl md:text-5xl font-bold tracking-tight">
                        Powerful Features
                    </h2>
                    <p class="text-xl text-muted-foreground">
                        Everything you need to run your business efficiently
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                >
                    <!-- Feature Cards -->
                    <Card>
                        <CardHeader>
                            <div
                                class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4"
                            >
                                <ShoppingCart class="w-6 h-6 text-primary" />
                            </div>
                            <CardTitle>Fast Checkout</CardTitle>
                            <CardDescription>
                                Process transactions quickly with our intuitive
                                POS interface designed for speed.
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <Card>
                        <CardHeader>
                            <div
                                class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4"
                            >
                                <Package class="w-6 h-6 text-primary" />
                            </div>
                            <CardTitle>Inventory Management</CardTitle>
                            <CardDescription>
                                Track stock levels, get low stock alerts, and
                                manage your inventory effortlessly.
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <Card>
                        <CardHeader>
                            <div
                                class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4"
                            >
                                <BarChart3 class="w-6 h-6 text-primary" />
                            </div>
                            <CardTitle>Analytics & Reports</CardTitle>
                            <CardDescription>
                                Get insights into your sales with beautiful
                                charts and detailed reports.
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <Card>
                        <CardHeader>
                            <div
                                class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4"
                            >
                                <Smartphone class="w-6 h-6 text-primary" />
                            </div>
                            <CardTitle>Mobile Ready</CardTitle>
                            <CardDescription>
                                Works perfectly on tablets and mobile devices
                                for on-the-go sales.
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <Card>
                        <CardHeader>
                            <div
                                class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4"
                            >
                                <Users class="w-6 h-6 text-primary" />
                            </div>
                            <CardTitle>Multi-User Access</CardTitle>
                            <CardDescription>
                                Role-based permissions for admin, cashier, and
                                owner accounts.
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <Card>
                        <CardHeader>
                            <div
                                class="w-12 h-12 bg-primary/10 rounded-lg flex items-center justify-center mb-4"
                            >
                                <DollarSign class="w-6 h-6 text-primary" />
                            </div>
                            <CardTitle>Multiple Payments</CardTitle>
                            <CardDescription>
                                Accept cash, QRIS, and e-wallet payments
                                seamlessly.
                            </CardDescription>
                        </CardHeader>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Pricing Section -->
        <section id="pricing" class="py-20 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16 space-y-4">
                    <h2 class="text-3xl md:text-5xl font-bold tracking-tight">
                        Simple Pricing
                    </h2>
                    <p class="text-xl text-muted-foreground">
                        Choose the plan that fits your business
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Starter Plan -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-2xl">Starter</CardTitle>
                            <div class="mt-4">
                                <span class="text-4xl font-bold">$29</span>
                                <span class="text-muted-foreground"
                                    >/month</span
                                >
                            </div>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>1 User</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>100 Products</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>Basic Reports</span>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Link href="/register" class="w-full">
                                <Button variant="outline" class="w-full"
                                    >Get Started</Button
                                >
                            </Link>
                        </CardFooter>
                    </Card>

                    <!-- Professional Plan -->
                    <Card class="border-primary shadow-lg scale-105">
                        <CardHeader>
                            <Badge class="w-fit mb-2">Popular</Badge>
                            <CardTitle class="text-2xl">Professional</CardTitle>
                            <div class="mt-4">
                                <span class="text-4xl font-bold">$79</span>
                                <span class="text-muted-foreground"
                                    >/month</span
                                >
                            </div>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>5 Users</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>Unlimited Products</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>Advanced Analytics</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>Priority Support</span>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Link href="/register" class="w-full">
                                <Button class="w-full">Get Started</Button>
                            </Link>
                        </CardFooter>
                    </Card>

                    <!-- Enterprise Plan -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-2xl">Enterprise</CardTitle>
                            <div class="mt-4">
                                <span class="text-4xl font-bold">$199</span>
                                <span class="text-muted-foreground"
                                    >/month</span
                                >
                            </div>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>Unlimited Users</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>Unlimited Products</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>Custom Reports</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Check class="w-5 h-5 text-primary" />
                                <span>24/7 Support</span>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <Link href="/register" class="w-full">
                                <Button variant="outline" class="w-full"
                                    >Contact Sales</Button
                                >
                            </Link>
                        </CardFooter>
                    </Card>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section
            class="py-20 px-4 sm:px-6 lg:px-8 bg-primary text-primary-foreground"
        >
            <div class="max-w-4xl mx-auto text-center space-y-8">
                <h2 class="text-3xl md:text-5xl font-bold">
                    Ready to transform your business?
                </h2>
                <p class="text-xl opacity-90">
                    Join thousands of businesses already using our POS system
                </p>
                <Link href="/register">
                    <Button size="lg" variant="secondary" class="text-lg px-8">
                        Start Your Free Trial
                    </Button>
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer id="contact" class="bg-muted py-12 px-4 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center gap-2 mb-4">
                            <img
                                src="/images/pos-logo.png"
                                alt="POS Logo"
                                class="h-8 w-8"
                            />
                            <span class="text-xl font-bold">POS System</span>
                        </div>
                        <p class="text-sm text-muted-foreground">
                            Modern point of sale solution for modern businesses.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Product</h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li>
                                <a
                                    href="#features"
                                    class="hover:text-foreground transition"
                                    >Features</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#pricing"
                                    class="hover:text-foreground transition"
                                    >Pricing</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-foreground transition"
                                    >Demo</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Company</h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-foreground transition"
                                    >About</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-foreground transition"
                                    >Blog</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-foreground transition"
                                    >Careers</a
                                >
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-sm text-muted-foreground">
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-foreground transition"
                                    >Help Center</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-foreground transition"
                                    >Contact</a
                                >
                            </li>
                            <li>
                                <a
                                    href="#"
                                    class="hover:text-foreground transition"
                                    >Privacy</a
                                >
                            </li>
                        </ul>
                    </div>
                </div>
                <Separator class="my-8" />
                <div class="text-center text-sm text-muted-foreground">
                    <p>&copy; 2025 POS System. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Separator } from "@/components/ui/separator";
import {
    ShoppingCart,
    Package,
    BarChart3,
    Smartphone,
    Users,
    DollarSign,
    Check,
} from "lucide-vue-next";
</script>
