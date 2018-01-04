<template>
	
    <!-- 右侧内容 -->
    <div class="main-content feedback">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/feedback'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                    </h1>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "feedback",
    data() {
        return {
            title: "问题反馈",
        };
    },
    methods: {
        
    },
    mounted() {
        if(this.checkPermission(this)){
           
        }
    }
};
</script>
