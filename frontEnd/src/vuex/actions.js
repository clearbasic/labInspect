import {serverUrl} from "../config/server.js";
import { checkWork,checkPlan } from "../config/data";
import { emitAjax } from "../assets/common.js";

export default {
    setCheckPlan(context,data){
        console.log(data)
        const url = serverUrl + "/admin/plan/getdata";
        emitAjax(url,data,function(checkPlan){
            context.commit("setCheckPlan",checkPlan);
        })
    },
    setCheckWork(context,data){
        if(data){
            context.commit("setCheckWork",data);
        }else{
            context.commit("setCheckWork",checkWork);
        }
    }
}