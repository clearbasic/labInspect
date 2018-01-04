<template>
    <li :class="['dd-item',{'dd-collapsed':!open}]">
        <button data-action="collapse" :class="[{show:open},{hidden:!open}]" v-if="data.child&&data.child.length>0" @click="open = false"></button>
        <button data-action="expand" :class="[{show:!open},{hidden:open}]" v-if="data.child&&data.child.length>0" @click="open = true"></button>
        <div class="dd-handle" >
            {{data.title}}({{data.id}})
            <div class="pull-right action-buttons">
                <a class="blue" @click="editMenu">
                    <i class="ace-icon fa fa-pencil bigger-130"></i>
                </a>
                <a class="red" @click="delMenu">
                    <i class="ace-icon fa fa-trash-o bigger-130"></i>
                </a>
            </div>
        </div>
        <ol class="dd-list" v-if="data.child&&data.child.length>0">
            <navItem :data = "menu" v-for="(menu,index) in data.child" :key="'nav'+index" :parentFn="parentFn"></navItem>
        </ol>
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
            },
            parentFn:{
                type:Function,
                default:null
            }
        },
        methods:{
            openMenu(){
                this.open = !this.open;
            },
            editMenu(){
                this.parentFn(this.data);
            },
            delMenu(){
                const URl = this.serverUrl+'/admin/menus/del';
                const _this = this;
                if(confirm("是否要删除菜单"+this.data.title+",此操作不可逆，请慎重！")){
                    this.emitAjax(URl,{id:this.data.id},function(){
                        window.localStorage.removeItem("leftMune");
                        _this.$store.dispatch("getMenu");
                    })
                }
            }
        }
    };
</script>

