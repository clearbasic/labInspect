<template>
    <transition name="bounce">
        <div class="toast" v-if="show">
            <h5 class="toastHead">系统提示</h5>
            <div class="toastBody">
                <i class="ace-icon glyphicon glyphicon-ok text-success"></i>
                {{msg}}
            </div>
            
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
        background: #fff;
        border:3px solid #1d8d12;
        border-radius: 5px;
        margin: -35px 0 0 -80px;
        z-index: 1000;
        width: 160px;
        height: 70px;
    }
    .toastHead {
        background: #0bbb08;
        height: 30px;
        padding-left:10px;
        margin:0;
        line-height: 30px;
        color:#fff;
    }
    .toastBody {
        padding-left:10px;
        line-height: 30px;
    }
</style>

