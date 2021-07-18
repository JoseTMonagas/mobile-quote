<template>
    <section class="p-2">
        <header class="flex flex-row justify-between">
            <slot name="header"></slot>
            <span class="m-2">
                <label for="filter">Filter:</label>
                <x-input v-model="filter"></x-input>
            </span>
        </header>
        <table class="w-full">
            <thead class="w-full">
                <tr class="w-full border-b">
                    <th
                        class="p-2 text-left"
                        :class="`${'w-1/' + headers.length}`"
                        v-for="(header, index) in headers"
                    >
                        <button
                            class="inline-flex font-bold"
                            @click="sortTable(header.value)"
                        >
                            {{ header.text }}
                            <svg
                                v-if="
                                    sortColumn == header.value && sortDirection
                                "
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true"
                                focusable="false"
                                width="1em"
                                height="1em"
                                style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);"
                                preserveAspectRatio="xMidYMid meet"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M13 20h-2V8l-5.5 5.5l-1.42-1.42L12 4.16l7.92 7.92l-1.42 1.42L13 8v12z"
                                    fill="#626262"
                                />
                            </svg>
                            <svg
                                v-if="
                                    sortColumn == header.value && !sortDirection
                                "
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                aria-hidden="true"
                                focusable="false"
                                width="1em"
                                height="1em"
                                style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);"
                                preserveAspectRatio="xMidYMid meet"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    d="M11 4h2v12l5.5-5.5l1.42 1.42L12 19.84l-7.92-7.92L5.5 10.5L11 16V4z"
                                    fill="#626262"
                                />
                            </svg>
                        </button>
                    </th>
                </tr>
            </thead>
            <tbody class="w-full">
                <tr
                    class="w-full border-b"
                    v-if="getRows.length > 0"
                    v-for="(row, index) in getRows"
                >
                    <td
                        class="p-1 text-left"
                        v-for="(header, index) in headers"
                    >
                        <slot :name="header.value" :item="row">
                            {{ row[header.value] }}
                        </slot>
                    </td>
                </tr>
                <tr v-else></tr>
            </tbody>
        </table>
        <footer classs="flex flex-row justify-around">
            <span class="mx-1">
                <button
                    class="border px-2 py-1 mx-1"
                    v-if="currentPage > 1"
                    @click="changePage(currentPage - 1)"
                >
                    <
                </button>
                <button
                    class="border px-2 py-1 mx-1"
                    v-for="i in pageList"
                    @click="changePage(i)"
                >
                    <strong v-if="i == currentPage">{{ i }}</strong>
                    <span v-else>{{ i }}</span>
                </button>
                <button
                    class="border px-2 py-1 mx-1"
                    v-if="currentPage < numPages"
                    @click="changePage(currentPage + 1)"
                >
                    >
                </button>
            </span>
            <span class="mx-1">
                <small>
                    You're on page {{ currentPage }} of {{ numPages }} for
                    {{ items.length }} items
                </small>
            </span>
            <slot name="footer"></slot>
        </footer>
    </section>
</template>
<script type="text/javascript">
import Input from "@/Jetstream/Input";

export default {
    components: {
        "x-input": Input
    },
    props: {
        headers: {
            type: Array,
            required: true
        },
        items: {
            type: Array,
            required: true
        }
    },
    created() {
        this.rows = this.items;
    },
    watch: {
        filter: function(newFilter, oldFilter) {
            if (newFilter == "") {
                this.rows = this.items;
            } else {
                this.rows = this.items.filter(item => {
                    return JSON.stringify(item).includes(newFilter);
                });
            }
        }
    },
    computed: {
        sortArrow: function() {
            if (this.sortDirection) {
                return "mdi:arrow-up-bold";
            } else {
                return "mdi:arrow-down-bold";
            }
        },
        numPages: function() {
            return Math.ceil(this.rows.length / this.elementsPerPage);
        },
        getRows: function() {
            let start = (this.currentPage - 1) * this.elementsPerPage;
            let end = start + this.elementsPerPage;
            return this.rows.slice(start, end);
        },
        pageList: function() {
            if (this.numPages < 5) {
                return this.numPages;
            }

            if (this.currentPage < 5) {
                return this.numberList(1, 5);
            } else if (this.currentPage >= this.numPages - 5) {
                const diff = this.numPages - this.currentPage;
                return this.numberList(this.currentPage - diff, this.numPages);
            } else {
                return this.numberList(
                    this.currentPage - 2,
                    this.currentPage + 2
                );
            }
        }
    },
    data: () => {
        return {
            currentPage: 1,
            elementsPerPage: 10,
            filter: "",
            rows: [],
            sortDirection: false,
            sortColumn: ""
        };
    },
    methods: {
        sortTable: function(col) {
            if (this.sortColumn === col) {
                this.sortDirection = !this.sortDirection;
            } else {
                this.sortDirection = true;
                this.sortColumn = col;
            }

            let ascending = this.sortDirection;

            this.rows.sort((a, b) => {
                if (a[col] > b[col]) {
                    return ascending ? 1 : -1;
                } else if (a[col] < b[col]) {
                    return ascending ? -1 : 1;
                }
                return 0;
            });
        },
        changePage: function(page) {
            this.currentPage = page;
        },
        hasHeader: function(header) {
            return !!this.$slots[header];
        },
        numberList: function(start, end) {
            let list = [];
            for (let i = start; i <= end; i++) {
                list.push(i);
            }
            return list;
        }
    }
};
</script>
