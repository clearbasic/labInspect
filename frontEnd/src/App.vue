<template>
    <div :class="{app:isApp,'no-skin':true}">
        <router-view/>
    </div>
</template>

<script>
export default {
    name: "app",
    data(){
        return {
            isApp:false
        }
    },
    mounted(){
        //判断是不是手机app访问
        /* const PATHNAME = window.location.search;
        let isApp = PATHNAME.search("isApp");
        if(isApp>0){
            this.isApp = true;
        } */
        if(app){
            this.isApp = true;
        }
    }
};
</script>
<style>
.app #navbar {
    display: none !important;
}
.app #sidebar {
    display: none !important;
}
.app #breadcrumbs {
    display: none !important;
}
a:hover {
    cursor: pointer;
}
.little {
    width:80px;
}
.inlineInput {
    display: inline-block;
    width:100%;
    max-width: 160px;
}
.app .page-content {
    padding-left:0;
    padding-right:0;
}
.has-error input[type=text],.has-error input[type=password] {
    border-color:#f00;
}
.form-group h5 {
    margin:0;
}
.table-responsive {
    overflow-y: auto;
}
@media screen and (max-width:767px){
    .little {
        width:auto;
    }  
}
</style>

