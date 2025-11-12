<template>
  <div>
    <q-table
      :rows="store.admin.users"
      :columns="columns"
      :pagination="{ rowsPerPage: 0 }"
      hide-bottom
    >
      <template #header-cell-tools>
        <q-th class="text-center">
          <q-btn
            icon="add"
            round
            color="primary"
            size="sm"
            @click="addDialog = true"
          ></q-btn>
        </q-th>
      </template>

      <template #body-cell-tools="{row}">
        <q-td class="text-center">
          <q-btn
            icon="delete"
            flat
            round
            color="negative"
            size="md"
            @click="onDelete(row)"
          ></q-btn>
          <q-btn
            icon="edit"
            flat
            round
            color="primary"
            size="md"
            @click="editDialog = { visible: true, user: cloneDeep(row) }"
          ></q-btn>
        </q-td>
      </template>
    </q-table>

    <add-user v-model="addDialog"></add-user>
    <edit-user v-model="editDialog.visible" :user="editDialog.user"></edit-user>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import { ref } from "vue";
import AddUser from "src/components/AddUser.vue";
import EditUser from "src/components/EditUser.vue";
import { Notify } from "quasar";
import callApi from "src/assets/call-api";
import { cloneDeep, remove } from "lodash-es";

const store = useStore();

const addDialog = ref(false);
const editDialog = ref({
  visible: false,
  user: null,
});

const columns = [
  {
    label: "Name",
    name: "name",
    field: "name",
    align: "left",
  },
  {
    label: "Email",
    name: "email",
    field: "email",
    align: "left",
  },
  {
    label: "",
    name: "tools",
  },
];

const onDelete = async (row) => {
  Notify.create({
    type: "warning",
    position: "center",
    message: "Are you sure you want to delete this user?",
    actions: [
      { label: "No" },
      {
        label: "Yes",
        handler: async () => {
          const response = await callApi({
            path: `/users/${row.id}`,
            method: "delete",
            useAuth: true,
          });

          if (response.status != "success") {
            Notify.create({
              type: "negative",
              position: "center",
              message: response.message || "Unknown error",
            });

            return;
          }

          Notify.create({
            type: "positive",
            position: "center",
            message: "User deleted",
          });

          remove(store.admin.users, ({ id }) => id == row.id);
        },
      },
    ],
  });
};
</script>
