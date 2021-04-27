import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios';

Vue.use(Vuex);

let store = new Vuex.Store({
    state: {
        categories: [],
        items: []
    },
    mutations: {
        SET_CATEGORIES_TO_STATE: (state, categories) => {
            state.categories = categories;
        },
        SET_ITEMS_TO_STATE: (state, items) => {
            state.items = items;
        },
    },
    actions: {
        GET_CATEGORIES_FROM_API({commit}) {
            return axios('http://192.168.99.101:8000' + '/api/v1/categories', {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
                .then((categories) => {
                    commit('SET_CATEGORIES_TO_STATE', categories.data);
                    return categories;
                })
                .catch((error) => {
                    console.log(error);
                    return error;
                })
        },
        GET_ITEMS_FROM_API({commit}) {
            return axios('http://192.168.99.101:8000' + '/api/v1/items', {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json"
                }
            })
                .then((items) => {
                    commit('SET_ITEMS_TO_STATE', items.data);
                    return items;
                })
                .catch((error) => {
                    console.log(error);
                    return error;
                })
        }
    },
    getters: {
        CATEGORIES(state) {
            return state.categories;
        },
        ITEMS(state) {
            return state.items;
        }
    }
});

export default store;