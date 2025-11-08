<template>
  <div>
    <div class="q-ma-xl">
      Calendar:
      <pre>
        {{ store.admin.calendar }}
      </pre>
    </div>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";

const store = useStore();
</script>
