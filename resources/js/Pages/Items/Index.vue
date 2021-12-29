<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Items: Index
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <header
                        class="inline-flex flex-row justify-between items-center w-full px-4 my-2"
                    >
                        <nav-link
                            class="ml-3 mt-2 px-2 py-1 border border-gray-400 rounded shadow"
                            :href="$route('items.create')"
                            >CREATE NEW</nav-link
                        >
                        <span> You have selected: {{ totalSelected }} </span>
                        <button
                            class="ml-3 mt-2 px-2 py-1 text-gray-800 border border-gray-400 bg-green-400 rounded shadow"
                            @click="onSell()"
                        >
                            SELL SELECTED
                        </button>
                        <button
                            class="ml-3 mt-2 px-2 py-1 border border-gray-400 rounded shadow"
                            @click="onEdit()"
                        >
                            EDIT SELECTED
                        </button>
                        <button
                            class="ml-3 mt-2 px-2 py-1 text-gray-800 border border-gray-400 bg-red-400 rounded shadow"
                            @click="onDeleteMultiple()"
                        >
                            DELETE SELECTED
                        </button>
                    </header>

                    <x-table :headers="headers" :items="inventory">
                        <template #select="{ item }">
                            <x-checkbox v-model="item.selected"></x-checkbox>
                        </template>
                        <template #battery="{ item }">
                            {{ item.battery }} %
                        </template>
                        <template #cost="{ item }">
                            $ {{ item.cost }}
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
                        <template #actions="{ item }">
                            <a
                                class="ml-3 mt-2 px-2 py-1 border border-gray-400 rounded shadow"
                                :href="$route('items.label', item.id)"
                            >
                                LABEL
                            </a>
                        </template>
                    </x-table>
                </div>
            </div>
        </div>
        <dialog-modal :show="dlgConfirmation">
            <template #title>
                <button @click="dlgConfirmation = !dlgConfirmation">
                    &times;
                </button>
            </template>
            <template #content>
                <header class="grid grid-cols-8 gap-4">
                    <div class="col-span-2">
                        <label for="date">Date:</label>
                        <input
                            class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                            type="date"
                            v-model="saleDate"
                        />
                    </div>
                    <div class="col-span-1">
                        <label for="discount">Discount:</label>
                        <input
                            class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                            type="number"
                            min="0"
                            v-model="discount"
                        />
                    </div>
                    <div class="col-span-1">
                        <label for="tax">Tax %:</label>
                        <input
                            class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                            type="number"
                            min="0"
                            max="100"
                            v-model="tax"
                        />
                    </div>
                    <div class="col-span-4">
                        <label for="customer">Customer:</label>
                        <input
                            class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                            type="text"
                            v-model="customer"
                        />
                    </div>
                </header>
                <table class="min-w-full">
                    <thead class="min-w-full bg-white border-b">
                        <tr>
                            <th
                                class="text-sm font-medium text-gray-900 px-6 py-4 text-left w-2/12"
                            >
                                Device
                            </th>
                            <th
                                class="text-sm font-medium text-gray-900 px-6 py-4 text-left w-2/12"
                            >
                                Issues
                            </th>
                            <th
                                class="text-sm font-medium text-gray-900 px-6 py-4 text-left w-2/12"
                            >
                                IMEI
                            </th>
                            <th
                                class="text-sm font-medium text-gray-900 px-6 py-4 text-left w-3/12"
                            >
                                Selling Price
                            </th>
                            <th
                                class="text-sm font-medium text-gray-900 px-6 py-4 text-right w-1/12"
                            >
                                Discount
                            </th>
                            <th
                                class="text-sm font-medium text-gray-900 px-6 py-4 text-right w-1/12"
                            >
                                Tax
                            </th>
                            <th
                                class="text-sm font-medium text-gray-900 px-6 py-4 text-right w-1/12"
                            >
                                Subtotal
                            </th>
                        </tr>
                    </thead>
                    <tbody class="min-w-full">
                        <template v-if="saleItems.length > 0">
                            <tr v-for="item in saleItems" class="border-b">
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-left w-2/12"
                                >
                                    {{ item.model }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-left w-2/12"
                                >
                                    {{ item.issues }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-left w-2/12"
                                >
                                    {{ item.imei }}
                                </td>
                                <td class="whitespace-nowrap text-left w-3/12">
                                    <input
                                        class="px-2 py-1 placeholder-gray-400 text-gray-600 relative bg-white rounded text-sm border border-gray-400 outline-none focus:outline-none w-full"
                                        type="number"
                                        v-model="item.selling_price"
                                    />
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right w-1/12"
                                >
                                    {{ discountItem(item) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right w-1/12"
                                >
                                    {{ taxItem(item) }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right w-1/12"
                                >
                                    {{ subtotalItem(item) }}
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
                <footer class="inline-flex flex-col w-full text-right mt-2">
                    <span class="mt-1 border-t"> Total: $ {{ total }}</span>
                </footer>
            </template>
            <template #footer>
                <div class="flex flex-row justify-around">
                    <button
                        class="bg-gray-800 text-white rounded px-3 py-2 hover:bg-gray-300 hover:text-gray-800"
                        @click="onSellCancel()"
                    >
                        CANCEL
                    </button>
                    <button
                        class="bg-blue-800 text-white rounded px-3 py-2 hover:bg-blue-300 hover:text-gray-800"
                        @click="onSellConfirm()"
                    >
                        CONFIRM
                    </button>
                </div>
            </template>
        </dialog-modal>
    </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Table from "@/Components/Table";
import NavLink from "@/Jetstream/NavLink";
import Button from "@/Jetstream/Button";
import Checkbox from "@/Jetstream/Checkbox";
import DialogModal from "@/Jetstream/DialogModal";

export default {
    components: {
        AppLayout,
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

    computed: {
        totalSelected() {
            return this.inventory.filter(item => item.selected).length;
        },
        total() {
            if (this.saleItems.length > 0) {
                let total = 0;
                for (const item of this.saleItems) {
                    total += this.subtotalItem(item);
                }
                return total;
            }
            return 0;
        }
    },

    data: () => {
        return {
            headers: [
                { text: "Select", value: "select" },
                { text: "Supplier", value: "supplier" },
                { text: "Manufacturer", value: "manufacturer" },
                { text: "Model", value: "model" },
                { text: "Colour", value: "colour" },
                { text: "Battery", value: "battery" },
                { text: "Grade", value: "grade" },
                { text: "Issues", value: "issues" },
                { text: "IMEI", value: "imei" },
                { text: "Cost", value: "cost" },
                { text: "Est. Sale", value: "selling_price" },
                { text: "Actions", value: "actions" }
            ],
            inventory: [],
            saleItems: {},
            dlgConfirmation: false,
            saleDate: "",
            discount: 0,
            tax: 0,
            customer: ""
        };
    },

    methods: {
        onDelete: function(url) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.isConfirmed) {
                    axios
                        .delete(url)
                        .catch(error => {
                            if (error.response) {
                                // The request was made and the server responded with a status code
                                // that falls out of the range of 2xx
                                Swal.fire({
                                    title: "An Error has ocurred!",
                                    text: error.response.data,
                                    icon: "error"
                                });
                            } else if (error.request) {
                                // The request was made but no response was received
                                // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                                // http.ClientRequest in node.js
                                Swal.fire({
                                    title: "An Error has ocurred!",
                                    text: error.request,
                                    icon: "error"
                                });
                            } else {
                                // Something happened in setting up the request that triggered an Error
                                Swal.fire({
                                    title: "An Error has ocurred!",
                                    text: error.message,
                                    icon: "error"
                                });
                            }
                        })
                        .then(response => {
                            if (
                                response.status >= 200 &&
                                response.status < 400
                            ) {
                                Swal.fire({
                                    title: "Deleted!",
                                    icon: "success"
                                }).then(location.reload());
                            }
                        });
                }
            });
        },
        onDeleteMultiple: function() {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then(result => {
                if (result.isConfirmed) {
                    const items = this.inventory.filter(item => item.selected);
                    axios
                        .delete(this.$route("items.obliterate"), {
                            data: items
                        })
                        .catch(error => {
                            if (error.response) {
                                // The request was made and the server responded with a status code
                                // that falls out of the range of 2xx
                                Swal.fire({
                                    title: "An Error has ocurred!",
                                    text: error.response.data,
                                    icon: "error"
                                });
                            } else if (error.request) {
                                // The request was made but no response was received
                                // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                                // http.ClientRequest in node.js
                                Swal.fire({
                                    title: "An Error has ocurred!",
                                    text: error.request,
                                    icon: "error"
                                });
                            } else {
                                // Something happened in setting up the request that triggered an Error
                                Swal.fire({
                                    title: "An Error has ocurred!",
                                    text: error.message,
                                    icon: "error"
                                });
                            }
                        })
                        .then(response => {
                            if (
                                response.status >= 200 &&
                                response.status < 400
                            ) {
                                Swal.fire({
                                    title: "Deleted!",
                                    icon: "success"
                                }).then(location.reload());
                            }
                        });
                }
            });
        },
        onEdit: function(item = undefined) {
            let items = [];
            if (item === undefined) {
                items = this.inventory
                    .filter(item => item.selected)
                    .map(item => item.id)
                    .join(";");
            } else {
                items.push(item);
            }

            window.location.href = this.$route("items.edit", btoa(items));
        },
        onSell: function(item = undefined) {
            let items = [];
            if (item === undefined) {
                items = this.inventory.filter(item => item.selected);
            } else {
                items.push(item);
            }
            this.saleItems = items;
            this.dlgConfirmation = true;
        },
        onSellConfirm: function() {
            let sale = [];
            for (const item of this.saleItems) {
                sale.push({
                    ...item,
                    sold: this.saleDate,
                    customer: this.customer,
                    discount: this.discountItem(item),
                    tax: this.taxItem(item),
                    subtotal: this.subtotalItem(item),
                    profit: this.subtotalItem(item) - item.cost
                });
            }
            axios
                .post(this.$route("sales.store"), sale)
                .catch(error => {
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        Swal.fire({
                            title: "An Error has ocurred!",
                            text: error.response.data,
                            icon: "error"
                        });
                    } else if (error.request) {
                        // The request was made but no response was received
                        // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                        // http.ClientRequest in node.js
                        Swal.fire({
                            title: "An Error has ocurred!",
                            text: error.request,
                            icon: "error"
                        });
                    } else {
                        // Something happened in setting up the request that triggered an Error
                        Swal.fire({
                            title: "An Error has ocurred!",
                            text: error.message,
                            icon: "error"
                        });
                    }
                })
                .then(response => {
                    if (response.status >= 200 && response.status < 400) {
                        let url = decodeURI(response.data);
                        window.open(url);
                        this.onSellCancel();
                    }
                });
        },
        discountItem(item) {
            let discount = item.selling_price - this.discount;
            if (discount <= 0) return 0;
            return discount;
        },
        taxItem(item) {
            return Math.round(this.discountItem(item) * (this.tax / 100));
        },
        subtotalItem(item) {
            return this.discountItem(item) + this.taxItem(item);
        },
        onSellCancel() {
            this.saleDate = "";
            this.discount = 0;
            this.tax = 0;
            this.customer = "";
            this.dlgConfirmation = false;
        }
    }
};
</script>
