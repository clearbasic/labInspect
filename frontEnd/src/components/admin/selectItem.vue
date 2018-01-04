<template>
    <li :class="[{'tree-item':!data.child},{'tree-branch':data.child},'tree-open']">
        <div @click="parentFn(data.id)" :class="[{'tree-branch-name':!data.child},{'tree-branch-header':data.child}]">
            <i class="icon-folder ace-icon tree-minus" v-if="data.level<currentLevel&&data.child&&secondOpen" @click.stop="secondOpen = !secondOpen"></i>
            <i class="icon-folder ace-icon tree-plus" v-if="data.level<currentLevel&&data.child&&!secondOpen" @click.stop="secondOpen = !secondOpen"></i>
            <span>{{data.level}},{{currentLevel}},{{data.title}}({{data.id}})</span>
        </div>
        <ul :class="['tree-branch-children',{hide:!secondOpen}]" v-if="data.level<currentLevel&&data.child&&data.child.length>0">
            <navItem :data = "menu" 
                v-for="(menu,index) in data.child" 
                :key="'nav'+index"  
                :parentFn="parentFn"
                :currentLevel = "currentLevel"
            ></navItem>
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
                secondOpen:false
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
            },
            currentLevel:{
                type:Number,
                default:0
            }
        },
    };
</script>

