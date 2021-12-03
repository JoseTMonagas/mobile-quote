<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Items: {{ formType }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form-section @submitted="onFormSubmit">
                        <template #title>
                            <strong class="p-3">{{ formType }} Item</strong>
                        </template>
                        <template #description>
                            <nav-link
                                class="ml-2 mt-1"
                                :href="$route('items.index')"
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
                            <label for="date">Date</label>
                            <x-input
                                class="col-span-2"
                                type="date"
                                v-model="date"
                            ></x-input>

                            <label for="supplier">Supplier</label>
                            <x-input
                                class="col-span-2"
                                type="text"
                                v-model="supplier"
                            ></x-input>

                            <label for="manufacturer">Manufacturer</label>
                            <x-input
                                class="col-span-2"
                                type="text"
                                v-model="manufacturer"
                            ></x-input>

                            <label for="model">Model</label>
                            <x-input
                                class="col-span-2"
                                type="text"
                                v-model="model"
                            ></x-input>

                            <label for="colour">Colour</label>
                            <x-input
                                class="col-span-2"
                                type="text"
                                v-model="colour"
                            ></x-input>

                            <label for="battery">Battery</label>
                            <x-input
                                class="col-span-2"
                                type="text"
                                v-model="battery"
                            ></x-input>

                            <label for="grade">Grade</label>
                            <x-input
                                class="col-span-2"
                                type="text"
                                v-model="grade"
                            ></x-input>

                            <label for="issues">Issues</label>
                            <x-input
                                class="col-span-2"
                                type="text"
                                v-model="issues"
                            ></x-input>

                            <label for="cost">Cost</label>
                            <x-input
                                class="col-span-2"
                                type="text"
                                v-model="cost"
                            ></x-input>

                            <label for="imei">Imei</label>
                            <x-input
                                class="col-span-2"
                                type="text"
                                v-model="imei"
                            ></x-input>

                            <label for="selling_price">Selling Price</label>
                            <x-input
                                class="col-span-2"
                                type="text"
                                v-model="selling_price"
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
        item: {
            type: Object,
            required: false,
            default: null
        }
    },
    created() {
        if (this.item !== null) {
            this.name = this.item.name;
        }
    },
    computed: {
        formType: function() {
            if (this.item !== null) {
                return "Edit";
            }
            return "Create";
        },
        requestUrl: function() {
            return this.item !== null
                ? this.$route("items.update", this.item.id)
                : this.$route("items.store");
        }
    },
    data: () => {
        return {
            date: "",
            supplier: "",
            manufacturer: "",
            model: "",
            colour: "",
            battery: "",
            grade: "",
            issues: "",
            cost: "",
            imei: "",
            selling_price: ""
        };
    },
    methods: {
        cleanForm() {
            this.name = "";
        },
        onFormSubmit() {
            let formData = new FormData();
            formData.append("name", this.name);

            if (this.item !== null) {
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
                            title: "Item saved successfully",
                            icon: "success",
                            text: `Item Name: ${response.data.name}`
                        });
                        this.cleanForm();
                    }
                });
        }
    }
};
</script>
