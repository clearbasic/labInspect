const env = process.env.NODE_ENV;
let serverUrl = "http://chenkq.chingo.cn:8080/lab-inspect/php/index.php";
const pathName = "";

if(env === "production"){
    serverUrl = "http://www.chingo.cn";
}
export {serverUrl,pathName};