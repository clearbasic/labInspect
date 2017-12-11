import { serverUrl } from "../config/server.js";
import { emitAjax } from "../assets/common.js";
import { checkWork,checkPlan,orgList,orgInfo } from "../config/data";

export default {
    getCheckPlan(context,data){
        const url = serverUrl + "/admin/plan/getdata";
        emitAjax(url,data,function(result){
            context.commit("getCheckPlan",result);
        })
    },
    getCheckWork(context,data){
        if(data){
            context.commit("getCheckWork",data);
        }else{
            context.commit("getCheckWork",checkWork);
        }
    },
    getOrgList(context,data){
        const url = serverUrl +"/admin/org/index";
        emitAjax(url,data,function(result){
            context.commit("getOrgList",result);
        })
    }
}