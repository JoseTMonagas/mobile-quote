<template>
    <div>
        <header class="bg-gray-800 md:py-5">lorem ipsum</header>
        <section class="grid grid-cols-1 md:grid-cols-8 p-3">
            <main class="col-span-6">
                <section class="flex flex-col md:mx-12 justify-around">
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
                            <main class="flex flex-row align-center">
                                <img :src="option.image" alt="" class="w-40" />
                                <strong class="ml-3">{{
                                    option.brand + option.model
                                }}</strong>
                            </main>
                        </template>
                    </v-select>
                    <section class="grid grid-cols-1 md:grid-cols-2 mt-4">
                        <main>
                            <label for="condition"
                                >Select your device's condition:</label
                            >
                            <v-radio
                                class="my-2"
                                :value="condition"
                                @change="condition = $event"
                                name="condition"
                                text="EXCELLENT"
                                label="Excellent"
                                help="The device is as good as new"
                            ></v-radio>
                            <v-radio
                                class="my-2"
                                :value="condition"
                                @change="condition = $event"
                                name="condition"
                                text="GOOD"
                                label="Good"
                            ></v-radio>
                            <v-radio
                                class="my-2"
                                :value="condition"
                                @change="condition = $event"
                                name="condition"
                                text="ACCEPTABLE"
                                label="Acceptable"
                            ></v-radio>
                            <v-radio
                                class="my-2"
                                :value="condition"
                                @change="condition = $event"
                                name="condition"
                                text="BROKEN"
                                label="Broken"
                            ></v-radio>
                        </main>
                        <aside
                            class="mt-4 md:mt-0"
                            v-if="condition == 'BROKEN'"
                        >
                            <label for="">Select your device's issues:</label>
                            <v-select
                                multiple
                                v-model="issues"
                                name="device"
                                :options="issuesList"
                                label="name"
                            ></v-select>
                        </aside>
                    </section>
                    <span class="mt-4 border-t">
                        <h2 class="text-2xl mt-3">$ {{ quote }}</h2>
                    </span>
                    <span class="flex mt-4">
                        <button
                            class="bg-green-800 text-white rounded px-3 py-2"
                            @click="onClickGenerate"
                        >
                            Generate
                        </button>

                        <button
                            class="bg-gray-800 text-white rounded px-3 py-2"
                            @click="onClickReset"
                        >
                            Reset
                        </button>
                    </span>
                </section>
            </main>
            <aside class="hidden md:block col-span-2">
                Quick Reports
            </aside>
        </section>
    </div>
</template>
<script>
import vSelect from "vue-select";
import "vue-select/dist/vue-select.css";

import Radio from "../Partials/Radio.vue";

export default {
    components: {
        "v-select": vSelect,
        "v-radio": Radio
    },
    created() {
        this.getDevices();
    },
    data: () => {
        return {
            devices: [],
            device: "",
            condition: "",
            issuesList: [],
            issues: [],
            quote: 0
        };
    },
    methods: {
        getDevices() {
            axios.get(this.$route("devices.list")).then(resp => {
                this.devices = resp.data;
            });
        },
        calculateQuote() {
            let base = this.device.base_price;
            if (this.device.store_price > 0) {
                base = this.device.store_price;
            }
            let factor = 0;
            let issues = 0;
            switch (this.condition) {
                case "EXCELLENT":
                    factor = this.device.excellent_factor;
                    break;
                case "GOOD":
                    factor = this.device.good_factor;
                    break;
                case "ACCEPTABLE":
                    factor = this.device.acceptable_factor;
                    break;
                case "BROKEN":
                    factor = this.device.broken_factor;
                    issues = this.issues.reduce(issue => {
                        return issue.pivot.deduction;
                    });
                    break;
            }

            return base - factor - issues;
        },
        onDeviceInput() {
            this.issuesList = this.device.issues;
        },
        onClickReset() {
            this.device = "";
            this.condition = "";
            this.issues = [];
            this.quote = 0;
        },
        onClickGenerate() {
            this.quote = this.calculateQuote();
            const device_id = this.device.id;
            const condition = this.condition;
            const issues = this.issues;
            const value = this.quote;
            axios
                .post(this.$route("quote.generate"), {
                    device_id,
                    condition,
                    issues,
                    value
                })
                .then(resp => console.log(resp));
        }
    }
};
</script>
