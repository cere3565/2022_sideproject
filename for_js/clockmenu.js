Vue.use(Vuetify)

new Vue({
  el: '#app',
  vuetify: new Vuetify(),
  selected:[],
  data: { 
    clockMenu:[],
    ProjectInfo:[],
    singleSelect: false,
    selected: [],
    headers: [
      { text: '鬧鐘名稱', value: 'ClockName', sortable: false, align: 'left'},
      { text: '監控欄位', value: 'MonitorList', sortable: false },
      { text: '適用專案', value: 'MatchPJ', sortable: false },
			{ text: '排除條件', value: 'NoMatch', sortable: false },
    ],
  },

  created() {
    this.getPjInfo();
  },

  watch: { 
    clockMenu: {
      handler (newVal, oldVal) {
        console.log('區域Prop changed: ', newVal, ' | was: ', oldVal)
      },
      immediate: true, //立即监听
      deep: true // 深度监听
    }
  },

  methods: {
    //抓取localStorage資料找尋符合鬧鐘
    getPjInfo() {
      var that = this;
      var storage = window.localStorage;
      let formData = new FormData();
      var json = storage.getItem('ProjectInfo');
      
      formData.append("ProjectInfo",json)
      axios.post('../for_system/project/clockMenu_get.php',formData).then((res)=>{
        that.clockMenu = res.data
        // console.log('clockMenu=',that.clockMenu)
      }).catch(error => {console.log(error)}) 
    },

    //儲存選取鬧鐘
    saveclock(){
      console.log(this.selected[0]['ID'])
      let formData = new FormData();
      var storage = window.localStorage
      var json = storage.getItem('ProjectInfo')
      this.ProjectInfo = JSON.parse(json)
      formData.append("selected", JSON.stringify(this.selected));
			formData.append("ClockNo", this.ProjectInfo.ProjectID);
      axios.post('../for_system/project/alarm_add.php',formData).then((res)=>{
        console.log(res)
        setTimeout(() => {window.close()}, 1000) 
      }).catch(error => {console.log(error)})  
    },
  }  
})