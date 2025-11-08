<template>
  <div class="q-pa-md">
    <div class="row q-gutter-md">
      <div class="col-6" v-if="Screen.gt.sm">
        <q-img :src="store.about.image" height="80vh" fit="contain"></q-img>
      </div>
      <div v-html="store.about.contents" class="col"></div>
    </div>
  </div>
</template>

<script setup>
import { Screen } from "quasar";
import { useStore } from "src/stores/store";

const store = useStore();
</script>
