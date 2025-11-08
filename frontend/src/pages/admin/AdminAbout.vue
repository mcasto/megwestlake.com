<template>
  <div>
    <div class="q-ma-xl">
      About:
      <pre>
        {{ store.admin.about }}
      </pre>
    </div>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";

const store = useStore();
</script>
