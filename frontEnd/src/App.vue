<template>
    <div id="app" :class="{app:isApp,'no-skin':true}">
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
        const PATHNAME = window.location.search;
        let isApp = PATHNAME.search("isApp");
        if(isApp>0){
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
</style>

