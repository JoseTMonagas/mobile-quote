<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <p class="p-5">Hello, {{ $page.props.user.name }}</p>
                </div>
                <div class="grid grid-cols-4 gap-8 mt-4">
                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col items-center py-2"
                    >
                        <strong>Devices in inventory</strong>
                        <span>{{ devicesInInventory }}</span>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col items-center py-2"
                    >
                        <strong>Devices Added this Month</strong>
                        <span>{{ tradesThisMonth }}</span>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col items-center py-2"
                    >
                        <strong>Devices Sold this Month</strong>
                        <span>{{ soldThisMonth }}</span>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col items-center py-2"
                    >
                        <strong>Cost of Goods Sold</strong>
                        <span>{{ costSoldThisMonth }}</span>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col items-center py-2"
                    >
                        <strong>Inventory Value ($)</strong>
                        <span>{{ inventoryValue }}</span>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col items-center py-2"
                    >
                        <strong>Est. Sale Value of Inventory ($)</strong>
                        <span>{{ saleValue }}</span>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col items-center py-2"
                    >
                        <strong>Revenue this Month ($)</strong>
                        <span>{{ soldValueThisMonth }}</span>
                    </div>

                    <div
                        class="bg-white overflow-hidden shadow-xl sm:rounded-lg flex flex-col items-center py-2"
                    >
                        <strong>Profit This Month ($)</strong>
                        <span>{{ profitThisMonth }}</span>
                    </div>
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
        devicesInInventory: {
            type: Number,
            required: true
        },
        tradesThisMonth: {
            type: Number,
            required: true
        },
        soldThisMonth: {
            type: Number,
            required: true
        },
        costSoldThisMonth: {
            type: Number,
            required: true
        },
        inventoryValue: {
            type: Number,
            required: true
        },
        saleValue: {
            type: Number,
            required: true
        },
        soldValueThisMonth: {
            type: Number,
            required: true
        },
        profitThisMonth: {
            type: Number,
            required: true
        }
    }
};
</script>
