import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useStore = defineStore(
  "store",
  () => {
    const state = {
      about: ref(null),
      admin: ref({
        about: null,
        calendar: null,
        dashboard: null,
        home: null,
        news: null,
      }),
      calendar: ref(null),
      home: ref(null),
      news: ref(null),
      token: ref(null),
      user: ref(null),
    };
    const getters = {};
    const actions = {};

    return { ...state, ...getters, ...actions };
  },
  {
    persist: {
      key: "default-key",
    },
  }
);
