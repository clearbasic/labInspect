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
            context.commit("filterOrg",result);
        })
    },
    logout(){
        const url = serverUrl +"/admin/login/logout";
        emitAjax(url,null,function(result){
            delLocalData();
            const iframe = document.createElement("iframe");
            iframe.src = "http://cas.xzhmu.edu.cn/cas/logout";
            document.getElementsByTagName("body")[0].appendChild(iframe);
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
    },
    getPlanList(context,data){
        const url = serverUrl + "/admin/plan/index";
        emitAjax(url,null,function(result){
            context.commit("getPlanList",result);
        })
    },
    getSysData(context,data){
        const url = serverUrl + "/admin/SystemConfig/index";
        emitAjax(url,data,function(result){
            context.commit("getSysData",result);
        })
    }
}