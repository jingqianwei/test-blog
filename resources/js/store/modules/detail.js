// initial state
const state = {
    item: [1, 2, 3],
    count: 500
};

// getters
const getters = {
    cartProducts: (state, getters, rootState) => {

    }
};

// actions
const actions = {
    checkout ({ commit, state }, products) {

    }
};

// mutations
const mutations = {
    pushProductToCart (state, { id }) {
        state.items.push({
            id,
            quantity: 1
        })
    }
};

export default {
    namespaced: true, // 带命名空间
    state,
    getters,
    actions,
    mutations,
}
