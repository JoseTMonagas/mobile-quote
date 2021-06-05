<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Users: Update role
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form-section @submitted="onFormSubmit">
                        <template #title>
                            <strong class="p-3">Update user role</strong>
                        </template>
                        <template #description>
                            <nav-link
                                class="ml-2 mt-1"
                                :href="$route('users.index')"
                            >
                                <span
                                    class="iconify"
                                    data-icon="mdi:arrow-left"
                                    data-inline="false"
                                ></span>
                                Back to index
                            </nav-link>
                            <ul class="p-2">
                                <li>
                                    <b>Name:</b>
                                    <span>{{ userEdit.name }}</span>
                                </li>
                                <li>
                                    <b>Email:</b>
                                    <span>{{ userEdit.email }}</span>
                                </li>
                            </ul>
                        </template>
                        <template #form>
                            <label for="role">Select role:</label>
                            <select
                                class="border-gray-300 bg-gray-100 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                                v-model="role"
                            >
                                <option
                                    v-if="currentUserRole == 'OWNER'"
                                    value="OWNER"
                                    >Owner</option
                                >
                                <option value="ADMIN">Admin</option>
                                <option value="USER">User</option>
                            </select>
                        </template>
                        <template #actions>
                            <section class="flex flex-row justify-around">
                                <x-button
                                    class="mx-2"
                                    type="reset"
                                    @click="cleanForm"
                                    >Reset</x-button
                                >
                                <x-button class="mx-2">Save</x-button>
                            </section>
                        </template>
                    </form-section>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import NavLink from "@/Jetstream/NavLink";
import FormSection from "@/Jetstream/FormSection";
import Button from "@/Jetstream/Button";
import Input from "@/Jetstream/Input";

export default {
    components: {
        AppLayout,
        NavLink,
        FormSection,
        "x-button": Button,
        "x-input": Input
    },
    props: {
        userEdit: {
            type: Object,
            required: true
        },
        currentUserRole: {
            type: String,
            required: true
        }
    },
    created() {
        this.cleanForm();
    },
    data: () => {
        return {
            role: null
        };
    },
    methods: {
        cleanForm() {
            this.role = this.userEdit.role;
        },
        onFormSubmit() {
            const role = this.role;
            axios
                .post(this.$route("users.updateRole", this.userEdit.id), {
                    role
                })
                .catch(error => {
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        Swal.fire({
                            title: "An Error has ocurred!",
                            text: error.response.data,
                            icon: "error"
                        });
                    } else if (error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        Swal.fire({
                            title: "An Error has ocurred!",
                            text: error.request,
                            icon: "error"
                        });
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        Swal.fire({
                            title: "An Error has ocurred!",
                            text: error.message,
                            icon: "error"
                        });
                    }
                })
                .then(response => {
                    if (response.status >= 200 && response.status < 400) {
                        Swal.fire({
                            title: "User saved successfully",
                            icon: "success",
                            text: `User Name: ${response.data.name}`
                        });
                        this.cleanForm();
                    }
                });
        }
    }
};
</script>
