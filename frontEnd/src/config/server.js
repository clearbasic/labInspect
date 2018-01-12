const env = process.env.NODE_ENV;
let serverUrl = "http://192.168.240.81:8080/lab-inspect/php/index.php";
const pathName = "";

if(env === "production"){
    serverUrl = "http://222.193.95.173/php/index.php";
}
export {serverUrl,pathName};