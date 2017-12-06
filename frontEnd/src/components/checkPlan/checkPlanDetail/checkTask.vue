<template>
    <div class="checkTask">
        <ul class="list-group">
            <div class="list-group-item list-group-item-info">
                <h4 style="margin:0;" class="clearfix">
                    自查计划 <small>共{{schoolArray.length}}次</small>
                    <i class="ace-icon glyphicon glyphicon-plus pull-right" @click="addTask('school')"></i>
                </h4>
            </div>
            <li class="list-group-item" v-for="(item,index) in schoolArray" :key="'zicha'+index" v-if="item.task_level === 'school'">
                {{item.task_name}}，
                自 <datepicker v-model="item.dt_begin" width="140"></datepicker>
                至 <datepicker v-model="item.dt_end" width="140"></datepicker>
                <button @click="delTask(item.task_id)" class=" btn btn-danger btn-xs"><i class="ace-icon glyphicon glyphicon-minus"></i></button>
                <button v-if="index === schoolArray.length-1" class="btn btn-primary btn-sm" @click="autoAddTask('school',1)">增加下一个月</button>
            </li>
            <li v-show="newTaskLevel==='school'" class="list-group-item">
                {{newTaskName}}，
                自 <datepicker v-model="newTaskDtBegin"  placeholder="输入计划开始时间" width="140"></datepicker>
                至 <datepicker v-model="newTaskDtEnd"  placeholder="输入计划结束时间" width="140"></datepicker>
                <button class="btn btn-success btn-sm" @click="createNewTask()"><i class="ace-icon glyphicon glyphicon-ok"></i></button>
                <button class="btn btn-danger btn-sm" @click="addTask('')"><i class="ace-icon glyphicon glyphicon-remove"></i></button>
            </li>
        </ul>
        <ul class="list-group">
            <div class="list-group-item list-group-item-info">
                <h4 style="margin:0;">
                    复查计划 <small>共{{collegeArray.length}}次</small>
                    <i class="ace-icon glyphicon glyphicon-plus pull-right" @click="addTask('college')"></i>
                </h4>
            </div>
            <li class="list-group-item" v-for="(item,index) in collegeArray" :key="'zicha'+index" v-if="item.task_level === 'college'">
                {{item.task_name}}，
                自 <datepicker v-model="item.dt_begin" width="140"></datepicker>
                至 <datepicker v-model="item.dt_end" width="140"></datepicker>
                <button @click="delTask(item.task_id)" class=" btn btn-danger btn-xs"><i class="ace-icon glyphicon glyphicon-minus"></i></button>
                <button v-if="index === collegeArray.length-1" class="btn btn-primary btn-sm" @click="autoAddTask('college',4)">增加下一季度</button>
            </li>
            <li v-show="newTaskLevel==='college'" class="list-group-item">
                {{newTaskName}}，
                自 <datepicker v-model="newTaskDtBegin" placeholder="输入计划开始时间" width="140"></datepicker>
                至 <datepicker v-model="newTaskDtEnd" placeholder="输入计划结束时间" width="140"></datepicker>
                <button class="btn btn-success btn-sm" @click="createNewTask()"><i class="ace-icon glyphicon glyphicon-ok"></i></button>
                <button class="btn btn-danger btn-sm" @click="addTask('')"><i class="ace-icon glyphicon glyphicon-remove"></i></button>
            </li>
        </ul>
        <ul class="list-group">
            <div class="list-group-item list-group-item-info">
                <h4 style="margin:0;">
                    抽查计划 <small>共{{labArray.length}}次</small>
                    <i class="ace-icon glyphicon glyphicon-plus pull-right" @click="addTask('lab')"></i>
                </h4>
            </div>
            <li class="list-group-item" v-for="(item,index) in labArray" :key="'zicha'+index" v-if="item.task_level === 'lab'">
                {{item.task_name}}，
                自 <datepicker v-model="item.dt_begin" width="140"></datepicker>
                至 <datepicker v-model="item.dt_end" width="140"></datepicker>
                <button @click="delTask(item.task_id)" class=" btn btn-danger btn-xs"><i class="ace-icon glyphicon glyphicon-minus"></i></button>
            </li>
            <li v-show="newTaskLevel==='lab'" class="list-group-item">
                {{newTaskName}}，
                自 <datepicker v-model="newTaskDtBegin"  placeholder="输入计划开始时间" width="140"></datepicker>
                至 <datepicker v-model="newTaskDtEnd"  placeholder="输入计划结束时间" width="140"></datepicker>
                <button class="btn btn-success btn-sm" @click="createNewTask()"><i class="ace-icon glyphicon glyphicon-ok"></i></button>
                <button class="btn btn-danger btn-sm" @click="addTask('')"><i class="ace-icon glyphicon glyphicon-remove"></i></button>
            </li>
        </ul>
    </div>
