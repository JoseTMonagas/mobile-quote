<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Devices: Index
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <nav-link
                        class="ml-3 mt-2 border border-gray-400 rounded shadow"
                        :href="$route('device.create')"
                        >CREATE NEW</nav-link
                    >
                    <x-table :headers="headers" :items="devices">
                        <template #actions="{ item }">
                            <nav-link
                                class="ml-3 mt-2 border border-gray-400 rounded shadow"
                                :href="$route('device.edit', item.id)"
                                >EDIT</nav-link
                            >
                            <nav-link
                                class="ml-3 mt-2 border border-gray-400 bg-red-300 rounded shadow"
                                :href="$route('device.destroy', item.id)"
                                >DELETE</nav-link
                            >
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

export default {
    components: {
        AppLayout,
        NavLink,
        "x-table": Table
    },
    props: {
        devices: {
            type: Array,
            required: true
        }
    },
    data: () => {
        return {
            headers: [
                { text: "Name", value: "name" },
                { text: "Company", value: "company" },
                { text: "Actions", value: "actions" }
            ]
        };
    },
    methods: {
        log: function(a) {
            console.log(a);
        }
    }
};
</script>
