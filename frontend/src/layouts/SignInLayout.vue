<template>
  <q-layout view="lHh Lpr lFf">
    <q-toolbar class="bg-brown-4 text-white" dark>
      <q-btn v-if="Screen.lt.md" icon="menu" flat></q-btn>
      <q-toolbar-title>
        Meg Westlake
      </q-toolbar-title>
    </q-toolbar>
    <q-page-container>
      <q-page class="bg-brown-2">
        <router-view />
      </q-page>
    </q-page-container>
    <q-footer class="bg-brown-4 text-white">
      <div class="flex justify-between items-center">
        <q-btn
          icon="settings"
          flat
          round
          size="sm"
          :to="{ name: 'admin' }"
        ></q-btn>
        <div>
          Site by
          <a href="https://castoware.com" target="_blank" class="text-blue-10">
            CastoWare Development
          </a>
        </div>
        <div>
          <a
            href="https://iconscout.com/icons/strava"
            target="_blank"
            class="text-blue-10"
            v-if="Screen.gt.sm"
            >Strava Icon</a
          >&nbsp;by&nbsp;
          <a
            href="https://iconscout.com/contributors/jagathish"
            target="_blank"
            class="text-blue-10"
            v-if="Screen.gt.sm"
            >Jagathish Saravanan</a
          >
          <q-btn
            icon
            color="brown-lighten-2"
            flat
            href="https://www.strava.com/athletes/margaret_westlake"
            target="_blank"
          >
            <q-img
              src="/storage/images/strava.png"
              height="32px"
              width="32px"
            ></q-img>
          </q-btn>
        </div>
      </div>
    </q-footer>
  </q-layout>
</template>

<script setup>
import { Screen } from "quasar";
import { ref } from "vue";
</script>
