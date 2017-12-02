<template>
    <div id="sidebar" class="sidebar responsive">
        <ul class="nav nav-list">
            <li :class="{active:parentNode === index,open:active === index}"
                v-for="(first,index) in leftMenuData" 
                :key="first.name"
                @click="toggleMenu(index)"
            >
                <a :class="{'dropdown-toggle':first.child&&first.child.length>0}">
                    <i :class="first.icon"></i>
                    <span class="menu-text">{{first.name}}</span>
                    <b class="arrow fa fa-angle-down" v-if="first.child&&first.child.length>0"></b>
                </a>
                <ul class="submenu" v-if="first.child&&first.child.length>0">
                    <li :class="{active:$route&&$route.meta.active==second.url}" 
                        v-for="second in first.child" 
                        :key="second.name"
                    >
                        <router-link :to="second.url" :class="{'dropdown-toggle':second.child&&second.child.length>0}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            {{second.name}}
                        </router-link>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</template>
<script>
import leftMenuData from '../../config/leftMenu';
export default {
    data() {
        return {
            leftMenuData:leftMenuData, //菜单数据
            parentNode:0,//第一级左厕栏高亮索引
            active:0
        }
    },
    methods: {
        toggleMenu(id){
            this.active = id;
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