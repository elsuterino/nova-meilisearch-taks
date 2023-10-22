<template>
  <Card>
    <div class="px-3 py-3 space-y-3">
      <h1 class="text-center text-3xl text-gray-500 font-light">Meilisearch Tasks</h1>
      <nav class="flex gap-6 px-4">
        <div class="flex-1">
          <label class="block">Index</label>
          <select v-model="filters.index" class="w-full block form-control form-select form-select-bordered">
            <option :value="null"></option>
            <template v-for="index in card.indexes" :key="index">
              <option :value="index">{{ index }}</option>
            </template>
          </select>
        </div>
        <div class="flex-1">
          <label class="block">Type</label>
          <select v-model="filters.type" class="w-full block form-control form-select form-select-bordered">
            <option :value="null"></option>
            <template v-for="type in types" :key="type">
              <option :value="type">{{ type }}</option>
            </template>
          </select>
        </div>
        <div class="flex-1">
          <label class="block">Status</label>
          <select v-model="filters.status" class="w-full block form-control form-select form-select-bordered">
            <option :value="null"></option>
            <template v-for="status in statuses" :key="status">
              <option :value="status">{{ status }}</option>
            </template>
          </select>
        </div>
        <div class="flex-1">
          <label class="block">Refresh</label>
          <select v-model="refresh" class="w-full block form-control form-select form-select-bordered">
            <option :value="null"></option>
            <option :value="1000">1 sec</option>
            <option :value="5000">5 sec</option>
          </select>
        </div>
      </nav>
      <table class="w-full">
        <thead class="bg-gray-50">
          <tr>
            <th class="text-left px-4 uppercase py-1">UID</th>
            <th class="text-left px-4 uppercase py-1">Index</th>
            <th class="text-left px-4 uppercase py-1">Type</th>
            <th class="text-left px-4 uppercase py-1">Status</th>
            <th class="text-left px-4 uppercase py-1">Enqueued at</th>
          </tr>
        </thead>
        <tbody>
          <template v-for="task in tasks.results" :key="task.uid">
            <tr>
              <td class="px-4 py-1">{{ task.uid }}</td>
              <td class="px-4 py-1">{{ task.indexUid }}</td>
              <td class="px-4 py-1">{{ task.type }}</td>
              <td class="px-4 py-1">{{ task.status }}</td>
              <td class="px-4 py-1">{{ new Date(task.enqueuedAt).toISOString() }}</td>
            </tr>
          </template>
        </tbody>
      </table>
      <div class="flex gap-3 border-t pt-2">
        <div class="px-4">total: {{ tasks.total }}</div>
      </div>
    </div>
  </Card>
</template>

<script setup>
import { reactive, ref, watch } from 'vue';
import axios from 'axios';

defineProps({
  card: {
    type: Object,
    required: true,
  },
});

const types = [
  `documentAdditionOrUpdate`,
  `documentDeletion`,
  `settingsUpdate`,
  `indexCreation`,
  `indexDeletion`,
  `indexUpdate`,
  `indexSwap`,
  `taskCancelation`,
  `taskDeletion`,
  `dumpCreation`,
  `snapshotCreation`,
];

const statuses = [`enqueued`, `processing`, `succeeded`, `failed`, `canceled`];

const filters = reactive({ index: null, type: null, status: null });

const refresh = ref(1000);
const refreshIntervalId = ref();

const tasks = ref({ from: 0, limit: 20, next: 0, results: [], total: 0 });

const updateTasks = () => {
  axios.get('/nova-vendor/meilisearch-task', { params: filters }).then(({ data }) => {
    tasks.value = data;
  });
};

updateTasks();

watch(filters, () => updateTasks());
watch(
  refresh,
  v => {
    if (refreshIntervalId.value) {
      clearInterval(refreshIntervalId.value);
      refreshIntervalId.value = undefined;
    }

    if (v) {
      refreshIntervalId.value = setInterval(() => updateTasks(), v);
    }
  },
  { immediate: true },
);
</script>
