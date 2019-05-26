<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar>
        <q-btn
          flat
          dense
          round
          @click="leftDrawerOpen = !leftDrawerOpen"
          aria-label="Menu"
        >
          <q-icon name="menu" />
        </q-btn>

        <q-toolbar-title>
          {{ getComic() }}
        </q-toolbar-title>

        <div>{{ getChapter() }}</div>
      </q-toolbar>
    </q-header>

    <q-drawer
      v-model="leftDrawerOpen"
      bordered
      content-class="bg-grey-2"
    >
      <q-list>
        <q-item-label header>Daftar Komik</q-item-label>
        <q-item v-for="comic in comics" :key="comic.key" clickable :to="`/${comic.key}`">
          <q-item-section>
            <q-item-label>{{ comic.title }}</q-item-label>
            <q-item-label caption>{{ comic.last.title }}</q-item-label>
            <q-item-label caption>{{ comic.last.date }}</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>

    <q-footer v-if="$route.params.chapter">
      <q-toolbar>
        <q-btn
          flat
          dense
          v-if="getChapterLink().before"
          :to="getChapterLink().before.link"
        >
          <q-icon left size="3em" name="arrow_back" />
          <div>{{ getChapterLink().before.chapter }}</div>
        </q-btn>

        <q-toolbar-title/>

        <q-btn
          flat
          dense
          v-if="getChapterLink().after"
          :to="getChapterLink().after.link"
        >
          <div>{{ getChapterLink().after.chapter }}</div>
          <q-icon right size="3em" name="arrow_forward" />
        </q-btn>
      </q-toolbar>
    </q-footer>

  </q-layout>
</template>

<script>
export default {
  name: 'MyLayout',
  data () {
    return {
      leftDrawerOpen: this.$q.platform.is.desktop
    }
  },
  mounted () {
    this.$store.dispatch('comic/LOAD_COMICS')
  },
  computed: {
    comics () {
      return this.$store.state.comic.comics
    }
  },
  methods: {
    getComic () {
      let comic = this.comics.find(item => item.key === this.$route.params.id)
      return comic !== undefined ? comic.title : ''
    },
    getChapter () {
      return this.$route.params.chapter ? this.$route.params.chapter : ''
    },
    getChapterLink () {
      return this.$store.getters['comic/GET_CHAPTER_LINK'](this.$route.params.id, this.$route.params.chapter)
    }
  }
}
</script>

<style>
</style>
