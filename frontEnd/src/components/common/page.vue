<template>
    <div>
        <ul class="pagination">
            <li>
                <a @click="clickPage(1)">首页</a>
            </li>
            <li>
                <a v-if="page==1"><i class="ace-icon fa fa-angle-double-left gray"></i></a>
                <a v-if="page!=1" @click="prev"><i class="ace-icon fa fa-angle-double-left"></i></a>
            </li>
            <li v-for="item in pageArray" :key="'page'+item" :class="{active:page==item}">
                <a @click="clickPage(item)">{{item}}</a>
            </li>
            <li>
                <a v-if="page==pages"><i class="ace-icon fa fa-angle-double-right gray"></i></a>
                <a  @click="next" v-if="page!=pages"><i class="ace-icon fa fa-angle-double-right"></i></a>
            </li>
            <li>
                <a @click="clickPage(pages)">尾页</a>
            </li>
            <li>
                <input class="pull-left" v-model="gotoPage">
                <a @click="clickPage(gotoPage)">跳转</a>
            </li>
            <li>
                <span style="background:none;color:#000;border:0;">当前{{page}}/{{pages}}页</span>
            </li>
        </ul>
    </div>
</template>
<script>
export default {
    data() {
        return {
            page: 1,
            pageArray:[1],
            gotoPage:1,
        };
    },
    props: {
        pages: {
            type: Number,
            default: 1
        },
        setPage: {
            type: Function,
            default: null
        },
        currentPage:{
            type: Number,
            default: 1
        }
    },
    methods: {
        clickPage(item) {
            if(item>this.pages){
                alert("跳转的页数不能超过"+this.pages+"。");
                return false;
            }
            this.page = item;
        },
        prev() {
            this.page--;
        },
        next(){
            this.page++;
        },
        setPageArray(){
            let length = 5;
            if(this.pages<5){
                length = this.pages;
            }
            this.pageArray = [];
            let first = this.page-2 >=1?this.page-2:1;
            for (let index = 0; index < length; index++) {
                if(index+first<=this.pages){
                    this.pageArray.push(index+first);
                }
            }
            if(this.pages == 0 ){
                this.pageArray = [1];
            }
        }
    },
    watch:{
        page(){
            this.setPage(this.page);
            this.gotoPage = this.page;
            //当总页数大于5点到第一个和最后一个
            if((this.page == this.pageArray[0] || this.page == this.pageArray[this.pageArray.length-1])&&this.pages>5 || this.pageArray.indexOf(this.page)<0){
                this.setPageArray();
            }
        },
        pages(){
            if(this.page>this.pages){
                this.page = this.pages;
            }
            this.setPageArray();
        },
        currentPage(){
            this.page = this.currentPage;
        }
    },
    mounted(){
        if(this.currentPage != this.page) {
            this.page = this.currentPage;
        }
        this.setPageArray();
    }
};
</script>