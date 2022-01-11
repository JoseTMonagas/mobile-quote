<template>
    <public-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Items: Index
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <header
                        class="inline-flex flex-row justify-between items-center w-full px-4 my-2"
                    >
                        <button
                            class="ml-3 mt-2 px-2 py-1 text-gray-800 border border-gray-400 bg-blue-400 rounded shadow"
                            @click="onClickExport()"
                        >
                            EXPORT
                        </button>
                    </header>
                    <x-table
                        :headers="headers"
                        :items="inventory"
                        class="font-sm"
                    >
                        <template #select="{ item }">
                            <x-checkbox v-model="item.selected"></x-checkbox>
                        </template>
                        <template #battery="{ item }">
                            {{ item.battery }} %
                        </template>
                        <template #selling_price="{ item }">
                            $ {{ item.selling_price }}
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
    },

    methods: {
        onClickExport() {
            let workbook = XLSX.utils.book_new();
            workbook.Props = {
                Title: "Items",
                CreatedDate: new Date()
            };

            workbook.SheetNames.push("Items");
            const items = this.iventory.map(item => {
                return {
                    manufacturer: item.manufacturer,
                    model: item.model,
                    colour: item.colour,
                    battery: `${item.battery} %`,
                    grade: item.grade,
                    issues: item.issues,
                    imei: item.imei,
                    price: item.selling_price
                };
            });
            let worksheet = XLSX.utils.json_to_sheet(items);
            workbook.Sheets["Items"] = worksheet;

            XLSX.writeFile(workbook, "Items.xlsx");
        }
    }
};
</script>
