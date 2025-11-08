<template>
  <q-page class="bg-brown-2 flex flex-center">
    <q-card class="login-card q-pa-lg shadow-10">
      <q-card-section class="text-center q-pb-md">
        <div class="text-h4 text-weight-bold text-grey-9 q-mb-xs">
          Welcome Back
        </div>
        <div class="text-body2 text-grey-6">Sign in to your account</div>
      </q-card-section>

      <q-card-section>
        <q-form @submit="onSubmit" class="q-gutter-md">
          <!-- Email Input -->
          <q-input
            v-model="email"
            type="email"
            label="Email Address"
            outlined
            rounded
            lazy-rules
            :rules="[
              (val) => !!val || 'Email is required',
              (val) => /.+@.+\..+/.test(val) || 'Email must be valid',
            ]"
          >
            <template v-slot:prepend>
              <q-icon name="mail" />
            </template>
          </q-input>

          <!-- Password Input -->
          <q-input
            v-model="password"
            :type="showPassword ? 'text' : 'password'"
            label="Password"
            outlined
            rounded
            lazy-rules
            :rules="[
              (val) => !!val || 'Password is required',
              (val) =>
                val.length >= 6 || 'Password must be at least 6 characters',
            ]"
          >
            <template v-slot:prepend>
              <q-icon name="lock" />
            </template>
            <template v-slot:append>
              <q-icon
                :name="showPassword ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="showPassword = !showPassword"
              />
            </template>
          </q-input>

          <!-- Submit Button -->
          <q-btn
            type="submit"
            label="Sign In"
            color="brown-9"
            rounded
            unelevated
            class="full-width q-mt-md"
            size="lg"
            no-caps
          />
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { Notify } from "quasar";
import callApi from "src/assets/call-api";
import { useStore } from "src/stores/store";
import { ref } from "vue";

const store = useStore();

const email = ref("");
const password = ref("");
const showPassword = ref(false);

const onSubmit = async () => {
  const response = await callApi({
    path: "/login",
    method: "post",
    payload: { email: email.value, password: password.value },
  });

  if (response.status == "error") {
    Notify.create({
      type: "negative",
      position: "center",
      message: response.message,
    });

    return;
  }

  store.token = response.token;
  store.user = response.user;

  store.router.push({ name: "admin-dashboard" });
};
</script>

<style scoped>
.login-card {
  width: 100%;
  max-width: 450px;
  border-radius: 16px;
  background: white;
}
</style>
