export default {
    setCheckPlan(state,data){
        state.checkPlan = Object.assign({},data);
    },
    setCheckWork(state,data){
        state.checkWork = Object.assign({},data);
    }
}