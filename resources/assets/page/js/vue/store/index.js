import Vue from 'vue'
import createPersistedState from 'vuex-persistedstate'

const setNestedKey = (obj, path, value) => {
    if (path.length === 1) {
        obj[path] = value;
        return
    }
    return setNestedKey(obj[path[0]], path.slice(1), value)
};

export default {
    plugins: [
        createPersistedState({storage: window.sessionStorage})
    ],
    state: {
        filtered: false,
        selected: {},
        items: {},
        config: {},
        isLoading: {
            is: false,
            uid: 15,
        },
        fetching: false
    },
    mutations: {
        set(state, {state_key, key, value}) {
            if (!state[state_key].hasOwnProperty(key))
                Vue.set(state[state_key], key, value);
            else
                state[state_key][key] = value;
        },
        putInSelected(state, {key, value}) {
            this.commit('set', {state_key: 'selected', key, value})
        },
        putInItems(state, {key, value}) {
            this.commit('set', {state_key: 'items', key, value})
        },
        setLoading(state, payload) {
            state.isLoading = payload
        },
        setFetching(state, is) {
            state.fetching = is
        },
        removeSelectedByName(state, key) {
            Vue.delete(state.selected, key)
        }
    },
    actions: {
        fetch({commit, state}, {name, url}) {
            return new Promise((resolve, reject) => {
                commit('setFetching', true);
                axios.get(url).then(({data}) => {
                    commit('putInItems', {
                        key: name,
                        value: data.data
                    });
                    resolve(data)
                }).catch((e) => {
                    console.log(e);
                    reject('Error en la sincronización')
                })
                    .finally(() => {
                        commit('setFetching', false);
                    });
            })
        },
    },
    getters: {
        getSelectedByName: (state) => (key) => {
            return state.selected[key]
        },
        getItemsByName: (state) => (key) => {
            return state.items[key] || []
        },
        isLoading: state => (ref) => {
            return state.isLoading.uid === ref._uid && state.isLoading.is
        },
        isFetching: state => {
            return state.fetching
        },
        getAllSelected: state => () => {
            return state.selected;
        }    
    }
}

