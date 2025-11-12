<template>
  <div class="q-pa-md">
    <div class="row q-gutter-md">
      <div class="col-12 col-md-6 text-center" v-if="Screen.gt.sm">
        <q-img :src="store.calendar.image" height="80vh" fit="contain"></q-img>
      </div>
      <div class="col">
        <q-tabs v-model="calendarType" align="left">
          <q-tab name="upcoming" label="Upcoming"></q-tab>
          <q-tab name="past" label="Past"></q-tab>
        </q-tabs>
        <q-table
          grid
          :rows="filteredEntries"
          :no-data-label="
            calendarType == 'upcoming' ? 'No Upcoming Events' : 'No Past Events'
          "
        >
          <template #top>
            <div>
              <div>
                <q-radio
                  val="name"
                  label="Event Name"
                  v-model="filter.type"
                ></q-radio>
                <q-radio
                  val="date"
                  label="Date Range"
                  v-model="filter.type"
                ></q-radio>
              </div>
              <div v-if="filter.type == 'name'">
                <q-input
                  type="text"
                  label="Event Name"
                  v-model="filter.name"
                  dense
                  outlined
                  clearable
                  debounce="300"
                  @keydown.esc="filter.name = null"
                ></q-input>
              </div>
              <div v-else>
                <div class="flex items-center">
                  <div>
                    <q-btn
                      icon="mdi-calendar-month"
                      label="Specify Date Range"
                      color="primary"
                    >
                      <q-menu>
                        <q-date v-model="filter.dateRange" range>
                          <div class="row items-center justify-end">
                            <q-btn
                              v-close-popup
                              label="Close"
                              color="primary"
                              flat
                            />
                          </div>
                        </q-date>
                      </q-menu>
                    </q-btn>
                  </div>

                  <div class="q-ml-xl" v-if="filter.dateRange">
                    {{ format(new Date(filter.dateRange.from), "PP") }} -
                    {{ format(new Date(filter.dateRange.to), "PP") }}
                  </div>
                </div>
              </div>
            </div>
          </template>
          <template #item="props">
            <div
              class="q-py-xs col-12 grid-style-transition"
              :style="props.selected ? 'transform: scale(0.95);' : ''"
            >
              <calendar-card
                :date="props.row.date"
                :distance="props.row.distance"
                :results="props.row.results"
                :name="props.row.name"
              ></calendar-card>
            </div>
          </template>
        </q-table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Screen } from "quasar";
import { useStore } from "src/stores/store";
import CalendarCard from "src/components/CalendarCard.vue";
import { computed, ref } from "vue";
import { format, isWithinInterval, parseISO } from "date-fns";

const store = useStore();

const calendarType = ref("upcoming");

const filter = ref({ type: "name", name: null, dateRange: null });

const filteredEntries = computed(() => {
  const entries = store.calendar.entries[calendarType.value];

  if (!filter.value.name && !filter.value.dateRange) {
    return entries;
  }

  if (filter.value.type == "name") {
    if (!filter.value.name) {
      return entries;
    }
    return entries.filter(({ name }) =>
      name.toLowerCase().includes(filter.value.name.toLowerCase())
    );
  }

  if (filter.value.type == "date") {
    if (!filter.value.dateRange) {
      return entries;
    }
    return entries.filter(({ date }) => {
      return isWithinInterval(new Date(date), {
        start: filter.value.dateRange.from,
        end: filter.value.dateRange.to,
      });
    });
  }
});
</script>
