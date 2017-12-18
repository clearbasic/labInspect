import { serverUrl,pathName } from "../config/server.js";
import { emitAjax,setLocalData,delLocalData } from "../assets/common.js";
import { checkWork,checkPlan,orgList,orgInfo } from "../config/data";

export default {
    getCheckPlan(context,data){
        const url = serverUrl + "/admin/plan/getdata";
        emitAjax(url,data,function(result){
            context.commit("getCheckPlan",result);
        })
    },
    getCheckWork(context,data){
        const url = serverUrl + "/admin/check/index";
        emitAjax(url,data,function(result){
            context.commit("getCheckWork",result);
        })
    },
    getOrgList(context,data){
        const url = serverUrl +"/admin/org/index";
        emitAjax(url,data,function(result){
            context.commit("getOrgList",result);
        })
    }
}