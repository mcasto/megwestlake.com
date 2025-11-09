<template>
  <div>
    <q-table :rows="store.admin.news" grid>
      <template #top-right>
        <q-btn
          icon="add"
          round
          color="primary"
          size="sm"
          @click="
            dialogConfig = {
              visible: true,
              row: cloneDeep(newRow),
            }
          "
        ></q-btn>
      </template>
      <template #item="{ row }">
        <div class="col-3 q-pa-sm">
          <q-card class="full-height" style="min-height: 310px;">
            <q-card-section>
              <div>
                <div class="text-caption text-uppercase text-grey-8">
                  Subject
                </div>
                <div>
                  {{ row.subject }}
                </div>
              </div>

              <div class="q-mt-sm">
                <div class="text-caption text-uppercase text-grey-8">
                  Date
                </div>
                <div>
                  {{ row.date }}
                </div>
              </div>

              <div class="q-mt-sm" style="height: 150px; overflow-y: auto;">
                <div class="text-caption text-uppercase text-grey-8">
                  Contents
                </div>
                <div v-html="row.content"></div>
              </div>
            </q-card-section>

            <q-card-actions class="justify-end absolute-bottom">
              <q-btn
                icon="delete"
                flat
                round
                color="negative"
                @click="deleteItem(row)"
              ></q-btn>
              <q-btn
                icon="edit"
                flat
                round
                color="primary"
                @click="dialogConfig = { visible: true, row: cloneDeep(row) }"
              ></q-btn>
            </q-card-actions>
          </q-card>
        </div>
      </template>
    </q-table>

    <edit-news
      v-model="dialogConfig.visible"
      :row="dialogConfig.row"
    ></edit-news>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import { ref } from "vue";
import EditNews from "src/components/EditNews.vue";
import { formatISO9075 } from "date-fns";
import { cloneDeep, remove } from "lodash-es";
import { Notify } from "quasar";
import callApi from "src/assets/call-api";

const newRow = {
  subject: null,
  date: formatISO9075(new Date(), { representation: "date" }),
  content: "",
};

const store = useStore();

const dialogConfig = ref({
  visible: false,
  row: null,
});

const deleteItem = async (row) => {
  Notify.create({
    type: "warning",
    position: "center",
    message: "Are you sure you want to delete this item?",
    actions: [
      {
        label: "No",
      },
      {
        label: "Yes",
        handler: async () => {
          const response = await callApi({
            path: `/news/${row.id}`,
            method: "delete",
            useAuth: true,
          });
          const type = response.status == "success" ? "positive" : "negative";

          Notify.create({
            type,
            position: "center",
            message: response.message,
          });

          if (response.status == "success") {
            remove(store.admin.news, ({ id }) => id == row.id);
          }
        },
      },
    ],
  });
};
</script>
