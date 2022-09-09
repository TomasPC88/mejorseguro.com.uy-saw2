import Vue from 'vue'

const setNestedKey = (obj, path, value) => {
    if (path.length === 1) {
        obj[path] = value;
        return
    }
    return setNestedKey(obj[path[0]], path.slice(1), value)
};

export default {
    state: {
        selected: {},
        items:{},
        config:{},
        isLoading: {
            is: false,
            uid: 15,
        }
    },
    mutations: {
        putInSelected(state, {key, value}) {
            if (!state.selected.hasOwnProperty(key))
                Vue.set(state.selected, key, value);
            else
                state.selected[key] = value;

        },
        putInItems(state, {key, value}) {
            if (!state.items.hasOwnProperty(key))
                Vue.set(state.items, key, value);
            else
                state.items[key] = value;

        },
        setLoading(state, payload) {
            state.isLoading = payload
        },
        removeSelectedByName(state, key) {
            Vue.delete(state.selected, key)
        }
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
        }
    }
}

