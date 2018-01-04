<template>
    <div id="sidebars" class="sidebar responsive">
        <ul class="nav nav-list">
            <navItem v-for="(menu,index) in leftMenuData" :key="'menu'+index" :data="menu"></navItem>
            <li>
                <a @click="logout" class="red">
                    <i class="menu-icon ace-icon glyphicon glyphicon-off"></i>
                    <span class="menu-text">退出</span>
                </a>
            </li>
        </ul>
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left"></i>
        </div>
    </div>
</template>
<script>
import navItem from './navItem.vue';
export default {
    data() {
        return {}
    },
    components:{navItem},
    computed:{
        leftMenuData(){
            return this.$store.state.leftMenu;
        }
    },
    methods: {
        logout(){
            if(window.confirm("是否要退出本系统！")){
                this.$store.dispatch("logout");
            }
        }
    },
    mounted(){
        if(this.$store.state.leftMenu.length == 0){
            this.$store.dispatch("getMenu");
        }
    }
};
</script>