<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Items
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 md:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg md:p-4"
                >
                    <main
                        class="w-full inline-flex flex-row flex-wrap my-1 pb-2 border-b"
                        v-for="item in items"
                    >
                        <div class="inline-flex flex-col w-3/12">
                            <label class="md:mt-4" for="device">Device:</label>
                            <v-select
                                v-model="item.device"
                                :options="devices"
                                label="model"
                            >
                                <template v-slot:option="option">
                                    <main class="flex flex-row align-center">
                                        <strong class="ml-3">
                                            {{ option.brand }}
                                            {{ option.model }}
                                        </strong>
                                    </main>
                                </template>
                            </v-select>
                        </div>
                        <div class="inline-flex flex-col w-3/12 mx-2">
                            <label class="md:mt-4" for="issues">Issues:</label>
                            <v-select
                                v-model="item.issues"
                                :options="issuesList(item.device)"
                                label="name"
                            >
                            </v-select>
                        </div>
                        <div class="inline-flex flex-col w-2/12">
                            <label class="md:mt-4" for="cost">Cost:</label>
                            <input
                                class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                type="number"
                                v-model="item.cost"
                            />
                        </div>
                        <div class="inline-flex flex-col w-2/12 mx-2 ">
                            <label class="md:mt-4" for="selling_price"
                                >Selling Price:</label
                            >
                            <input
                                class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                type="number"
                                v-model="item.selling_price"
                            />
                        </div>
                        <div class="inline-flex flex-col w-2/12">
                            <label class="md:mt-4" for="imei">IMEI:</label>
                            <input
                                class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                type="text"
                                v-model="item.imei"
                            />
                        </div>
                        <div class="inline-flex flex-col w-1/12 mx-2">
                            <label class="md:mt-4" for="colour">Colour:</label>
                            <input
                                class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                type="text"
                                v-model="item.colour"
                            />
                        </div>
                        <div class="inline-flex flex-col w-1/12 mx-2">
                            <label class="md:mt-4" for="battery"
                                >Battery %:</label
                            >
                            <input
                                class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                type="number"
                                min="0"
                                max="100"
                                v-model="item.battery"
                            />
                        </div>
                        <div class="inline-flex flex-col w-1/12">
                            <label class="md:mt-4" for="grade">Grade:</label>
                            <input
                                class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                type="text"
                                v-model="item.grade"
                            />
                        </div>
                        <div class="inline-flex flex-col w-2/12 ml-2">
                            <label class="md:mt-4" for="date">Date:</label>
                            <input
                                class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                type="date"
                                v-model="item.date"
                            />
                        </div>
                    </main>
                    <section>
                        <button
                            class="inline-flex items-center px-4 py-2 bg-blue-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
                            @click="onAddMoreItemsButtonClick"
                        >
                            + Add More Items
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
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import NavLink from "@/Jetstream/NavLink";
import FormSection from "@/Jetstream/FormSection";
import Button from "@/Jetstream/Button";
import Input from "@/Jetstream/Input";

import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

export default {
    components: {
        AppLayout,
        NavLink,
        FormSection,
        "x-button": Button,
        "v-select": vSelect,
        "x-input": Input
    },
    props: {
        editing: {
            type: Array,
            required: false,
            default: null
        }
    },
    created() {
        this.getDevices();
    },
    data: () => {
        return {
            devices: [],
            items: [
                {
                    device: undefined,
                    issues: [],
                    colour: "",
                    battery: "",
                    grade: "",
                    cost: "",
                    imei: "",
                    date: "",
                    selling_price: ""
                }
            ]
        };
    },
    methods: {
        issuesList(device) {
            if (device !== undefined) {
                return device.issues;
            }
            return [];
        },
        onAddMoreItemsButtonClick() {
            const itemPrototype = {
                device: undefined,
                issues: [],
                colour: "",
                battery: "",
                grade: "",
                cost: "",
                imei: "",
                date: new Date().toLocaleDateString(),
                selling_price: ""
            };

            this.items.push(itemPrototype);
        },
        onClickReset() {
            this.items = [];
        },
        onConfirmDialogClick() {
            const newItems = {
                date: new Date(),
                items: this.items
            };
            axios.post(this.$route("items.store"), newItems).then(resp => {
                console.log({ resp });
            });
        },
        getDevices() {
            axios.get(this.$route("devices.list")).then(resp => {
                this.devices = resp.data;
            });
        }
    }
};
</script>
