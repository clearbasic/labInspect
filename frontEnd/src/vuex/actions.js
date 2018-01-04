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
    getOrgList(context,data){
        const url = serverUrl +"/admin/org/index";
        emitAjax(url,data,function(result){
            context.commit("getOrgList",result);
        })
    },
    logout(){
        const url = serverUrl +"/admin/login/logout";
        emitAjax(url,null,function(result){
            delLocalData();
            window.location.href = pathName+"/login";
        })
    },
    getMenu(context,data){
        const leftMune = JSON.parse(window.localStorage.getItem("leftMune"));
        if(leftMune){
            context.commit("getMenu",leftMune);
        }else{
            const url = serverUrl +"/admin/menus/index";
            emitAjax(url,{type:"tree"},function(result){
                window.localStorage.setItem("leftMune",JSON.stringify(result));
                context.commit("getMenu",result);
            })
        }
    }
}