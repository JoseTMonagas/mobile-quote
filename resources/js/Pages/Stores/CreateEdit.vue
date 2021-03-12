<template>
  <app-layout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Stores: {{ formType }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <form-section @submitted="onFormSubmit">
            <template #title>
              <strong class="p-3">{{ formType }} Store</strong>
            </template>
            <template #description>
              <nav-link class="ml-2 mt-1" :href="$route('stores.index')">
                <span
                  class="iconify"
                  data-icon="mdi:arrow-left"
                  data-inline="false"
                ></span>
                Back to index
              </nav-link>
            </template>
            <template #form>
              <label for="name">Name:</label>
              <x-input class="col-span-2" v-model="name"></x-input>

              <label for="email">Email:</label>
              <x-input class="col-span-2" v-model="email"></x-input>

              <label for="address">Address:</label>
              <x-input
                class="col-span-2"
                v-model="address"
                type="address"
              ></x-input>
            </template>
            <template #actions>
              <section class="flex flex-row justify-around">
                <x-button class="mx-2" type="reset" @click="cleanForm"
                  >Reset</x-button
                >
                <x-button class="mx-2">Save</x-button>
              </section>
            </template>
          </form-section>
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

export default {
  components: {
    AppLayout,
    NavLink,
    FormSection,
    "x-button": Button,
    "x-input": Input
  },
  props: {
    storeEdit: {
      type: Object,
      required: false,
      default: null
    }
  },
  created() {
    if (this.storeEdit !== null) {
      this.name = this.storeEdit.name;
      this.email = this.storeEdit.email;
      this.address = this.storeEdit.address;
    }
  },
  computed: {
    formType: function() {
      return this.storeEdit !== null ? "Edit" : "Create";
    },
    requestUrl: function() {
      return this.storeEdit !== null
        ? this.$route("stores.update", this.storeEdit.id)
        : this.$route("stores.store");
    }
  },
  data: () => {
    return {
      name: "",
      email: "",
      address: ""
    };
  },
  methods: {
    cleanForm() {
      this.name = "";
      this.email = "";
      this.adress = "";
    },
    onFormSubmit() {
      let formData = new FormData();
      formData.append("name", this.name);
      formData.append("address", this.adress);
      formData.append("email", this.email);

      if (this.storeEdit !== null) {
        formData.append("_method", "put");
      }

      axios
        .post(this.requestUrl, formData, {
          headers: {
            "Content-Type": "multipart/form-data"
          }
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
          if (response.status >= 200 && response.status < 400) {
            Swal.fire({
              title: "Store saved successfully",
              icon: "success",
              text: `Store Name: ${response.data.name}`
            });
            this.cleanForm();
          }
        });
    }
  }
};
</script>
