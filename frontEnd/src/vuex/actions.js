import { serverUrl } from "../config/server.js";
import { emitAjax } from "../assets/common.js";
import { checkWork,checkPlan } from "../config/data";

export default {
    getCheckPlan(context,data){
        const url = serverUrl + "/admin/plan/getdata";
        emitAjax(url,data,function(checkPlan){
            context.commit("getCheckPlan",checkPlan);
        })
    },
    getCheckWork(context,data){
        if(data){
            context.commit("getCheckWork",data);
        }else{
            context.commit("getCheckWork",checkWork);
        }
    }
}