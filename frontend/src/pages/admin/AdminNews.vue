<template>
  <div>
    Admin News

    <pre class="q-mt-md">
      {{ store.admin.news }}
    </pre>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";

const store = useStore();
</script>
