//一些公用的方法
import md5 from "crypto-js/md5";

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
                    window.location.href = "/login";
                    break;
                case 103:
                    delLocalData();
                    alert("您的帐号被禁用或者被删除，请联系相关管理员！");
                    window.location.href = "/login";
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
export {emitAjax,setLocalData,delLocalData};