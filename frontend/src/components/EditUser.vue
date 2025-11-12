<template>
  <q-dialog v-model="model">
    <q-card style="width: 50vw;">
      <q-form @submit.prevent="onSubmit">
        <q-card-section class="column q-gutter-y-sm">
          <q-input
            type="text"
            label="Name"
            v-model="user.name"
            dense
            outlined
            required
          ></q-input>

          <q-input
            type="email"
            label="Email"
            v-model="user.email"
            dense
            outlined
            required
          ></q-input>

          <q-btn
            v-if="!changePass"
            label="Reset Password"
            color="primary"
            @click="changePass = true"
          ></q-btn>
          <div v-if="changePass" class="column q-gutter-y-sm">
            <q-input
              :type="showPass ? 'text' : 'password'"
              label="Password"
              v-model="user.password"
              dense
              outlined
              required
            >
              <template #append>
                <q-btn
                  :icon="showPass ? 'visibility_off' : 'visibility'"
                  round
                  flat
                  color="primary"
                  @click="showPass = !showPass"
                  tabindex="-1"
                ></q-btn>
              </template>
            </q-input>

            <q-input
              :type="showPass ? 'text' : 'password'"
              label="Confirm Password"
              v-model="confirmPassword"
              dense
              outlined
              required
              :rules="[
                (v) =>
                  v == user.password || 'Password and Confirmation must match',
              ]"
            >
            </q-input>
          </div>
        </q-card-section>

        <q-card-actions class="justify-end">
          <q-btn color="negative" label="Cancel" @click="onCancel"></q-btn>
          <q-btn color="positive" label="Save" type="submit"></q-btn>
        </q-card-actions>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { Notify } from "quasar";
import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";
import { ref } from "vue";

const store = useStore();

const model = defineModel();
const props = defineProps(["user"]);

const confirmPassword = ref(null);
const showPass = ref(false);
const changePass = ref(false);

const onSubmit = async () => {
  const response = await callApi({
    path: `/users/${props.user.id}`,
    method: "put",
    payload: { ...props.user, changePass: changePass.value },
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

  store.admin.users = store.admin.users.map((user) => {
    if (user.id == props.user.id) {
      return response.user;
    }

    return user;
  });

  Notify.create({
    type: "positive",
    position: "center",
    message: "User updated",
  });

  model.value = false;
};

const onCancel = () => {
  model.value = false;
};
</script>
