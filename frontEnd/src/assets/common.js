//一些公用的方法
import Vue from "vue";
import md5 from "crypto-js/md5";
import { pathName } from "../config/server";
//发起网络请求
function emitAjax(url,opt,success,error){
    let app_secret="c6d9622fdc385b26129fc8a4c7a30c2a";
    let app_key=2347508144;
    let timestamp = Date.parse(new Date());
    let sign = setSign(app_secret,app_key,timestamp);
    const authKey = localStorage.getItem("authKey");
    const sessionId = localStorage.getItem("sessionId");
    let data = Object.assign({},{
        app_key,
        timestamp,
        sign
    },opt)
    const isOk = checkPermission(url);
    if(isOk.flag){
        $.ajax({
            url,
            data,
            type:"POST",
            headers:{
                authKey,
                sessionId
            },
            success:function(result){
                let resultObj = result;
                if(typeof result == "string"){
                    resultObj = JSON.parse(resultObj);
                }
                switch (resultObj.code) {
                    case 200:
                        success && success(resultObj.data);
                        break;
                    case 101:
                        //登录失效
                        delLocalData();
                        window.location.href = pathName+"/login";
                        break;
                    case 103:
                        delLocalData();
                        alert("您的帐号被禁用或者被删除，请联系相关管理员！");
                        window.location.href = pathName+"/login";
                        break;
                    default:
                        alert(resultObj.error);
                        error&&error(resultObj);
                        break;
                }
            },
            error:function(error){
                if(error.readyStatus !=4 || error.status != 200){
                    alert("连接不到服务器，网络请求失败");
                }
            }
        });
    }else{
        alert("您没有权限做此操作！"+isOk.pathName);
    }
}
//生存密钥
function setSign(a,b,c){
    let signString = a+b+c+a;
    return md5(signString).toString();
}
//判断权限
function checkPermission(url){
    const urlArray = url.split("/");
    const authList = JSON.parse(localStorage.getItem("authList"));
    const userInfo = JSON.parse(localStorage.getItem("userInfo"));
    const pathName = urlArray[urlArray.length-3]+'/'+urlArray[urlArray.length-2]+'/'+urlArray[urlArray.length-1];
    let flag = false;

    if(authList){
        for (let index = 0; index < authList.length; index++) {
            const auth = authList[index];
            if(auth == pathName){
                flag = true;
                break;
            }
        }
    }else{
        flag = true;
    }
    //admin帐号不过滤
    if(userInfo && userInfo.username == "admin"){
        flag = true;
    }
    return {
        flag,
        pathName
    };
}
function setLocalData(data){
    localStorage.setItem("authKey",data.authKey);
    localStorage.setItem("sessionId",data.sessionId);
    localStorage.setItem("userInfo",JSON.stringify(data.userInfo));
    localStorage.setItem("leftMune",JSON.stringify(data.menusList));
    localStorage.setItem("authList",JSON.stringify(data.authList));
}
function delLocalData(){
    localStorage.removeItem("authKey");
    localStorage.removeItem("sessionId");
    localStorage.removeItem("userInfo");
    localStorage.removeItem("leftMune");
    localStorage.removeItem("authList");
}
function getUserInfo(){
    let userInfo = JSON.parse(localStorage.getItem("userInfo"));
    let data = {};
    if(userInfo){
        switch (userInfo.group_level) {
            case md5("lab_level").toString():
                data = Object.assign({},userInfo,{
                    group_level:"lab"
                })
                break;
            case md5("college_level").toString():
                data = Object.assign({},userInfo,{
                    group_level:"college"
                })
                break;
            case md5("school_level").toString():
                data = Object.assign({},userInfo,{
                    group_level:"school"
                })
                break;
            default:
                break;
        }
    }
    return data;
}
export {emitAjax,setLocalData,delLocalData,getUserInfo};