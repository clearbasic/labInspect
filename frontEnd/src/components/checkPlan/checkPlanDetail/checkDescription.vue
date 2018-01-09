<template>
    <div class="checkDescription">
        <vueUeditor
            :ueditorConfig="ueditorConfig"
            @ready="editorReady"
            :ueditorPath="pathName+'/static/ueditor/'"
        ></vueUeditor>
    </div>
</template>
<script>
    import vueUeditor from 'vue-ueditor'
    export default {
        name:"checkDescription",
        components:{
           vueUeditor
        },
        data(){
            return {
                ueditorConfig:{
                    toolbars:[
                        ['fullscreen', 'source', 'undo', 'redo','bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat',
                         'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall',
                          'cleardoc',"|",'link',"simpleupload","insertimage","|",'justifyleft', 'justifyright','justifycenter','justifyjustify',"|","inserttable"]
                    ],
                    initialFrameHeight:400,
                },
                ueditor:null,
                content:"",
                pathName:"",
            }
        },
        methods:{
            editorReady(ueditor){
                const _SELF = this;
                this.ueditor = ueditor;
                ueditor.addListener("blur",()=>{
                    const newContent = ueditor.getContent();
                    if(newContent != _SELF.content){
                        let data = Object.assign({},_SELF.checkPlan.plan,{
                            intro:newContent,
                        })
                        const URL = _SELF.serverUrl + "/admin/plan/edit";
                        _SELF.emitAjax(URL, data, function(){
                            _SELF.$store.commit("showToast",{isShow:true});
                        },function(){
                            //修改失败刷新页面
                            _SELF.$router.push(pathName+'/checkPlan/'+_SELF.$route.params.id);
                        });
                    }
                })
                ueditor.addListener("focus",()=>{
                    _SELF.content = ueditor.getContent();
                })
            },
            setIntroContent(){
                const content = this.checkPlan.plan.intro?this.checkPlan.plan.intro:"";
                this.ueditor && this.ueditor.setContent(content);
            }
        },
        computed:{
            checkPlan(){
                return this.$store.state.checkPlan
            }
        },
        watch:{
            checkPlan(){
                this.setIntroContent();
            },
            ueditor(){
                this.setIntroContent();
            }
        }
    };
</script>

