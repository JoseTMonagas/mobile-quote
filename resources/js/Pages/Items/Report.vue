<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Sales Report
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <form-section @submitted="onFormSubmit">
                        <template #title>
                            <strong class="p-3">Date Range</strong>
                        </template>
                        <template #form>
                            <label for="start">Start Date:</label>
                            <input
                                class="col-span-2"
                                type="date"
                                v-model="start"
                            />
                            <label for="end">End Date:</label>
                            <input
                                class="col-span-2"
                                type="date"
                                v-model="end"
                            />
                        </template>
                        <template #actions>
                            <section class="flex flex-row justify-around">
                                <x-button
                                    class="mx-2"
                                    type="reset"
                                    @click="onClickReset"
                                    >Reset</x-button
                                >
                                <x-button class="mx-2">Generate</x-button>
                            </section>
                        </template>
                    </form-section>
                </div>
            </div>
        </div>

        <div class="py-6" v-if="report.length > 0">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-x-scroll shadow-xl sm:rounded-lg">
                    <button
                        class="shadow bg-blue-300 py-1 px-2"
                        @click="onClickExport"
                    >
                        Export to excel
                    </button>
                    <x-table
                        :headers="headers"
                        :items="report"
                        :key="keyCount"
                        class="text-xs"
                    >
                    </x-table>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Table from "@/Components/Table";
import Button from "@/Jetstream/Button";
import FormSection from "@/Jetstream/FormSection";

import XLSX from "xlsx";

export default {
    components: {
        AppLayout,
        FormSection,
        "x-table": Table,
        "x-button": Button
    },

    data: () => {
        return {
            start: "",
            end: "",

            headers: [],
            report: [],
            keyCount: 0
        };
    },

    created() {
        const role = this.$page.props.user.role;
        this.headers = [
            { text: "Sale Date", value: "sold" },
            { text: "Supplier", value: "supplier" },
            { text: "Customer", value: "customer" },
            { text: "Manufacturer", value: "manufacturer" },
            { text: "Model", value: "model" },
            { text: "Colour", value: "colour" },
            { text: "Battery", value: "battery" },
            { text: "Grade", value: "grade" },
            { text: "Issues", value: "issues" },
            { text: "IMEI", value: "imei" },
            { text: "Cost", value: "cost" },
            { text: "Subtotal", value: "subtotal" },
            { text: "Total", value: "total" },
            { text: "Profit", value: "profit" }
        ];
    },

    methods: {
        onClickReset() {
            this.start = "";
            this.end = "";
            this.report = [];
        },
        onClickExport() {
            let workbook = XLSX.utils.book_new();
            workbook.Props = {
                Title: "Reports",
                CreatedDate: new Date()
            };

            workbook.SheetNames.push("Report");
            let worksheet = XLSX.utils.json_to_sheet(this.report);
            workbook.Sheets["Report"] = worksheet;

            XLSX.writeFile(workbook, "Report.xlsx");
        },
        onFormSubmit() {
            const start = this.start;
            const end = this.end;
            axios
                .post(this.$route("sales.generate_report"), { start, end })
                .catch(error => {
                    if (error.response) {
                        let textMsg = "";
                        if (error.status < 500) {
                            textMsg = "User input error";
                        } else {
                            textMsg = "Server error";
                        }
                        Swal.fire({
                            title: "An Error has ocurred!",
                            text: textMsg,
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
                        if (response.data === []) {
                            Swal.fire({
                                title: "No data",
                                icon: "warning",
                                text:
                                    "There wasn't any quote found in this date period."
                            });
                        }
                        this.report = response.data;
                        this.keyCount += 1;
                    }
                });
        }
    }
};
</script>
