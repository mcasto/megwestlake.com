<template>
  <div>
    <q-table :rows="filteredEntries" :columns="columns">
      <template #body-cell-tools="{row}">
        <q-td class="text-right">
          <q-btn icon="delete" color="negative" round flat></q-btn>
          <q-btn icon="edit" color="primary" round flat></q-btn>
        </q-td>
      </template>
    </q-table>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import { computed, ref } from "vue";

const store = useStore();

const filter = ref({ type: "name", name: null, dateRange: null });

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
</script>
