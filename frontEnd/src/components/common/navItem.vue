<template>
    <li :class="[{open:open&&data.child&&data.child.length>0},{active:isActive}]" @click="openMenu">
        <router-link :to="data.url">
            <i :class="'menu-icon '+data.icon"></i>
            <span class="menu-text">{{data.name}}</span>
            <b class="arrow fa fa-angle-down" v-if="data.child&&data.child.length>0"></b>
        </router-link>
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
            }
        },
        mounted(){
            let active = this.$route.meta.active;
            if(active == this.data.url){
                this.isActive = true;
            }
            if(this.data.child){
                for (let index = 0; index < this.data.child.length; index++) {
                    const element = this.data.child[index];
                    if(active == element.url){
                        this.isActive = true;
                    }
                }
            }
        }
    };
</script>
