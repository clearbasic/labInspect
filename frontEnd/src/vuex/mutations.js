export default {
    getCheckPlan(state,data){
        state.checkPlan = Object.assign({},data);
    },
    getCheckWork(state,data){
        state.checkWork = Object.assign({},data);
    },
    getOrgList(state,data){
        state.orgList = data;
    }
}