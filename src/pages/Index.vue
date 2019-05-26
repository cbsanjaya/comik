<template>
  <q-page padding>
    <q-list bordered padding>
      <q-item clickable @click="loadTitle($route.params.id, true)">
        <q-item-section>
          <q-item-label>Refresh</q-item-label>
        </q-item-section>
      </q-item>
      <q-item v-for="title in titles" :key="title.chapter" clickable :to="`/${$route.params.id}/${title.chapter}`">
        <q-item-section>
          <q-item-label>{{ title.title }}</q-item-label>
          <q-item-label caption>{{ title.date }}</q-item-label>
        </q-item-section>
      </q-item>
    </q-list>
  </q-page>
</template>

<style>
</style>

<script>
export default {
  name: 'PageIndex',
  watch: {
    '$route' (to, from) {
      this.loadTitle(to.params.id)
    }
  },
  computed: {
    titles () {
      return this.$store.getters['comic/GET_CHAPTERS'](this.$route.params.id)
    }
  },
  mounted () {
    this.loadTitle(this.$route.params.id)
  },
  methods: {
    loadTitle (key, force = false) {
      this.$store.dispatch('comic/LOAD_CHAPTERS', { key, force })
    }
  }
}
</script>
