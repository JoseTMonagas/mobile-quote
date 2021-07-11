<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Stores: Update users
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form-section @submitted="onFormSubmit">
                        <template #title>
                            <strong class="p-3">Update store users</strong>
                        </template>
                        <template #description>
                            <nav-link
                                class="ml-2 mt-1"
                                :href="$route('stores.index')"
                            >
                                <span
                                    class="iconify"
                                    data-icon="mdi:arrow-left"
                                    data-inline="false"
                                ></span>
                                Back to index
                            </nav-link>

                            <ul class="p-3">
                                <li>
                                    <b>Store name:</b>
                                    {{ store.name }}
                                </li>
                            </ul>
                        </template>
                        <template #form>
                            <label class="col-span-2" for="users"
                                >Users for this store:</label
                            >
                            <v-select
                                multiple
                                v-model="users"
                                name="users"
                                :options="userList"
                                label="email"
                                class="col-span-4"
                            ></v-select>
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
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        AppLayout,
        NavLink,
        FormSection,
        vSelect,
        "x-button": Button,
        "x-input": Input
    },

    props: {
        userList: {
            type: Array,
            required: true
        },
        store: {
            type: Object,
            required: true
        }
    },

    created() {
        this.cleanForm();
    },

    data: () => {
        return {
            users: []
        };
    },

    methods: {
        cleanForm() {
            this.users = this.userList.filter(
                user => user.store_id == this.store.id
            );
        },
        onFormSubmit() {
            const users = this.users;
            axios
                .post(this.$route("stores.users", this.store.id), {
                    users
                })
                .catch(error => {
                    if (error.response) {
                        let textMsg = "";
                        if (error.status < 500) {
                            textMsg = "User input error";
                        } else {
                            textMsg = "Server error";
                        }
                        Swal.fire({
                            title: "An Error has ocurred!",
                            text: textMsg,
                            icon: "error"
                        });
                    } else if (error.request) {
                        Swal.fire({
                            title: "An Error has ocurred!",
                            text: "Response timeout",
                            icon: "error"
                        });
                    } else {
                        Swal.fire({
                            title: "An Error has ocurred!",
                            text: "Configuration error contact your webmaster",
                            icon: "error"
                        });
                    }
                })
                .then(response => {
                    if (response.status >= 200 && response.status < 400) {
                        Swal.fire({
                            title: "Store users saved succesfully!",
                            icon: "success"
                        });
                    }
                });
        }
    }
};
</script>
