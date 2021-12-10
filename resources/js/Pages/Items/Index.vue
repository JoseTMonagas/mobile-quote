<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Items: Index
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <header
                        class="inline-flex flex-row justify-between items-center w-full px-4 my-2"
                    >
                        <nav-link
                            class="ml-3 mt-2 px-2 py-1 border border-gray-400 rounded shadow"
                            :href="$route('items.create')"
                            >CREATE NEW</nav-link
                        >
                        <span> You have selected: {{ totalSelected }} </span>
                        <nav-link
                            class="ml-3 mt-2 px-2 py-1 text-gray-800 border border-gray-400 bg-green-400 rounded shadow"
                            >SELL SELECTED</nav-link
                        >
                        <button
                            class="ml-3 mt-2 px-2 py-1 border border-gray-400 rounded shadow"
                            @click="onEdit()"
                        >
                            EDIT SELECTED
                        </button>
                        <button
                            class="ml-3 mt-2 px-2 py-1 text-gray-800 border border-gray-400 bg-red-400 rounded shadow"
                            @click="onDeleteMultiple()"
                        >
                            DELETE SELECTED
                        </button>
                    </header>

                    <x-table :headers="headers" :items="inventory">
                        <template #select="{ item }">
                            <x-checkbox v-model="item.selected"></x-checkbox>
                        </template>
                        <template #issues="{item}">
                            <span v-for="(issue, index) in item.issues">
                                {{ issue.name }}
                                <span v-if="index != item.issues.length - 1">
                                    ,
                                </span>
                            </span>
                        </template>
                        <template #actions="{ item }">
                            <button
                                class="ml-3 mt-2 px-2 py-1 border border-gray-400 rounded shadow"
                                @click="onEdit(item.id)"
                            >
                                EDIT
                            </button>
                            <button
                                class="ml-3 mt-2 px-2 py-1 text-gray-800 border border-gray-400 bg-red-400 rounded shadow"
                                @click="
                                    onDelete($route('items.destroy', item.id))
                                "
                            >
                                DELETE
                            </button>
                        </template>
                    </x-table>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Table from "@/Components/Table";
import NavLink from "@/Jetstream/NavLink";
import Button from "@/Jetstream/Button";
import Checkbox from "@/Jetstream/Checkbox";

export default {
    components: {
        AppLayout,
        NavLink,
        "x-table": Table,
        "x-button": Button,
        "x-checkbox": Checkbox
    },

    props: {
        items: {
            type: Array,
            required: true
        }
    },

    created() {
        this.inventory = this.items.map(item => {
            return { ...item, selected: false };
        });
    },

    computed: {
        totalSelected() {
            return this.inventory.filter(item => item.selected).length;
        }
    },

    data: () => {
        return {
            headers: [
                { text: "Select", value: "select" },
                { text: "Supplier", value: "supplier" },
                { text: "Manufacturer", value: "manufacturer" },
                { text: "Model", value: "model" },
                { text: "Colour", value: "colour" },
                { text: "Battery", value: "battery" },
                { text: "Grade", value: "grade" },
                { text: "Issues", value: "issues" },
                { text: "IMEI", value: "imei" },
                { text: "Cost", value: "cost" },
                { text: "Amount", value: "selling_price" },
                { text: "Actions", value: "actions" }
            ],
            inventory: []
        };
    },

    methods: {
        onDelete: function(url) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.isConfirmed) {
                    axios
                        .delete(url)
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
                            if (
                                response.status >= 200 &&
                                response.status < 400
                            ) {
                                Swal.fire({
                                    title: "Deleted!",
                                    icon: "success"
                                }).then(location.reload());
                            }
                        });
                }
            });
        },
        onDeleteMultiple: function() {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.isConfirmed) {
                    const items = this.inventory.filter(item => item.selected);
                    axios
                        .delete(this.$route("items.obliterate"), {
                            data: items
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
                            if (
                                response.status >= 200 &&
                                response.status < 400
                            ) {
                                Swal.fire({
                                    title: "Deleted!",
                                    icon: "success"
                                }).then(location.reload());
                            }
                        });
                }
            });
        },
        onEdit: function(item = undefined) {
            let items = [];
            if (item === undefined) {
                items = this.inventory
                    .filter(item => item.selected)
                    .map(item => item.id)
                    .join(";");
            } else {
                items.push(item);
            }

            window.location.href = this.$route("items.edit", btoa(items));
        }
    }
};
</script>
