<template>
  <q-layout view="lHh Lpr lFf">
    <q-header>
      <q-toolbar class="bg-brown-4 text-white" dark>
        <q-btn v-if="Screen.lt.md" icon="menu" flat></q-btn>
        <q-toolbar-title>
          Meg Westlake: Admin
        </q-toolbar-title>
        <q-tabs>
          <q-route-tab label="Home" :to="{ name: 'admin-home' }"></q-route-tab>
          <q-route-tab label="News" :to="{ name: 'admin-news' }"></q-route-tab>
          <q-route-tab
            label="About"
            :to="{ name: 'admin-about' }"
          ></q-route-tab>
          <q-route-tab
            label="Race Calendar"
            :to="{ name: 'admin-calendar' }"
          ></q-route-tab>
        </q-tabs>
      </q-toolbar>
    </q-header>

    <q-page-container>
      <q-page class="q-pt-md bg-brown-2">
        <router-view />
      </q-page>
    </q-page-container>
    <q-footer class="bg-brown-4 text-white">
      <div class="flex justify-between items-center">
        <q-btn icon="home" flat round size="sm" :to="{ name: 'home' }"></q-btn>
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
</script>
