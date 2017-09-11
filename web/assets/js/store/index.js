import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

Vue.use(Vuex);

export const store = new Vuex.Store({
    state: {
        loadedPhones: []
    },
    mutations: {
        setLoadedPhones (state, payload) {
            state.loadedPhones = payload
        }
    },
    actions: {
        loadPhones ({ commit })  {
            axios.get('http://127.0.0.1:8001/api/phones').then((response) => {
                let phones = response.data[0].items;
                commit('setLoadedPhones', phones)
            }, (error) => {
                console.log(error)
            })
        }
    },
    getters: {
        loadedPhones (state) {
            return state.loadedPhones
        },
        loadedPhone (state) {
            return (phoneSlug) => {
                return state.loadedPhones.find((phone) => {
                    return phone.slug === phoneSlug
                })
            }
        }
    }
});
