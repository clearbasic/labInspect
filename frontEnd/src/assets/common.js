//一些公用的方法
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
                    alert("登录过期，请重新登录！");
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
            console.log(error);
            if(error.readyStatus !=4 || error.status != 200){
                alert("连接不到服务器，网络请求失败");
            }
        }
    });
}
//生存密钥
function setSign(a,b,c){
    let signString = a+b+c+a;
    return md5(signString).toString();
}
function setLocalData(data){
    localStorage.setItem("authKey",data.authKey);
    localStorage.setItem("sessionId",data.sessionId);
    localStorage.setItem("userInfo",JSON.stringify(data.userInfo));
}
function delLocalData(){
    localStorage.removeItem("authKey");
    localStorage.removeItem("sessionId");
    localStorage.removeItem("userInfo");
}
function checkPermission(obj){
    const currentUser = JSON.parse(localStorage.getItem("userInfo"));
    const user_per = obj.permission[currentUser.user_level];

    if(user_per < obj.$route.meta.permission){
        alert("您没有访问权限！");
        obj.$router.push(pathName + "/");
        return false;
    }
    return true;
}
export {emitAjax,setLocalData,delLocalData,checkPermission};