<script setup>
import Checkbox from "@/Components/Checkbox.vue";
import GuestLayout from "@/Layouts/GuestLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link } from "@inertiajs/vue3";
</script>

<script>
import SetHeader from "@/Components/SetHeader.vue";
import axios from "axios";

export default {
    name: "login",
    props: {
        msg: String,
    },
    data() {
        return {
            username: "",
            password: "",
            msg: "",
            showPassword: false,
            password: null
        };
    },
    computed: {
        buttonLabel() {
            return (this.showPassword) ? "Hide" : "Show";
        }
    },
    methods: {
        login() {
            const credentials = {
                username: this.username,
                password: this.password,
            };

            axios
                .post("/login", credentials)
                .then((response) => {
                    if (response.data.success) {
                        localStorage.setItem(
                            "jwtToken",
                            response.data.jwtToken
                        );
                        SetHeader(response.data);
                        window.location.href = "/admin/dashboard";
                    } else {
                        this.msg = response.data.message;
                    }
                })
                .catch((err) => (this.msg = err.response.data.message));
        },
        toggleShow() {
            this.showPassword = !this.showPassword;
        }
    }
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="login">
            <div>
                <TextInput
                    id="username"
                    type="text"
                    placeholder="Username"
                    class="block w-full"
                    v-model="username"
                    required
                    autofocus
                    autocomplete="username"
                />
                <p class="mb-4 font-medium text-sm text-red-600">{{ msg }}</p>
            </div>

            <div class="mt-4 relative">
                <TextInput
                    v-if="showPassword"
                    id="password"
                    type="text"
                    placeholder="Password"
                    class="block w-full"
                    v-model="password"
                    required
                    autocomplete="current-password"
                />
                <TextInput
                    v-else
                    id="password"
                    type="password"
                    placeholder="Password"
                    class="block w-full"
                    v-model="password"
                    required
                    autocomplete="current-password"
                />
                <span @click="toggleShow" class="absolute w-[20px] text-center text-[#6B7280] right-[13px] top-[10px]">
                    <i class="fas" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
                </span>
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="remember" />
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link
                    :href="route('password.request')"
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    class="ml-4"
                    :class="{ 'opacity-25': processing }"
                    :disabled="processing"
                >
                    Log in
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
