<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Devices: {{ formType.label }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form-section @submitted="onFormSubmit">
                        <template #title>
                            <strong class="p-3"
                                >{{ formType.label }} Device</strong
                            >
                        </template>
                        <template #description>
                            <nav-link
                                class="ml-2 mt-1"
                                :href="$route('device.index')"
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
                            <label for="company">Company:</label>
                            <x-input
                                class="col-span-2"
                                v-model="company"
                            ></x-input>
                            <label for="image">Image:</label>
                            <input
                                type="file"
                                name="image"
                                class="col-span-2"
                                @change="image = $event.target.files[0]"
                            />
                            <img v-if="image" :src="image" alt="" />
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
        storeUrl: {
            type: String,
            required: true
        },
        device: {
            type: Object,
            required: false,
            default: null
        }
    },
    created() {
        if (this.device !== null) {
            this.name = this.device.name;
            this.company = this.device.company;
            this.image = this.device.image;
        }
    },
    computed: {
        formType: function() {
            if (this.device !== null) {
                return {
                    label: "Edit",
                    route: this.$route("device.update", this.device.id),
                    method: "put"
                };
            }
            return {
                label: "Create",
                route: this.$route("device.store"),
                method: "post"
            };
        }
    },
    data: () => {
        return {
            name: "",
            company: "",
            image: ""
        };
    },
    methods: {
        cleanForm() {
            this.name = "";
            this.company = "";
            this.image = "";
        },
        onFormSubmit() {
            const method = this.formType.method;
            const url = this.formType.route;
            let data = new FormData();
            data.append("name", this.name);
            data.append("company", this.company);
            data.append("image", this.image);
            axios({
                method,
                url,
                data,
                headers: {
                    "Content-Type": "multipart/form-data"
                }
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
                    Swal.fire({
                        title: "Device saved successfully",
                        icon: "success",
                        text: `Device Name: ${response.data.name}\n
                                Device Company: ${response.data.company}`
                    });
                    this.cleanForm();
                });
        }
    }
};
</script>