</template>
<script>
import datepicker from 'vue2-datepicker';
import moment from 'moment';
export default {
    name: "checkTask",
    data(){
        return {
            schoolArray:[],
            collegeArray:[],
            labArray:[],
            newTaskName:"",
            newTaskLevel:"",
            newTaskDtBegin:"",
            newTaskDtEnd:"",
        }
    },
    components:{
        datepicker
    },
    computed:{
        checkPlan(){
            return this.$store.state.checkPlan
        }
    },
    methods:{
        addTask(type){//显示添加计划输入框
            this.newTaskLevel = type;
            
            if(type === ''){
                this.newTaskName = '';
                this.newTaskLevel = '';
                this.newTaskDtBegin = '';
                this.newTaskDtEnd = '';
            }else{
                this.newTaskName = "第"+(this[type+'Array'].length+1)+"次";
            }
        },
        createNewTask(){//创建新计划
            if(this.newTaskName === '' || this.newTaskLevel === '' || this.newTaskDtBegin === '' || this.newTaskDtEnd === ''){
                alert("请填写完整的信息")
            }else{
                this.checkPlan.task_list.push({
                    task_name:this.newTaskName,
                    task_level:this.newTaskLevel,
                    dt_begin:this.newTaskDtBegin,
                    dt_end:this.newTaskDtEnd,
                })
                this.$store.dispatch("setCheckPlan",this.checkPlan);
                this.addTask("")
            }
        },
        delTask(id){
            var _this = this;
            if(confirm("删除后不可恢复，请谨慎操作！！"))
            for (let index = 0; index < this.checkPlan.task_list.length; index++) {
                const item = _this.checkPlan.task_list[index];
                if(item.task_id === id){
                    _this.checkPlan.task_list.splice(index,1);
                    this.$store.dispatch("setCheckPlan",_this.checkPlan);
                    break;
                };
            }
        },
        autoAddTask(type,space){//自动添加一条
            let length = this[type+"Array"].length;
            let {dt_begin,dt_end} = this[type+"Array"][length-1];
            let changedBegin = moment(dt_begin).add(space,"months").format("YYYY-MM-DD");
            let changedEnd = moment(dt_end).add(space,"months").format("YYYY-MM-DD");
            let _this = this;
            let newTask = {
                plan_id:this.$route.params.id,
                task_name:"第"+(length+1)+"次",
                task_level:type,
                dt_begin:changedBegin,
                dt_end:changedEnd
            }
            this.checkPlan.task_list.push(newTask);
            this.$store.dispatch("setCheckPlan",this.checkPlan);
        },
        setCount(){//计划分类
            const _this = this;
            _this.schoolArray = [];
            _this.collegeArray = [];
            _this.labArray = [];
            const taskList = this.checkPlan.task_list;
            if(taskList){
                for (let index = 0; index < taskList.length; index++) {
                    const element = taskList[index];
                    switch (element.task_level) {
                        case "school":
                            _this.schoolArray.push(Object.assign({},element))
                            break;
                        case "college":
                            _this.collegeArray.push(Object.assign({},element))
                            break;
                        case "lab":
                            _this.labArray.push(Object.assign({},element))
                        default:
                            break;
                    }
                }
            }
        },
    },
    watch:{
        checkPlan:function(){
           this.setCount();
        }
    }
};
</script>