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
              row: newRow,
            }
          "
        ></q-btn>
      </template>
      <template #item="{ row }">
        <div class="col-3 q-pa-sm z-max">
          <q-card>
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

              <div class="q-mt-sm">
                <div class="text-caption text-uppercase text-grey-8">
                  Contents
                </div>
                <div v-html="row.content"></div>
              </div>
            </q-card-section>

            <q-card-actions class="justify-end">
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
import { cloneDeep } from "lodash-es";

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
  console.log({ delete: row });
};

const editItem = (row) => {
  console.log({ edit: row });
};
</script>
