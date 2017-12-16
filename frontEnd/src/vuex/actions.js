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
    },
    login(context,data){
        const url = serverUrl +"/admin/login/login";
        emitAjax(url,data.data,function(result){
            data.router.push(pathName+"/");
            setLocalData(result);
            context.commit("setCurrentUser",result.userInfo);
        },function(){
            delLocalData();
            data.refreshVerifyCode()
        })
    },
    logout(context,data){
        const url = serverUrl +"/admin/login/logout";
        emitAjax(url,null,function(result){
            data.router.push(pathName+"/login");
            delLocalData();
            context.commit("logout");
        })
    }
}