<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bulk Quote Generator
            </h2>
            <inertia-link
                :href="$route('quotes.create')"
                class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
            >
                + Quote
            </inertia-link>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg md:p-4">
                    <main
                        class="w-full grid grid-cols-8 gap-4 my-4"
                        v-for="quote in quoteStack"
                    >
                        <div class="col-span-2">
                            <label class="md:mt-4" for="device">Device:</label>
                            <v-select
                                v-model="quote.device"
                                name="device"
                                :options="devices"
                                label="model"
                            >
                                <template v-slot:option="option">
                                    <main class="flex flex-row align-center">
                                        <strong class="ml-3">{{
                                            `${option.brand} ${option.model}`
                                        }}</strong>
                                    </main>
                                </template>
                            </v-select>
                        </div>
                        <div class="col-span-2">
                            <label for="quantity">Quantity:</label>
                            <x-input v-model="quote.quantity"></x-input>
                        </div>
                        <div class="col-span-2">
                            <label for="condition">Avg condition:</label>
                            <v-select
                                v-model="quote.condition"
                                name="condition"
                                :options="['Excellent', 'Good', 'Acceptable']"
                                label="condition"
                            >
                            </v-select>
                        </div>
                        <div class="col-span-2">
                            <label for="">
                                Issues:
                            </label>
                            <v-select
                                multiple
                                v-model="quote.issues"
                                name="device"
                                :options="issuesList(quote.device)"
                                label="name"
                            ></v-select>
                        </div>
                    </main>
                    <section>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                            @click="onAddMoreDevicesButtonClick"
                        >
                            + Add More Devices
                        </button>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-red-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                            @click="onClickReset"
                        >
                            Reset
                        </button>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-green-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                            @click="onConfirmDialogClick"
                        >
                            Confirm
                        </button>
                    </section>
                </div>
            </div>
        </div>
        <dialog-modal :show="dlgConfirmation">
            <template #title>
                You're generating a new quote for $ {{ quoteTotal }}
            </template>
            <template #content>
                <header class="grid grid-cols-3 gap-4">
                    <div class="col-span-1">
                        <label for="name">Quote Name:</label>
                        <x-input v-model="quoteName"></x-input>
                    </div>
                    <div class="col-span-1">
                        <label for="internalRef">Internal Ref:</label>
                        <x-input v-model="internalNumber"></x-input>
                    </div>
                    <div class="col-span-1">
                        <label for="storeMargin">Store Margin %:</label>
                        <x-input v-model="storePercent"></x-input>
                    </div>
                </header>
                <table class="min-w-full">
                    <thead class="min-w-full">
                        <tr class="min-w-full border-b">
                            <th class="p-2 text-left w-1/3">Device</th>
                            <th class="p-2 text-left w-1/3">
                                IMEI/Serial (optional)
                            </th>
                            <th class="p-2 text-left w-1/3">Quote ($)</th>
                        </tr>
                    </thead>
                    <tbody class="min-w-full">
                        <template v-if="quotes.length > 0">
                            <tr
                                v-for="quote in quotes"
                                class="min-w-full border-b"
                            >
                                <td class="p-1 text-left">
                                    {{ quote.device.model }}
                                </td>
                                <td class="p-1 text-left">
                                    <x-input
                                        v-model="quote.serialNumber"
                                    ></x-input>
                                </td>
                                <td class="p-1 text-left">
                                    {{ calculateQuote(quote) }}
                                </td>
                            </tr>
                        </template>
                        <template v-else>
                            <tr>
                                <td colspan="3">
                                    No devices added to this quote!
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
                <footer class="float-right my-2 p-3">
                    Total: ${{ quoteTotal }}
                </footer>
            </template>
            <template #footer>
                <div class="flex flex-col justify-center">
                    <button
                        class="bg-blue-800 text-white rounded px-3 py-2 hover:bg-blue-300 hover:text-gray-800"
                        @click="onConfirmClick()"
                    >
                        Confirm
                    </button>
                </div>
            </template>
        </dialog-modal>
    </app-layout>
