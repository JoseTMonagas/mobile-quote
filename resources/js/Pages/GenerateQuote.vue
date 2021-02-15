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
                        :options="['Canada', 'United States']"
                    >
                        <template v-slot:option="option">
                            <main class="flex flex-row align-center">
                                <img
                                    src="https://via.placeholder.com/150"
                                    alt=""
                                />
                                <strong class="ml-3">{{ option.label }}</strong>
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
                                :options="['Canada', 'United States']"
                            ></v-select>
                        </aside>
                    </section>
                    <span class="mt-4 border-t">
                        <h2 class="text-2xl mt-3">
                            $ Placeholder
                        </h2>
                    </span>
                    <span class="flex mt-4">
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
    data: () => {
        return {
            device: "",
            condition: "",
            issues: []
        };
    },
    methods: {
        onClickReset() {
            this.device = "";
            this.condition = "";
            this.issues = [];
        }
    }
};
</script>
