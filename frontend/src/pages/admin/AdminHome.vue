<template>
  <div>
    <q-form @submit.prevent="onSubmit">
      <div class="flex justify-center">
        <div class="column">
          <q-img
            :src="store.admin.home.image"
            width="20vw"
            fit="contain"
            v-if="!changeImage"
          ></q-img>
          <q-uploader
            v-else
            url="/api/admin/home/image"
            method="post"
            auto-upload
            hide-upload-btn
            color="secondary"
            field-name="uploadedImage"
            :headers="[
              { name: 'Authorization', value: `Bearer ${store.token}` },
            ]"
            @uploaded="onUpload"
          ></q-uploader>
          <q-btn
            :label="changeImage ? 'Cancel' : 'Change Image'"
            color="primary"
            @click="changeImage = !changeImage"
          >
          </q-btn>
        </div>
      </div>

      <div class="flex justify-center">
        <q-field
          v-model="store.admin.home.caption"
          label="Caption"
          dense
          outlined
          class="q-mt-md"
          color="black"
        >
          <q-editor
            v-model="store.admin.home.caption"
            class="q-ma-sm"
          ></q-editor>
        </q-field>
      </div>

      <q-btn
        type="submit"
        label="save"
        color="primary"
        class="absolute-bottom-right q-ma-md"
      ></q-btn>
    </q-form>
  </div>
</template>

<script setup>
import { Notify } from "quasar";
import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";
import { ref } from "vue";

const store = useStore();
const changeImage = ref(false);
const tempFile = ref(null);

const onUpload = ({ xhr }) => {
  const response = JSON.parse(xhr.response);
  if (response.status == "error") {
    Notify.create({
      type: "negative",
      position: "center",
      message: response.message,
    });
    return;
  }

  tempFile.value = response.tempFile;
};

const onSubmit = async () => {
  const payload = {
    ...store.admin.home,
    changeImage: changeImage.value,
    tempFile: tempFile.value,
  };

  const response = await callApi({
    path: "/home",
    method: "put",
    payload,
    useAuth: true,
  });

  console.log({ response });
};
</script>
