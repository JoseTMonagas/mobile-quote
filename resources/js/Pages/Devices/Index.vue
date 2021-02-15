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
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Company</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(index, device) in devices">
                                <td>{{ index }}</td>
                                <td>{{ device.name }}</td>
                                <td>{{ device.company }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
    components: {
        AppLayout
    },
    props: {
        devices: {
            type: Array,
            required: true
        },
        createUrl: {
            type: String,
            required: true
        }
    }
};
</script>
