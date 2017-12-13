//一些公用的方法
import md5 from "crypto-js/md5";

//发起网络请求
function emitAjax(url,opt,success,error){
    let app_secret="c6d9622fdc385b26129fc8a4c7a30c2a";
    let app_key=2347508144;
    let timestamp = Date.parse(new Date());
    let sign = setSign(app_secret,app_key,timestamp);
    let data = Object.assign({},{
        app_key,
        timestamp,
        sign
    },opt)
    $.post(url,data,function(result){
        let resultObj = result;
        if(typeof result == "string"){
            resultObj = JSON.parse(resultObj);
        }
        if(resultObj.code == 200){
            success && success(resultObj.data);
        }else{
            alert(resultObj.error);
            error&&error(resultObj)
        }
    })
    .error(function(error){
        console.log(error);
        if(error.readyStatus !=4 || error.status != 200){
            alert("连接不到服务器，网络请求失败");
        }
    })
}
//生存密钥
function setSign(a,b,c){
    let signString = a+b+c+a;
    return md5(signString).toString();
}
export {emitAjax};