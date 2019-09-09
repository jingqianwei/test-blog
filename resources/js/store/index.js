import Vue from 'vue'
import Vuex from 'vuex'
import detail from './modules/detail' // 就是把仓库拆分处理

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production';

export default new Vuex.Store({
    state: {
        count: 100
    },
    mutations: {
        increment(state) {
            state.count++
        }
    },
    strict: debug,
    modules: {
        detail
    }
})
