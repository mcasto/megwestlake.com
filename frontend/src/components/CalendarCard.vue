<template>
  <q-card class="bg-transparent" bordered flat>
    <q-card-section>
      <div class="text-subtitle1">
        {{ name }}
      </div>
      <div class="text-caption">
        {{ format(parseISO(date), "PP") }}
      </div>
      <div class="text-subtitle2">
        {{ distance }}
      </div>
      <div v-html="results"></div>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { format, parseISO } from "date-fns";

const props = defineProps(["name", "date", "distance", "results"]);
</script>
