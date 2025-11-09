<template>
  <q-dialog v-model="model">
    <q-card>
      <div class="flex justify-end">
        <q-btn icon="close" round flat size="sm" @click="model = false">
        </q-btn>
      </div>

      <q-form @submit.prevent="onSubmit">
        <q-card-section>
          <q-input
            type="text"
            label="Subject"
            v-model="row.subject"
            dense
            outlined
            :rules="[(v) => !!v || 'Required']"
          ></q-input>

          <q-input
            label="Date"
            dense
            outlined
            v-model="row.date"
            mask="####-##-##"
            class="q-mt-sm"
            :rules="[(v) => !!v || 'Required']"
          >
            <template v-slot:append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy
                  cover
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-date v-model="row.date" mask="YYYY-MM-DD">
                    <div class="row items-center justify-end">
                      <q-btn v-close-popup label="Close" color="primary" flat />
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>

          <q-field
            label="Contents"
            stack-label
            borderless
            :rules="[(v) => !!v || 'Required']"
            v-model="row.content"
          >
            <template v-slot:control>
              <q-editor v-model="row.content" class="full-width" />
            </template>
          </q-field>
        </q-card-section>
        <q-card-actions class="justify-end">
          <q-btn
            label="Cancel"
            type="button"
            color="negative"
            @click="model = false"
          ></q-btn>
          <q-btn label="Save" type="submit" color="primary"></q-btn>
        </q-card-actions>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { Notify } from "quasar";
import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";
const store = useStore();

const model = defineModel();
const props = defineProps(["row"]);

const onSubmit = async () => {
  const id = props.row.id;

  const path = id ? `/news/${id}` : "/news";
  const method = id ? "put" : "post";
  const payload = props.row;

  const response = await callApi({ path, method, payload, useAuth: true });

  const type = response.status == "success" ? "positive" : "negative";

  Notify.create({
    type,
    position: "center",
    message: response.message,
  });

  store.admin.news = await callApi({ path: "/news", method: "get" });

  model.value = false;
};
</script>
