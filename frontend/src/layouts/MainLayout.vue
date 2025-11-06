<template>
  <q-layout view="lHh Lpr lFf">
    <q-toolbar class="bg-brown-4 text-white" dark>
      <q-btn v-if="Screen.lt.md" icon="menu" flat></q-btn>
      <q-toolbar-title>
        Meg Westlake
      </q-toolbar-title>
      <q-tabs>
        <q-route-tab label="Home" :to="{ name: 'home' }"></q-route-tab>
        <q-route-tab label="About" :to="{ name: 'about' }"></q-route-tab>
        <q-route-tab
          label="Race Calendar"
          :to="{ name: 'race-calendar' }"
        ></q-route-tab>
      </q-tabs>
    </q-toolbar>
    <q-page-container>
      <q-page class="bg-brown-2">
        <router-view />
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { Screen } from "quasar";
import { ref } from "vue";
</script>
