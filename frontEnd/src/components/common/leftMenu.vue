<template>
    <div id="sidebar" class="sidebar responsive">
        <ul class="nav nav-list">
            <li :class="{active:(route&&route.path==first.route)||(parentNode==first.route),open:index==0}" 
                v-for="(first,index) in leftMenuData" 
                :key="first.name"
                
            >
                <a :href="first.url" :class="{'dropdown-toggle':first.child&&first.child.length>0}">
                    <i :class="first.icon"></i>
                    <span class="menu-text">{{first.name}}</span>
                    <b class="arrow fa fa-angle-down" v-if="first.child&&first.child.length>0"></b>
                </a>
                <ul class="submenu" v-if="first.child&&first.child.length>0">
                    <li :class="{active:route&&route.path==second.route}" v-for="second in first.child" :key="second.name">
                        <a :href="second.url" :class="{'dropdown-toggle':second.child&&second.child.length>0}">
                            <i class="menu-icon fa fa-caret-right"></i>
                            {{second.name}}
                        </a>
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
            parentNode:"/",
            route:"",
        }
    },
    methods: {
        setNavActive (parent){
            this.parentNode = parent;
        }
    },
    mounted(){
        this.route = this.$route;
    }
};
</script>