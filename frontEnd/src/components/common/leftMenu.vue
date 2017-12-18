<template>
    <div id="sidebars" class="sidebar responsive">
        <ul class="nav nav-list">
            <li :class="['firstNav',{active:parentNode === index,open:parentNode===index}]"
                v-for="(first,index) in leftMenuData" 
                :key="first.name"
                v-if="permission[$store.state.currentUser.user_level] >= first.permission"
            >
                <a :class="{red:index===leftMenuData.length-1}" @click="toggleMenu($event)" v-if="first.url != pathName+'/logout'">
                    <i :class="'menu-icon '+first.icon"></i>
                    <span class="menu-text">{{first.name}}</span>
                    <b class="arrow fa fa-angle-down" v-if="first.child&&first.child.length>0"></b>
                </a>
                <a :class="{red:index===leftMenuData.length-1}" @click="logout" v-if="first.url == pathName+'/logout'">
                    <i :class="'menu-icon '+first.icon"></i>
                    <span class="menu-text">{{first.name}}</span>
                    <b class="arrow fa fa-angle-down" v-if="first.child&&first.child.length>0"></b>
                </a>
                <ul class="submenu" v-if="first.child&&first.child.length>0">
                    <li :class="{active:$route&&$route.meta.active==second.url}" 
                        v-for="second in first.child" 
                        :key="second.name"
                        v-if="permission[$store.state.currentUser.user_level] >= second.permission"
                    >
                        <router-link :to="second.url" :class="{'dropdown-toggle':second.child&&second.child.length>0}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            {{second.name}}
                        </router-link>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
            <i class="ace-icon fa fa-angle-double-left"></i>
        </div>
    </div>
</template>
<script>
import leftMenuData from '../../config/leftMenu';
export default {
    data() {
        return {
            leftMenuData:leftMenuData, //菜单数据
            parentNode:0,//第一级左厕栏高亮索引
            active:0,
            isOpen:true
        }
    },
    methods: {
        toggleMenu(event){
            if($(event.target).parents(".firstNav").hasClass("open")){
                return false;
            }
            $(".firstNav").removeClass("open");
            $(".firstNav").find(".submenu").slideUp(300);
            $(event.target).parents(".firstNav").find(".submenu").slideDown(300);
            $(event.target).parents(".firstNav").addClass("open");
        },
        logout(){
            if(window.confirm("是否要退出本系统！")){
                this.$store.dispatch("logout",{router:this.$router});
            }
        }
    },
    mounted(){
        //左侧栏高亮
        let active = this.$route.meta.active;
        for (let index = 0; index < this.leftMenuData.length; index++) {
            const element = this.leftMenuData[index];
            if(element.child){
                for(let i = 0; i < element.child.length ; i++){
                    if(element.child[i].url == active){
                        this.parentNode = index;
                        break;
                    };
                }
            }
        }
    }
};
</script>