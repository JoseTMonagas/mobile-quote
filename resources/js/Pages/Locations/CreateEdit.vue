<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Locations: {{ formType }}
            </h2>
        </template>

        <!-- Locations CREATE/EDIT -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form-section @submitted="onFormSubmit">
                        <template #title>
                            <strong class="p-3">{{ formType }} Location</strong>
                        </template>
                        <template #description>
                            <nav-link
                                class="ml-2 mt-1"
                                :href="
                                    $route('stores.locations.index', store.id)
                                "
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
                            <label class="text-right" for="name">Name:</label>
                            <x-input
                                class="col-span-1"
                                v-model="name"
                            ></x-input>

                            <label class="text-right" for="address"
                                >Address:</label
                            >
                            <x-input
                                class="col-span-3"
                                v-model="address"
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
        store: {
            type: Object,
            required: true
        },
        location: {
            type: Object,
            required: false,
            default: null
        }
    },
    created() {
        if (this.location !== null) {
            this.name = this.location.name;
            this.address = this.location.address;
        }
    },
    computed: {
        formType: function() {
            return this.location !== null ? "Edit" : "Create";
        },
        requestUrl: function() {
            return this.location !== null
                ? this.$route("locations.update", this.location.id)
                : this.$route("stores.locations.store", this.store.id);
        }
    },
    data: () => {
        return {
            name: "",
            address: ""
        };
    },
    methods: {
        cleanForm() {
            this.name = "";
            this.address = "";
        },
        onFormSubmit() {
            let formData = new FormData();
            formData.append("name", this.name);
            formData.append("address", this.address);

            if (this.location !== null) {
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
