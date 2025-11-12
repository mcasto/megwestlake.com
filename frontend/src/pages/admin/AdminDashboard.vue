<template>
  <div class="row q-gutter-md">
    <div class="col-3" v-for="item of store.admin.dashboard" :key="item.link">
      <router-link :to="{ name: item.link }">
        <q-card class="bg-transparent" flat>
          <q-img :src="item.image" height="30vh" fit="contain"></q-img>
          <div class="text-h6 text-center text-blue-10">
            {{ item.label }}
          </div>
        </q-card>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";

const store = useStore();
</script>
