<template>
    <!-- 右侧内容 -->
    <div class="main-content rule" @click="closeSelect">
        <div class="main-content-inner">
            <!-- 面包屑 -->
            <div class="breadcrumbs" id="breadcrumbs">
                <ul class="breadcrumb">
                    <li>
                        <i class="ace-icon fa fa-home home-icon"></i>
                        <router-link :to="pathName+'/'">首页</router-link>
                    </li>
                    <li>
                        <router-link :to="pathName+'/rule'" class="active">{{title}}</router-link>
                    </li>
                </ul>
            </div>
            <!-- 右侧主要内容 -->
            <div class="page-content">
                <div class="page-header">
                    <h1>
                        {{title}}
                        <div class="pull-right">
                            <button class="btn btn-primary btn-sm" @click="setShowAdd">
                                <i class="ace-icon glyphicon glyphicon-plus hidden-480"></i>
                                添加
                            </button>
                        </div>
                    </h1>
                </div>
                <!-- 添加 修改权限点 -->
                <transition name="fade">
                    <div class="addRule" v-if="showAdd">
                        <div class="row">
                            <div class="form-horizontal col-lg-8">
                                <div class="form-group">
                                    <label for="title" class="col-sm-2 control-label">显示名称</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="newRule.title" id="title" placeholder="显示名称">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label">父节点</label>
                                    <div class="col-sm-10">
                                        <div class="position-relative has-feedback">
                                            <input type="text" class="form-control"  v-model="newRule.pid" @click.stop="select=true">
                                            <span class="glyphicon glyphicon-chevron-down form-control-feedback"></span>
                                            <div class="widget-main padding-8 widget-box widget-color-blue2 selectMenu"  v-if="select">
                                                <ul class="tree tree-selectable">
                                                    <li class="tree-item" @click="selectPid(0)">
                                                        <div class="tree-branch-name">无</div>
                                                    </li>
                                                    <selectItem :data="rule" 
                                                        v-for="(rule,index) in rule_tree" 
                                                        :key="'nav'+index" 
                                                        :parentFn="selectPid"
                                                        :currentLevel = "newRule.level"
                                                    ></selectItem>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">名称</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" v-model="newRule.name" id="name" placeholder="权限名称">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="level" class="col-sm-2 control-label">权限级别：</label>
                                    <label class="control-label">
                                        <input type="radio" v-model="newRule.level" value="1" class="ace">
                                        <span class="lbl">
                                            模块
                                        </span>
                                    </label>
                                    <label class="control-label">
                                        <input type="radio" v-model="newRule.level" value="2" class="ace">
                                        <span class="lbl">
                                            控制器
                                        </span>
                                    </label>
                                    <label class="control-label">
                                        <input type="radio" v-model="newRule.level" value="3" class="ace">
                                        <span class="lbl">
                                            方法
                                        </span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="sort" class="col-sm-2 control-label">状态：</label>
                                    <label class="control-label">
                                        <input type="radio" v-model="newRule.status" value="1" class="ace">
                                        <span class="lbl">
                                            开启
                                        </span>
                                    </label>
                                    <label class="control-label">
                                        <input type="radio" v-model="newRule.status" value="0" class="ace">
                                        <span class="lbl">
                                            禁用
                                        </span>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-10 col-sm-offset-2">
                                        <button class="btn btn-success btn-sm" @click="addRule">保存</button>
                                        <button class="btn btn-default btn-sm" @click="setHideAdd">取消</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition>
                <!-- 权限列表 -->
                <transition name="fade">
                    <div class="table-resonsive" v-if="!showAdd">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th class="center little">权限ID</th>
                                    <th>父级名称</th>
                                    <th>显示名称</th>
                                    <th class="center little">名称</th>
                                    <th class="center little">状态</th>
                                    <th class="center little">操作</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="rule in rule_list" :key="'rule'+rule.id">
                                    <td class="center little">{{rule.id}}</td>
                                    <td>
                                        <span v-if="rule.pid==0">无</span>
                                        <span v-for="pRule in rule_list" :key="'pRule'+pRule.id" v-if="pRule.id == rule.pid">{{pRule.title}}</span>
                                    </td>
                                    <td>{{rule.title}}</td>
                                    <td class="center little">{{rule.name}}</td>
                                    <td class="center little">
                                        <span v-if="rule.status == 1">开启</span>
                                        <span v-if="rule.status == 0">禁用</span>
                                    </td>
                                    <td class="center little">
                                        <div class="hidden-xs btn-group">
                                            <button class="btn btn-xs btn-success" @click="editRule(rule)" title="编辑">
                                                <i class="ace-icon glyphicon glyphicon-edit"></i>
                                            </button>
                                            <button class="btn btn-xs btn-danger" @click="delRule(rule)" title="删除">
                                                <i class="ace-icon fa fa-trash-o"></i>
                                            </button>
                                        </div>
                                        <div class="hidden-sm hidden-md hidden-lg">
                                            <div class="inline pos-rel">
                                                <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto" aria-expanded="false">
                                                    <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                                    <li>
                                                        <a class="tooltip-info blue" @click="editRule(rule)">
                                                            <i class="ace-icon glyphicon glyphicon-edit bigger-100"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="tooltip-info red" @click="delRule(rule)">
                                                            <i class="ace-icon fa fa-trash-o bigger-100"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="rule_list.length == 0">
                                    <td colspan="6" class="center">
                                        暂无权限
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

<script>
import selectItem from '../selectItem.vue';
export default {
    name: "rule",
    components: {
        selectItem
    },
    data() {
        return {
            title: "权限点设置",
            newRule:{
                status:"1",
                pid:0,
                level:3
            },
            showAdd:false,
            select:false,
            rule_list:[],
            rule_tree:[]
        };
    },
    methods: {
        init(){
            this.getRuleTree();
            this.getRuleList();
        },
        getRuleList(){
            //获取权限点列表
            const URL = this.serverUrl + '/admin/rules/index';
            const _this = this;
            this.emitAjax(URL,null,function(result){
                _this.rule_list = result;
            })
        },
        getRuleTree(){
            //获取权限点树状结构
            const URL = this.serverUrl + '/admin/rules/index';
            const _this = this;
            this.emitAjax(URL,{type:"tree"},function(result){
                _this.rule_tree = result;
            })
        },
        addRule(){
            //添加新权限
            let URL = this.serverUrl + '/admin/rules/add';
            if(this.newRule.id){
                URL = this.serverUrl + '/admin/rules/edit';
            }
            const _this = this;
            this.emitAjax(URL,this.newRule,function(result){
                _this.setHideAdd();
                _this.init();
            })
        },
        editRule(rule){
            //编辑权限
            this.setShowAdd();
            this.newRule = Object.assign({},rule);
        },
        delRule(rule){
            if(confirm("是否要删除"+rule.title+"权限点，此操作不可逆，请慎重！")){
                const URL = this.serverUrl + '/admin/rules/del';
                const _this = this;
                this.emitAjax(URL,{id:rule.id},function(result){
                    _this.init();
                })
            }
        },
        setShowAdd(){
            //切换添加修改表单
            this.showAdd = true;
        },
        setHideAdd(){
            //切换到权限列表
            this.showAdd = false;
            this.newRule = {
                status:1,
                pid:0,
                level:3
            };
        },
        selectPid(id){
            //选择父节点
            if(this.newRule.id == id){
                alert("不能选择自身");
                return false;
            }
            this.newRule.pid = id;
        },
        closeSelect(){
            //下拉菜单关闭
            this.select = false;
        }
    },
    mounted(){
        this.init();
    }
};
</script>

