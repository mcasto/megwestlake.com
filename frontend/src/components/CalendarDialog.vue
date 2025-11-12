<template>
  <q-dialog v-model="model">
    <q-card style="width: 50vw;">
      <q-form @submit.prevent="onSubmit">
        <q-card-section class="column q-gutter-y-sm">
          <q-input
            type="text"
            label="Race Name"
            dense
            outlined
            v-model="row.name"
            required
          ></q-input>

          <div>
            <q-input
              dense
              outlined
              v-model="row.date"
              mask="####-##-##"
              label="Date"
              required
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
                        <q-btn
                          v-close-popup
                          label="Close"
                          color="primary"
                          flat
                        />
                      </div>
                    </q-date>
                  </q-popup-proxy>
                </q-icon>
              </template>
            </q-input>
          </div>
          <q-input
            type="text"
            label="Distance"
            dense
            outlined
            v-model="row.distance"
          ></q-input>

          <q-input
            type="text"
            label="Results"
            dense
            outlined
            v-model="row.results"
          ></q-input>
        </q-card-section>
        <q-card-actions class="justify-end">
          <q-btn label="Cancel" color="negative" @click="model = false"></q-btn>
          <q-btn label="Save" color="positive" type="submit"></q-btn>
        </q-card-actions>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { isFuture } from "date-fns";
import { remove } from "lodash-es";
import { clone } from "lodash-es";
import { Loading, Notify } from "quasar";
import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";

const store = useStore();

const model = defineModel();
const props = defineProps(["row"]);

const onSubmit = async () => {
  Loading.show({ delay: 300 });

  const path = props.row.id ? `/calendar/${props.row.id}` : "/calendar";
  const method = props.row.id ? "put" : "post";
  const payload = clone(props.row);

  const response = await callApi({ path, method, payload, useAuth: true });

  const action = method == "put" ? "updated" : "created";
  const type = response.status == "success" ? "positive" : "negative";
  const message =
    response.status == "success"
      ? `Successfully ${action} entry`
      : `Unable to ${action.replace(/d$/, "")} entry`;

  if (response.status == "success") {
    store.admin.calendar = await callApi({ path: "/calendar", method: "get" });
  }

  Notify.create({ type, message, position: "center" });

  Loading.hide();

  model.value = false;
};
</script>
