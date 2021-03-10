<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Devices - Issues
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form-section @submitted="onFormSubmit">
                        <template #title>
                            <strong class="p-3"
                                >Configurate Device and Issues</strong
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
                            <table class="col-span-6">
                                <thead>
                                    <tr>
                                        <th
                                            class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell"
                                            scope="col"
                                        >
                                            Issue
                                        </th>
                                        <th
                                            class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell"
                                            scope="col"
                                        >
                                            Deduction
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(row, index) in issues"
                                        :key="row.issue.id"
                                        class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap lg:flex-no-wrap mb-10 lg:mb-0"
                                    >
                                        <td
                                            class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static"
                                        >
                                            {{ row.issue.name }}
                                        </td>
                                        <td
                                            class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static"
                                        >
                                            <x-input
                                                class="w-auto"
                                                v-model="row.deduction"
                                            ></x-input>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
        device: {
            type: Object,
            required: true
        },
        issueList: {
            type: Array,
            required: true
        }
    },
    created() {
        this.cleanForm();
    },
    data: () => {
        return {
            issues: []
        };
    },
    methods: {
        cleanForm() {
            this.issues = this.issueList.map(row => {
                const deduction = row.deduction ?? 0;
                const issue = row.issue;
                return { issue, deduction };
            });
        },
        onFormSubmit() {
            const issues = this.issues.filter(issue => issue.deduction > 0);

            axios
                .post(this.$route("device.issues", this.device.id), { issues })
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
                            title: "Device issue deduction saved succesfully",
                            icon: "success"
                        });
                    }
                });
        }
    }
};
</script>
