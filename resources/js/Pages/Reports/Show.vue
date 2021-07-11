<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reports
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
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <button
                        class="shadow bg-blue-300 py-1 px-2"
                        @click="onClickExport"
                    >
                        Export to excel
                    </button>
                    <x-table :headers="headers" :items="report"></x-table>
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

            headers: [
                { text: "Date", value: "date" },
                { text: "Store", value: "store" },
                { text: "Location", value: "location" },
                { text: "User", value: "user" },
                { text: "Device", value: "device" },
                { text: "Base Price", value: "base_price" },
                { text: "Issues", value: "issues" },
                { text: "Value", value: "value" },
                { text: "Serial #", value: "serial_ref" },
                { text: "Internal #", value: "internal_ref" }
            ],
            report: []
        };
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

            workbook.Sheets["Report"] = worksheet;

            XLSX.writeFile(workbook, "Report.xlsx");
        },
        onFormSubmit() {
            const start = this.start;
            const end = this.end;
            axios
                .post(this.$route("reports.generate"), { start, end })
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
                    }
                });
        }
    }
};
</script>
