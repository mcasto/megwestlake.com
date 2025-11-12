<template>
  <div>
    <q-table :rows="filteredEntries" :columns="columns">
      <template #top>
        <div>
          <div>
            <q-radio
              val="name"
              label="Event Name"
              v-model="filter.type"
            ></q-radio>
            <q-radio
              val="date"
              label="Date Range"
              v-model="filter.type"
            ></q-radio>
          </div>
          <div v-if="filter.type == 'name'">
            <q-input
              type="text"
              label="Event Name"
              v-model="filter.name"
              dense
              outlined
              clearable
              debounce="300"
              @keydown.esc="filter.name = null"
            ></q-input>
          </div>
          <div v-else>
            <div class="flex items-center">
              <div>
                <q-btn
                  icon="mdi-calendar-month"
                  label="Specify Date Range"
                  color="primary"
                >
                  <q-menu>
                    <q-date v-model="filter.dateRange" range>
                      <div class="row items-center justify-end">
                        <q-btn
                          v-close-popup
                          label="Close"
                          color="primary"
                          flat
                        />
                      </div>
                    </q-date>
                  </q-menu>
                </q-btn>
              </div>

              <div class="q-ml-xl" v-if="filter.dateRange">
                {{ format(new Date(filter.dateRange.from), "PP") }} -
                {{ format(new Date(filter.dateRange.to), "PP") }}
                <q-btn
                  icon="mdi-close-circle"
                  flat
                  round
                  color="grey-7"
                  @click="filter.dateRange = null"
                ></q-btn>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template #header-cell-tools>
        <q-th class="text-right">
          <q-btn
            icon="add"
            color="primary"
            round
            size="sm"
            class="q-mr-lg"
            @click="dialog = { visible: true, row: clone(newEntry) }"
          ></q-btn>
        </q-th>
      </template>
      <template #body-cell-tools="{row}">
        <q-td class="text-right">
          <q-btn
            icon="delete"
            color="negative"
            round
            flat
            @click="onDelete(row)"
          ></q-btn>
          <q-btn
            icon="edit"
            color="primary"
            round
            flat
            @click="dialog = { visible: true, row }"
          ></q-btn>
        </q-td>
      </template>
    </q-table>

    <calendar-dialog
      v-model="dialog.visible"
      :row="dialog.row"
    ></calendar-dialog>
  </div>
</template>

<script setup>
import { clone, remove } from "lodash-es";
import { useStore } from "src/stores/store";
import { computed, ref } from "vue";
import CalendarDialog from "src/components/CalendarDialog.vue";
import { formatISO9075, isWithinInterval, format } from "date-fns";
import callApi from "src/assets/call-api";
import { Notify } from "quasar";

const store = useStore();

const filter = ref({ type: "name", name: null, dateRange: null });

const newEntry = {
  date: formatISO9075(new Date(), { representation: "date" }),
  distance: null,
  name: null,
  results: null,
};

const dialog = ref({
  visible: false,
  row: null,
});

const columns = [
  {
    label: "Date",
    name: "date",
    field: "date",
    align: "left",
  },
  {
    label: "Name",
    name: "name",
    field: "name",
    align: "left",
  },
  {
    label: "",
    name: "tools",
  },
];

const filteredEntries = computed(() => {
  const entries = [
    ...store.admin.calendar.entries.upcoming,
    ...store.admin.calendar.entries.past,
  ];

  if (!filter.value.name && !filter.value.dateRange) {
    return entries;
  }

  if (filter.value.type == "name") {
    if (!filter.value.name) {
      return entries;
    }
    return entries.filter(({ name }) =>
      name.toLowerCase().includes(filter.value.name.toLowerCase())
    );
  }

  if (filter.value.type == "date") {
    if (!filter.value.dateRange) {
      return entries;
    }
    return entries.filter(({ date }) => {
      return isWithinInterval(new Date(date), {
        start: filter.value.dateRange.from,
        end: filter.value.dateRange.to,
      });
    });
  }
});

const onDelete = async (row) => {
  Notify.create({
    type: "warning",
    position: "center",
    message: "Are you sure you want to delete this entry?",
    actions: [
      { label: "No" },
      {
        label: "Yes",
        handler: async () => {
          const response = await callApi({
            path: `/calendar/${row.id}`,
            method: "delete",
            useAuth: true,
          });

          const type = response.status == "success" ? "positive" : "negative";
          const message =
            response.status == "success"
              ? "Successfully deleted entry"
              : "Unable to delete entry";

          if (response.status == "success") {
            remove(
              store.admin.calendar.entries.upcoming,
              ({ id }) => id == row.id
            );

            remove(store.admin.calendar.entries.past, ({ id }) => id == row.id);
          }

          Notify.create({ type, message, position: "center" });
        },
      },
    ],
  });
};
</script>
