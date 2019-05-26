<template>
  <q-page class="flex flex-center" style="background-color:black">
    <div v-for="(image, index) in images" :key="index" class="col-12"
      style="width:800px;background-color:white;padding:1px;">
      <img :src="image" style="width:100%">
    </div>
  </q-page>
</template>

<style>
</style>

<script>
export default {
  name: 'PageChapter',
  watch: {
    '$route' (to, from) {
      this.LoadImages(to.params.id, to.params.chapter)
    }
  },
  computed: {
    images () {
      return this.$store.getters['comic/GET_IMAGES'](this.$route.params.id, this.$route.params.chapter)
    }
  },
  mounted () {
    this.LoadImages(this.$route.params.id, this.$route.params.chapter)
  },
  methods: {
    LoadImages (key, chapter) {
      this.$store.dispatch('comic/LOAD_IMAGES', { key, chapter })
    }
  }
}
</script>
