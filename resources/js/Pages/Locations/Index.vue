<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Locations: Index
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <nav-link
                        class="ml-3 mt-2 px-2 py-1 border border-gray-400 rounded shadow"
                        :href="$route('stores.locations.create', store.id)"
                        >CREATE NEW</nav-link
                    >
                    <x-table :headers="headers" :items="locations">
                        <template #actions="{ item }">
                            <nav-link
                                class="ml-3 mt-2 px-2 py-1 border border-gray-400 rounded shadow"
                                :href="$route('locations.usersList', item.id)"
                                >USERS</nav-link
                            >
                            <nav-link
                                class="ml-3 mt-2 px-2 py-1 border border-gray-400 rounded shadow"
                                :href="$route('locations.edit', item.id)"
                                >EDIT</nav-link
                            >
                            <button
                                class="ml-3 mt-2 px-2 py-1 text-gray-800 border border-gray-400 bg-red-400 rounded shadow"
                                @click="
                                    onDelete(
                                        $route('locations.destroy', item.id)
                                    )
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

export default {
    components: {
        AppLayout,
        NavLink,
        "x-table": Table,
        "x-button": Button
    },

    props: {
        locations: {
            type: Array,
            required: true
        },
        store: {
            type: Object,
            required: true
        }
    },

    data: () => {
        return {
            headers: [
                { text: "Name", value: "name" },
                { text: "Address", value: "address" },
                { text: "Actions", value: "actions" }
            ]
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
        }
    }
};
</script>
