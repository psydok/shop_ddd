import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios';

Vue.use(Vuex);

let store = new Vuex.Store({
    state: {
        token: localStorage.getItem('token') || "",
        categories: [],
        items: [],
        catalog: []
    },
    mutations: {
        SET_CATALOG_TO_STATE: (state, catalog) => {
            state.catalog = catalog;
        },
        SET_CATEGORIES_TO_STATE: (state, categories) => {
            state.categories = categories;
        },
        SET_ITEMS_TO_STATE: (state, items) => {
            state.items = items;
        },
        SET_TOKEN: (state, token) => {
            console.log(token);
            document.cookie = "token=" + token;
            state.token = token;
        },
        DELETE_TOKEN: (state) => {
            document.cookie = "";
            state.items = "";
        }
    },
    actions: {
        CHECK(context) {
            return new Promise((resolve, reject) => {
                axios('http://' + 'localhost:8060' + '/admin', {
                    method: "GET",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                        'Authorization': "Bearer " + context.getters.TOKEN
                    }
                })
                    .then((response) => {
                        console.log("есть доступ");
                        resolve();
                    })
                    .catch((error) => {
                        console.log("нет доступа");
                        console.log(error);
                        reject(error)
                    })
            });
        },
        GET_CATALOG_FROM_API({commit}) {
            return axios('http://' + 'localhost:8060' + '/catalog/categories', {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                }
            })
                .then((catalog) => {
                    commit('SET_CATALOG_TO_STATE', catalog.data);
                    return catalog;
                })
                .catch((error) => {
                    console.log(error);
                })
        },
        GET_CATEGORIES_FROM_API(context) {
            console.log(context.getters.TOKEN);
            return axios('http://' + 'localhost:8060' + '/admin/categories', {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    'Authorization': "Bearer " + context.getters.TOKEN
                }
            })
                .then((categories) => {
                    context.commit('SET_CATEGORIES_TO_STATE', categories.data);
                    return categories;
                })
                .catch((error) => {
                    console.log(error);
                    return error;
                })
        },
        GET_ITEMS_FROM_API(context) {
            console.log('token&??')

            console.log(context.getters.TOKEN)
            return axios('http://' + 'localhost:8060' + '/admin/items', {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "Content-Type": "application/json",
                    'Authorization': "Bearer " + context.getters.TOKEN
                }
            })
                .then((items) => {
                    context.commit('SET_ITEMS_TO_STATE', items.data);
                    return items;
                })
                .catch((error) => {
                    console.log(error);
                    return error;
                })
        },
        REGISTER({commit}, [login, password]) {
            const body = JSON.stringify({
                login: login,
                password: password
            });
            return new Promise((resolve, reject) => {
                axios('http://' + 'localhost:8060' + '/register', {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    data: body
                })
                    .then((response) => {
                        console.log(response.data);
                        this.dispatch('LOGIN', [login, password]);
                        resolve(response)
                    })
                    .catch((error) => {
                        console.log(error);
                        reject(error)
                    })
            });
        },
        LOGIN({commit}, [login, password]) {
            const body = JSON.stringify({
                login: login,
                password: password
            });
            return new Promise((resolve, reject) => {
                axios('http://' + 'localhost:8060' + '/auth', {
                    method: "POST",
                    headers: {
                        "Accept": "application/json",
                        "Content-Type": "application/json"
                    },
                    data: body
                })
                    .then((response) => {
                        const token = response.data.jwt;
                        localStorage.setItem('token', token);
                        axios.defaults.headers.common['Authorization'] = "Bearer " + token;
                        commit('SET_TOKEN', token);
                        resolve(response);
                    })
                    .catch((error) => {
                        console.log(error);
                        localStorage.removeItem('token');
                        reject(error)
                    })
            });

        },
        LOGOUT({commit}) {
            return new Promise((resolve, reject) => {
                commit('DELETE_TOKEN');
                localStorage.removeItem('token')
                delete axios.defaults.headers.common['Authorization']
                resolve()
            });
        },
    },
    getters: {
        CATALOG(state) {
            return state.catalog;
        },
        CATEGORIES(state) {
            return state.categories;
        },
        ITEMS(state) {
            return state.items;
        },
        TOKEN(state) {
            return state.token;
        },
        isLoggedIn: state => !!state.token,
        authStatus: state => state.status,

    }
});

export default store;