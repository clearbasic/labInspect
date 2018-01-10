<template>
    <div class="search form-group">
        <div class="dropdown open">
            <button class="btn btn-primary btn-sm" @click.stop="toggle">
                <i class="ace-icon glyphicon glyphicon-search"></i>
                检索
            </button>
            <slot name="right"></slot>
            <div class="dropdown-menu dropdown-info col-sm-8 col-md-6  widget-main" v-show="searchShow" @click.stop="openDrop">
                <slot></slot>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "search",
    data() {
        return {
            searchShow:false
        };
    },
    props:{
        show:{
            type:Boolean,
            default:false,
        },
        setShow:{
            type:Function,
            default:null
        }
    },
    watch:{
        show(){
            this.searchShow = this.show;
        }
    },
    methods:{
        openDrop(){
            this.setShow(true);
        },
        close(){
            this.setShow(false);
        },
        toggle(){
            this.setShow(!this.searchShow);
        }
    }
};
</script>
