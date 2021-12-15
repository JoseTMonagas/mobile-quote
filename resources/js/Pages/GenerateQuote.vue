<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Quote Generator
            </h2>
            <inertia-link
                :href="$route('bulkQuotes.create')"
                class="
                    inline-flex
                    items-center
                    px-4
                    py-2
                    bg-blue-800
                    border border-transparent
                    rounded-md
                    font-semibold
                    text-xs text-white
                    uppercase
                    tracking-widest
                    hover:bg-blue-700
                    active:bg-gray-900
                    focus:outline-none
                    focus:border-gray-900
                    focus:shadow-outline-gray
                    transition
                    ease-in-out
                    duration-150
                "
            >
                + Bulk Quote
            </inertia-link>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <section class="grid grid-cols-1 md:grid-cols-8 p-3">
                        <main class="col-span-8">
                            <section
                                class="flex flex-col md:mx-12 justify-around"
                            >
                                <label class="md:mt-4" for="device"
                                    >Select your device:</label
                                >
                                <v-select
                                    v-model="device"
                                    name="device"
                                    :options="devices"
                                    label="model"
                                    @input="onDeviceInput"
                                >
                                    <template v-slot:option="option">
                                        <main
                                            class="flex flex-row align-center"
                                        >
                                            <strong class="ml-3">{{
                                                `${option.brand} ${option.model}`
                                            }}</strong>
                                        </main>
                                    </template>
                                </v-select>
                                <section
                                    class="grid grid-cols-1 md:grid-cols-2 mt-4"
                                >
                                    <main>
                                        <label for="condition"
                                            >Please select the device's cosmetic
                                            condition:</label
                                        >
                                        <v-radio
                                            class="my-2"
                                            :value="condition"
                                            @change="condition = $event"
                                            name="condition"
                                            text="EXCELLENT"
                                            label="Excellent"
                                            help="The device is in Like New condition, without any signs of previous usage."
                                        ></v-radio>
                                        <v-radio
                                            class="my-2"
                                            :value="condition"
                                            @change="condition = $event"
                                            name="condition"
                                            text="GOOD"
                                            label="Good"
                                            help="The device shows some signs of normal usage, but is free of deep scratches and scuffs."
                                        ></v-radio>
                                        <v-radio
                                            class="my-2"
                                            :value="condition"
                                            @change="condition = $event"
                                            name="condition"
                                            text="ACCEPTABLE"
                                            label="Acceptable"
                                            help="The device shows heavier signs of wear on the body and/or screen."
                                        ></v-radio>
                                    </main>
                                    <aside class="mt-4 md:mt-0 flex flex-col">
                                        <label for="">
                                            Select any issue(s) the device has:
                                        </label>
                                        <v-select
                                            multiple
                                            v-model="issues"
                                            name="device"
                                            :options="issuesList"
                                            label="name"
                                        ></v-select>
                                        <small class="text-gray-500">
                                            In order to get the most accurate
                                            quote, please select any issue(s)
                                            the device has from the dropdown
                                            list above. (ie; Cracked Screen,
                                            Broken Camera etc).
                                        </small>
                                        <small class="text-gray-500">
                                            Failure to disclose any issues can
                                            result in an adjusted quote or
                                            device rejection.
                                        </small>
                                    </aside>
                                </section>
                                <span class="mt-4 border-t">
                                    <h2 class="text-2xl mt-3">$ {{ quote }}</h2>
                                </span>
                                <span class="flex justify-center mt-4">
                                    <button
                                        class="
                                            bg-gray-800
                                            text-white
                                            rounded-r-none rounded
                                            px-3
                                            py-2
                                            hover:bg-gray-300
                                            hover:text-gray-800
                                        "
                                        @click="onClickReset"
                                    >
                                        Reset
                                    </button>

                                    <button
                                        class="
                                            bg-green-800
                                            text-white
                                            rounded-r-none
                                            rounded-l-none
                                            rounded
                                            px-3
                                            py-2
                                            hover:bg-green-300
                                            hover:text-gray-800
                                        "
                                        @click="onClickGenerate"
                                    >
                                        Generate
                                    </button>

                                    <button
                                        class="
                                            bg-blue-800
                                            text-white
                                            rounded-l-none rounded
                                            px-3
                                            py-2
                                            hover:bg-blue-300
                                            hover:text-gray-800
                                        "
                                        @click="onClickConfirm"
                                    >
                                        Confirm
                                    </button>
                                </span>
                            </section>
                        </main>
                    </section>
                </div>
            </div>
        </div>
        <dialog-modal :show="dlgConfirmation">
            <template #title>
                You're generating a new quote for $ {{ quote }}
            </template>
            <template #content>
                <div class="flex flex-col">
                    <div class="inline-flex flex-row">
                        <label for="serialNumber">Serial #:</label>
                        <x-input v-model="serialNumber"></x-input>

                        <label class="ml-3" for="serialNumber"
                            >Internal #:</label
                        >
                        <x-input v-model="internalNumber"></x-input>
                    </div>
                    <div class="inline-flex flex-row items-center my-3">
                        <x-checkbox v-model="accountRemoved"></x-checkbox>
                        <label for="accountRemoved"
                            >iCloud/Android account removed *</label
                        >
                        <small v-if="!accountRemoved" class="text-red-700"
                            >You must check this box!</small
                        >
                    </div>
                    <div class="inline-flex flex-row items-center">
                        <x-checkbox v-model="factoryReset"></x-checkbox>
                        <label for="factoryReset">Factory Reset *</label>
                        <small v-if="!factoryReset" class="text-red-700"
                            >You must check this box!</small
                        >
                    </div>
                </div>
            </template>
            <template #footer>
                <div class="flex flex-col justify-center">
                    <button
                        class="
                            bg-blue-800
                            text-white
                            rounded
                            px-3
                            py-2
                            hover:bg-blue-300 hover:text-gray-800
                        "
                        @click="onClickFinish"
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
            device: "",
            condition: "",
            issuesList: [],
            issues: [],
            quote: 0,
            serialNumber: "",
            internalNumber: "",
            accountRemoved: false,
            factoryReset: false,
            dlgConfirmation: false
        };
    },

    methods: {
        getDevices() {
            axios.get(this.$route("devices.list")).then(resp => {
                this.devices = resp.data;
            });
        },
        calculateQuote() {
            const base = this.device.base_price;
            let storePrice = 0;

            if (this.device.custom_price > 0) {
                storePrice = this.device.custom_price;
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

            if (this.issues.length > 0) {
                for (const issue in this.issues) {
                    issues += this.issues[issue].pivot.deduction;
                }
            }

            let storeMargin = this.storePercent;
            if (this.device.custom_price > 0) {
                storeMargin = 0;
            }

            let preMargin = storePrice - factor - issues;
            let withMargin = preMargin * (1 - storeMargin / 100);

            const quote = Math.round(withMargin).toFixed(0);

            if (quote <= 0) {
                return 0;
            }

            return quote;
        },
        onDeviceInput() {
            this.issuesList = this.device.issues;
        },
        onClickReset() {
            this.device = "";
            this.condition = "";
            this.issues = [];
            this.quote = 0;
            this.serialNumber = "";
            this.internalNumber = "";
            this.accountRemoved = false;
            this.factoryReset = false;
        },
        onClickGenerate() {
            this.quote = this.calculateQuote();
        },
        onClickConfirm() {
            this.quote = this.calculateQuote();
            this.dlgConfirmation = true;
        },
        onClickFinish() {
            if (!this.accountRemoved || !this.factoryReset) {
                return;
            }

            const itemsPrototype = {
                device: this.device,
                quantity: 1,
                condition: this.condition,
                issues: this.issues,
                value: this.quote,
                serialNumber: this.serialNumber
            };

            const quotePrototype = {
                name: this.quoteName,
                internal_number: this.internalNumber,
                store_margin: this.storePercent,
                items: [itemsPrototype]
            };

            axios
                .post(this.$route("quotes.store"), quotePrototype)
                .then(resp => {
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
