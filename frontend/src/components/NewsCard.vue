<template>
  <q-card class="bg-transparent" bordered flat>
    <q-card-section>
      <div class="text-h6">
        {{ subject }}
      </div>
      <div class="text-caption">
        {{ format(parseISO(date), "PP") }}
      </div>
    </q-card-section>
    <q-card-section>
      <div v-html="contents"></div>
    </q-card-section>
  </q-card>
</template>

<script setup>
import { format, parseISO } from "date-fns";

const props = defineProps(["subject", "date", "contents"]);
</script>
