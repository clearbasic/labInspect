<template>
    <li :class="[{open:open&&data.child&&data.child.length>0},{active:isActive}]">
        <router-link :to="pathName+data.url" @click="openMenu" v-if="data.url != pathName">
            <i :class="'menu-icon '+data.icon"></i>
            <span class="menu-text">{{data.title}}</span>
            <b class="arrow fa fa-angle-down" v-if="data.child&&data.child.length>0"></b>
        </router-link>
        <a @click="openMenu" v-if="data.url == pathName">
            <i :class="'menu-icon '+data.icon"></i>
            <span class="menu-text">{{data.title}}</span>
            <b class="arrow fa fa-angle-down" v-if="data.child&&data.child.length>0"></b>
        </a>
        <ul :class="['submenu',{show:open},{hidden:!open}]" v-if="data.child&&data.child.length>0">
            <navItem v-for="(menu,index) in data.child" :key="'menu'+index" :data="menu"></navItem>
        </ul>
    </li>
</template>
<script>
    export default {
        name:"navItem",
        data(){
            return {
                open:true,
                isActive:false,
            }
        },
        props:{
            data:{
                type:Object,
                default:null
            }
        },
        methods:{
            openMenu(){
                this.open = !this.open;
            },
            setActive(){
                let active = this.$route.meta.active;
                if(active == this.pathName+this.data.url){
                    this.open = true;
                    this.isActive = true;
                }else{
                    this.isActive = false;
                }
                if(this.data.child){
                    for (let index = 0; index < this.data.child.length; index++) {
                        const element = this.data.child[index];
                        if(active == this.pathName+element.url){
                            this.isActive = true;
                        }
                    }   
                }
            }
        },
        watch:{
            $route(){
                this.setActive();
            }
        },
        mounted(){
            this.setActive();
        }
    };
</script>
