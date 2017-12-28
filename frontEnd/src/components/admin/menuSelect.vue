<template>
    <li :class="[{'tree-item':!data.child},{'tree-branch':data.child},'tree-open']">
        <div @click="parentFn(data.id)" :class="[{'tree-branch-name':!data.child},{'tree-branch-header':data.child}]">
            <i class="icon-folder ace-icon tree-minus" v-if="data.child"></i>
            <span>{{data.title}}({{data.id}})</span>
        </div>
        <ul class="tree-branch-children" v-if="data.child&&data.child.length>0">
            <navItem :data = "menu" v-for="(menu,index) in data.child" :key="'nav'+index" :parentFn="parentFn"></navItem>
        </ul>
    </li>
</template>
<script>
    export default {
        name:"navItem",
        data(){
            return {
                open:false,
                isActive:false,
            }
        },
        props:{
            data:{
                type:Object,
                default:null
            },
            parentFn:{
                type:Function,
                default:null
            }
        },
    };
</script>

