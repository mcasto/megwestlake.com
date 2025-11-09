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
          ></q-input>

          <q-input
            dense
            outlined
            v-model="row.date"
            mask="####-##-##"
            class="q-mt-sm"
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

          <q-field label="Contents" stack-label borderless>
            <template v-slot:control>
              <q-editor v-model="row.content" class="full-width" />
            </template>
          </q-field>
        </q-card-section>
        <q-card-actions class="justify-end">
          <q-btn label="Save" type="submit" color="primary"></q-btn>
        </q-card-actions>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { onUpdated, ref } from "vue";

const model = defineModel();
const props = defineProps(["row"]);

const onSubmit = async () => {
  console.log({ payload: props.row });
};
</script>
