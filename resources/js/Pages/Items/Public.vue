<template>
    <public-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Items: Index
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <x-table :headers="headers" :items="inventory">
                        <template #select="{ item }">
                            <x-checkbox v-model="item.selected"></x-checkbox>
                        </template>
                        <template #battery="{ item }">
                            {{ item.battery }} %
                        </template>
                        <template #selling_price="{ item }">
                            $ {{ item.selling_price }}
                        </template>
                        <template #issues="{item}">
                            <span v-for="(issue, index) in item.issues">
                                {{ issue.name }}
                                <span v-if="index != item.issues.length - 1">
                                    ,
                                </span>
                            </span>
                        </template>
                    </x-table>
                </div>
            </div>
        </div>
    </public-layout>
</template>

<script>
import PublicLayout from "@/Layouts/PublicLayout";
import Table from "@/Components/Table";
import NavLink from "@/Jetstream/NavLink";
import Button from "@/Jetstream/Button";
import Checkbox from "@/Jetstream/Checkbox";
import DialogModal from "@/Jetstream/DialogModal";

export default {
    components: {
        PublicLayout,
        NavLink,
        DialogModal,
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

    data: () => {
        return {
            headers: [
                { text: "Manufacturer", value: "manufacturer" },
                { text: "Model", value: "model" },
                { text: "Colour", value: "colour" },
                { text: "Battery", value: "battery" },
                { text: "Grade", value: "grade" },
                { text: "Issues", value: "issues" },
                { text: "IMEI", value: "imei" },
                { text: "Price", value: "selling_price" }
            ],
            inventory: []
        };
    }
};
</script>
