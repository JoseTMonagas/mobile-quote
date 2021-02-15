<template>
    <main class="flex flex-col">
        <section class="flex flex-row items-center">
            <input
                v-model="inputVal"
                class="mr-3"
                :name="name"
                type="radio"
                :value="text"
            />
            <label>{{ `${label ? label : text}` }}</label>
        </section>
        <small v-if="help" class="text-gray-500">{{ help }}</small>
    </main>
</template>
<script>
export default {
    props: {
        value: {
            required: true
        },
        text: {
            type: String,
            required: true
        },
        label: {
            type: String,
            required: false
        },
        name: {
            type: String,
            required: true
        },
        help: {
            type: String,
            required: false
        }
    },
    computed: {
        inputVal: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit("change", val);
            }
        }
    }
};
</script>
