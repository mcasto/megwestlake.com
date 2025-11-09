<template>
  <div class="q-pa-md">
    <div class="row" v-if="store.home">
      <div class="col-12 col-md-6 text-center">
        <q-img :src="store.home.image" height="50vh" fit="contain"> </q-img>
        <div class="text-caption q-mt-xs">
          <div v-html="store.home.caption"></div>
        </div>
      </div>
      <div class="col-12 col-md-6" v-if="store.news">
        <span class="text-h4">News</span>
        <div class="column q-gutter-y-sm">
          <news-card
            v-for="item of store.news"
            :key="`news-item-${item.id}`"
            :subject="item.subject"
            :date="item.date"
            :contents="item.content"
          ></news-card>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useStore } from "src/stores/store";
import NewsCard from "src/components/NewsCard.vue";

const store = useStore();
</script>
