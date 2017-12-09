<template>
    <div class="checkDescription">
        <vueUeditor
            :ueditorConfig="ueditorConfig"
            @ready="editorReady"
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
                        ['fullscreen', 'source', 'undo', 'redo','bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc',"|",'link',"simpleupload","insertimage","|",'justifyleft', 'justifyright','justifycenter','justifyjustify',"|","inserttable"]
                    ],
                    initialFrameHeight:400,
                }
            }
        },
        methods:{
            editorReady(ueditor){
                const content = this.checkPlan.intro;
                if(content){
                    ueditor.setContent(content)
                }
            }
        },
        computed:{
            checkPlan(){
                return this.$store.state.checkPlan
            }
        },
        watch:{
            checkPlan:function(){
                
            }
        }
    };
</script>

