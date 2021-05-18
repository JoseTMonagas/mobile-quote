<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Stores: {{ formType }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form-section @submitted="onFormSubmit">
                        <template #title>
                            <strong class="p-3">{{ formType }} Store</strong>
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
                        </template>
                        <template #form>
                            <label for="name">Name:</label>
                            <x-input
                                class="col-span-2"
                                v-model="name"
                            ></x-input>

                            <label for="email">Email:</label>
                            <x-input
                                class="col-span-2"
                                v-model="email"
                            ></x-input>

                            <label class="col-span-2" for="address"
                                >Address:</label
                            >
                            <x-input
                                class="col-span-4"
                                v-model="address"
                            ></x-input>

                            <label class="col-span-2" for="price_percent"
                                >Price Decrease (%):</label
                            >
                            <x-input
                                class="col-span-1"
                                v-model="price_percent"
                            ></x-input>
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
        storeEdit: {
            type: Object,
            required: false,
            default: null
        }
    },
    created() {
        if (this.storeEdit !== null) {
            this.name = this.storeEdit.name;
            this.email = this.storeEdit.email;
            this.address = this.storeEdit.address;
            this.price_percent = this.storeEdit.price_percent;
        }
    },
    computed: {
        formType: function() {
            return this.storeEdit !== null ? "Edit" : "Create";
        },
        requestUrl: function() {
            return this.storeEdit !== null
                ? this.$route("stores.update", this.storeEdit.id)
                : this.$route("stores.store");
        }
    },
    data: () => {
        return {
            name: "",
            email: "",
            address: "",
            price_percent: 0
        };
    },
    methods: {
        cleanForm() {
            this.name = "";
            this.email = "";
            this.address = "";
            this.price_percent = 0;
        },
        onFormSubmit() {
            let formData = new FormData();
            formData.append("name", this.name);
            formData.append("address", this.address);
            formData.append("email", this.email);
            formData.append("price_percent", this.price_percent);

            if (this.storeEdit !== null) {
                formData.append("_method", "put");
            }

            axios
                .post(this.requestUrl, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data"
                    }
                })
                .catch(error => {
                    if (error.response) {
                        let textMsg = "";
                        if (error.response.status < 500) {
                            let errors = error.response.data.errors;
                            for (const error in errors) {
                                textMsg += errors[error] + "\n";
                            }
                        } else {
                            textMsg = "Server error";
                        }
                        Swal.fire({
                            title: "An Error has ocurred!",
                            html: `<pre>${textMsg}</pre>`,
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
                            title: "Store saved successfully",
                            icon: "success",
                            text: `Store Name: ${response.data.name}`
                        });
                        this.cleanForm();
                    }
                });
        }
    }
};
</script>