</template>
<script>
import AppLayout from "@/Layouts/AppLayout";
import vSelect from "vue-select";
import Input from "@/Jetstream/Input";
import Checkbox from "@/Jetstream/Checkbox";
import DialogModal from "@/Jetstream/DialogModal.vue";
import "vue-select/dist/vue-select.css";

import Radio from "../Partials/Radio.vue";

export default {
    components: {
        AppLayout,
        DialogModal,
        "x-input": Input,
        "x-checkbox": Checkbox,
        "v-select": vSelect,
        "v-radio": Radio
    },

    created() {
        this.getDevices();
    },

    props: {
        storePercent: {
            type: Number,
            default: 0,
            required: true
        }
    },

    data: () => {
        return {
            devices: [],
            quoteStack: [],
            quotes: [],
            internalNumber: "",
            dlgConfirmation: false,
            quoteName: ""
        };
    },

    computed: {
        quoteTotal() {
            let total = 0;
            for (const quote of this.quotes) {
                total += parseFloat(quote.value);
            }
            return total;
        }
    },

    methods: {
        getDevices() {
            axios.get(this.$route("devices.list")).then(resp => {
                this.devices = resp.data;
            });
        },
        onClickReset() {
            this.quoteStack = [];
            this.quoteName = "";
            this.internalNumber = "";
        },
        calculateQuote(rowQuote) {
            const base = rowQuote.device.base_price;
            let storePrice = 0;

            if (rowQuote.device.custom_price > 0) {
                storePrice = rowQuote.device.custom_price;
            }

            if (storePrice <= 0) {
                storePrice = base;
            }

            let factor = 0;
            let issues = 0;
            if (
                ["EXCELLENT", "GOOD", "ACCEPTABLE", "BROKEN"].includes(
                    this.condition
                )
            ) {
                const condition = this.condition.toLowerCase();
                const index = `${condition}_factor`;
                if (Number.isFinite(this.device[index])) {
                    factor = this.device[index];
                }
            }

            if (rowQuote.issues.length > 0) {
                for (const issue in this.issues) {
                    issues += this.issues[issue].pivot.deduction;
                }
            }

            let storeMargin = this.storePercent;
            if (rowQuote.device.custom_price > 0) {
                storeMargin = 0;
            }

            let preMargin = storePrice - factor - issues;
            let withMargin = preMargin * (1 - storeMargin / 100);

            let quote = Math.round(withMargin).toFixed(0);

            if (quote <= 0) {
                quote = 0;
            }

            rowQuote.value = parseFloat(quote);
            return quote;
        },
        issuesList(device) {
            if (device !== undefined) {
                return device.issues;
            }
            return [];
        },
        onAddMoreDevicesButtonClick() {
            const quotePrototype = {
                device: undefined,
                quantity: 1,
                condition: undefined,
                issues: [],
                value: 0,
                serialNumber: ""
            };

            this.quoteStack.push(quotePrototype);
        },
        onConfirmDialogClick() {
            for (const quote of this.quoteStack) {
                for (let i = 0; i < quote.quantity; i++) {
                    this.quotes.push({
                        ...quote
                    });
                }
            }
            this.dlgConfirmation = true;
        },
        onConfirmClick() {
            const bulkQuote = {
                name: this.quoteName,
                internal_number: this.internalNumber,
                store_margin: this.storePercent,
                items: this.quotes
            };

            axios.post(this.$route("quotes.store"), bulkQuote).then(resp => {
                const quote_id = resp.data.id;
                if (Number.isInteger(quote_id) && quote_id > 0) {
                    window.open(this.$route("quotes.receipt", quote_id));
                }
            });

            this.onClickReset();
            this.dlgConfirmation = false;
        }
    }
};
</script>
