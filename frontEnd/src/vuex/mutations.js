export default {
    getCheckPlan(state,data){
        state.checkPlan = Object.assign({},data);
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
    },
    getMenu(state,data){
        state.leftMenu = data;
    },
    getPlanList(state,data){
        state.plan_list = data;
    },
    showToast(state,data){
        state.showToast = data.isShow;
        if(data.msg){
            state.msgText = data.msg;
        }else{
            state.msgText = "保存成功";
        }
    }
}