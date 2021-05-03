import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios';

Vue.use(Vuex);

let store = new Vuex.Store({
    state: {
        categories: []
    },
    mutations: {
        SET_CATEGORIES_TO_STATE: (state, categories) => {
            state.categories = categories;
        }
    },
    actions: {
        GET_CATEGORIES_FROM_API({commit}) {
            return axios('http://' + window.location.host + '/api/categories', {
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
        }
    },
    getters: {
        CATEGORIES(state) {
            return state.categories;
        }
    }
});

export default store;