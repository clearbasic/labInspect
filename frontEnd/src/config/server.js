const env = process.env.NODE_ENV;
let serverUrl = "http://ckq.chingo.cn:8081/lab-inspect/php/index.php";
const pathName = "";
//前端登录地址
let frontUrl = "http://192.168.240.81:8090/login";
if(env === "production"){
    serverUrl = "http://222.193.95.173/php/index.php";
    frontUrl = "http://222.193.95.173/login";
    // serverUrl = "http://lab-inspcet.appdemo.chingo.cn/php/index.php";
    // serverUrl = "http://192.168.99.103/site1/php/index.php";
}
export {serverUrl,pathName,frontUrl};