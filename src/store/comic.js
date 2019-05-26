import axios from 'axios'

export default {
  namespaced: true,
  state: {
    comics: [],
    chapters: [],
    images: []
  },
  getters: {
    GET_CHAPTERS: state => key => {
      let chapter = state.chapters.find(item => item.key === key)
      return chapter ? chapter.data : []
    },
    GET_CHAPTER_LINK: state => (key, chapter) => {
      let result = { before: null, after: null }
      if (state.chapters.length === 0) {
        return result
      }
      let comic = state.chapters.find(item => item.key === key)
      let data = comic.data.find(item => item.chapter === chapter)
      let i = comic.data.indexOf(data)

      let before = comic.data[i + 1]
      let after = comic.data[i - 1]
      before ? result.before = { chapter: before.chapter, link: `/${key}/${before.chapter}` } : result.before = null
      after ? result.after = { chapter: after.chapter, link: `/${key}/${after.chapter}` } : result.after = null

      return result
    },
    GET_IMAGES: state => (key, chapter) => {
      let image = state.images.find(item => item.key === key & item.chapter === chapter)
      return image ? image.data : []
    }
  },
  mutations: {
    STORE_COMICS (state, payload) {
      state.comics = payload
    },
    STORE_CHAPTERS (state, payload) {
      let chapter = state.chapters.find(item => item.key === payload.key)
      if (chapter) {
        chapter.data = payload.data
      } else {
        state.chapters.push(payload)
      }
    },
    STORE_IMAGES (state, payload) {
      state.images.push(payload)
    }
  },
  actions: {
    LOAD_COMICS ({ commit, state }) {
      if (state.comics.length > 0) {
        return
      }
      axios.get()
        .then(res => {
          commit('STORE_COMICS', res.data)
        })
    },
    LOAD_CHAPTERS ({ dispatch, commit, getters }, payload) {
      dispatch('LOAD_COMICS')
      if ((!payload.force) & (getters.GET_CHAPTERS(payload.key).length > 0)) {
        return
      }
      axios.get(`?comic=${payload.key}`)
        .then(res => {
          commit('STORE_CHAPTERS', { key: payload.key, data: res.data })
        })
    },
    LOAD_IMAGES ({ dispatch, commit, getters }, payload) {
      dispatch('LOAD_CHAPTERS', { key: payload.key, force: false })
      if (getters.GET_IMAGES(payload.key, payload.chapter).length > 0) {
        return
      }
      axios.get(`?comic=${payload.key}&chapter=${payload.chapter}`)
        .then(res => {
          commit('STORE_IMAGES', { key: payload.key, chapter: payload.chapter, data: res.data })
        })
    }
  }
}
