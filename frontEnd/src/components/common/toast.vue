<template>
    <transition name="fade">
        <div class="toast" v-if="show">
            <i class="ace-icon glyphicon glyphicon-ok text-success"></i>
            {{msg}}
        </div> 
    </transition>
</template>
<script>
    
    export default {
        data(){
            return {
                isShow:false
            }
        },
        props:{
            show:{
                type:Boolean,
                default:false
            },
            timeout:{
                type:Number,
                default:2000,
            },
            msg:{
                type:String,
                default:"保存成功",
            },
            hide:{
                type:Function,
                default:null
            }
        },
        methods:{
            hideToast(){
                const _this = this;
                setTimeout(() => {
                    _this.hide();
                }, this.timeout);
            }
        },
        watch:{
            show(){
                if(this.show == true){
                    this.hideToast();
                }
            }
        }
    };
</script>
<style>
    .toast {
        position: fixed;
        top:50%;
        left:50%;
        background: #f1f1f1;
        border:1px solid #ccc;
        line-height: 30px;
        border-radius: 5px;
        padding: 0 10px;
        margin-top: -15px;
        z-index: 1000;
    }
    .fade-enter,.fade-leave-to {
        opacity: 0;
    }
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s
    }
</style>

