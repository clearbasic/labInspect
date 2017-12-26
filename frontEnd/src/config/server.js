const env = process.env.NODE_ENV;
let serverUrl = "http://192.168.240.81:8080/lab-inspect/php/index.php";
const pathName = "";

if(env === "production"){
    serverUrl = "http://www.chingo.cn";
}
export {serverUrl,pathName};