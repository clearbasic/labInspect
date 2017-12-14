export default {
    getCheckPlan(state,data){
        state.checkPlan = Object.assign({},data);
    },
    getCheckWork(state,data){
        state.checkWork = Object.assign({},data);
    },
    getOrgList(state,data){
        state.orgList = data;
    },
    setCurrentRoom(state,data){
        state.currentRoom = Object.assign({},{
            agent_name:"",
            agent_id:"",
            dept_id:0,
            lab_id:0,
            zone_id:0
        },data);
    },
    setCurrentZone(state,data){
        state.currentZone = Object.assign({},{
            zone_name:"",
            org_id:0,
        },data);
    }
}