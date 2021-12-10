<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Items
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 md:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg md:p-4">
                    <main
                        class="w-full inline-flex flex-col border-b py-2"
                        v-for="item in items"
                    >
                        <section class="grid grid-cols-12 gap-4 my-1">
                            <div class="inline-flex flex-col col-span-2">
                                <label for="">Date:</label>
                                <input
                                    class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                    type="date"
                                    v-model="item.date"
                                />
                            </div>
                            <div class="inline-flex flex-col col-span-3">
                                <label for="">Model:</label>
                                <input
                                    class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                    type="text"
                                    v-model="item.model"
                                />
                            </div>
                            <div class="inline-flex flex-col col-span-3">
                                <label for="">Issues:</label>
                                <v-select
                                    multiple
                                    v-model="item.issues"
                                    name="device"
                                    :options="issues"
                                    label="name"
                                ></v-select>
                            </div>
                            <div class="inline-flex flex-col col-span-2">
                                <label for="">Cost:</label>
                                <input
                                    class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                    type="text"
                                    v-model="item.cost"
                                />
                            </div>
                            <div class="inline-flex flex-col col-span-2">
                                <label for="">Selling Price:</label>
                                <input
                                    class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                    type="text"
                                    v-model="item.selling_price"
                                />
                            </div>
                            <div class="inline-flex flex-col col-span-2">
                                <label for="">Supplier:</label>
                                <input
                                    class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                    type="text"
                                    v-model="item.supplier"
                                />
                            </div>
                            <div class="inline-flex flex-col col-span-2">
                                <label for="">Manufacturer:</label>
                                <input
                                    class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                    type="text"
                                    v-model="item.manufacturer"
                                />
                            </div>
                            <div class="inline-flex flex-col col-span-2">
                                <label for="">IMEI:</label>
                                <input
                                    class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                    type="text"
                                    v-model="item.imei"
                                />
                            </div>
                            <div class="inline-flex flex-col">
                                <label for="">Colour:</label>
                                <input
                                    class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                    type="text"
                                    v-model="item.colour"
                                />
                            </div>
                            <div class="inline-flex flex-col">
                                <label for="">Battery:</label>
                                <input
                                    class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                    type="number"
                                    min="0"
                                    max="100"
                                    v-model="item.battery"
                                />
                            </div>
                            <div class="inline-flex flex-col">
                                <label for="">Grade:</label>
                                <input
                                    class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                    type="text"
                                    v-model="item.grade"
                                />
                            </div>
                        </section>
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
        axios.get(this.$route("issues.list")).then(resp => {
            this.issues = resp.data;
        });

        if (this.editing != null && this.editing.length > 0) {
            this.items = [...this.editing];
        }
    },
    data: () => {
        return {
            issues: [],
            items: [
                {
                    id: "",
                    date: "",
                    supplier: "",
                    manufacturer: "",
                    model: "",
                    colour: "",
                    battery: "",
                    grade: "",
                    issues: [],
                    cost: "",
                    imei: "",
                    selling_price: ""
                }
            ]
        };
    },
    methods: {
        onAddMoreItemsButtonClick() {
            const itemPrototype = {
                date: "",
                supplier: "",
                manufacturer: "",
                model: "",
                colour: "",
                battery: "",
                grade: "",
                issues: [],
                cost: "",
                imei: "",
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
            let route = this.$route("items.store");
            if (this.editing != null && this.editing.length > 0) {
                route = this.$route("items.update");
            }
            axios.post(route, newItems).then(response => {
                if (response.status >= 200 && response.status < 400) {
                    location.href = this.$route("items.index");
                }
            });
        }
    }
};
</script>
