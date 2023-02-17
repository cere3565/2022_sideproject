Vue.use(Vuetify)

new Vue({
  el: '#app',
  vuetify: new Vuetify(),
  data() {
    return {
      project:[],
      user:[],
      notifyList:[],
      dialog: false,
      userAccount: '',
      userpws: '',
      nowTime: '',
      login_s: -1,
      headers: [
        { text: '專案名稱', value: 'PJName', sortable: false, align: 'left'},
        { text: '專案種類', value: 'PJKind' },
        { text: '專案形式', value: 'Attr' },
        { text: '期限種類', value: 'TimeKind', sortable: false },
        { text: '選擇鬧鐘｜專案完成', value: 'action', sortable: false, width :180  },
      ],
    }
  },

  watch: {
		dialog (val) { val || this.close() },
	},

  computed: { 
		loginTitle () { return this.login_s === -1 ? '' : this.user[0]['Username'] + ",您好!" },
	},

  created(){
    Push.Permission.request();
    this.setScheduledTask(16,57)
  },

  mounted (){
		this.getData()
	},

  methods: {
    getData(){
			var that = this;
			axios.get("../for_system/project/Project_get.php").then(function(res){
				that.project = res.data;
			}).catch(error => {console.log(error)})
		},

    checklogin(){
      axios.get('../for_system/project/login.php', {
        params: {
          username: this.userAccount,
          userpws: this.userpws
        }}).then(res => {
        if (res.data.length > 0) { 
          this.user = res.data
          this.login_s = 1
          this.GetNotifylist()
          setTimeout(() => {this.pushMessage()}, 10000)
        }
        else { alert('登入失敗') }
      }).catch(function (error) { // 請求失敗處理
        console.log(error)
      });
      this.dialog = false
    },

    //保存點選的專案資料存至localStorage，讓clockmenu.php抓取符合的鬧鐘
    getPjInfo(item) {
      if (typeof (Storage) == "undefined") {
        alert("對不起，您的瀏覽器不支持 web 存儲。");
      }
      
      var infoStr = JSON.stringify(item);
      localStorage.setItem('ProjectInfo', infoStr);
      
      var clockmenu = window.open("clockMenu.php","_blank",
      'height=200,width=400,status=yes,top=300,left=600,toolbar=no,menubar=no,location=no');
      clockmenu.focus();
    },

    //關閉登入視窗
		close () {
      this.dialog = false
    },

    //創建明天通知清單至資料庫
    CreatNotifylist(){
      axios.get("../for_system/project/notify_creat.php").then(function(res){
				console.log(res)
			}).catch(error => {console.log(error)})
    },

    //取得當日通知清單，並在用戶登入時發出通知(log紀錄)
    GetNotifylist(){
      var that = this;
      axios.get("../for_system/project/notify_get.php?user=" + this.userAccount).then(function(res){
				that.notifyList = res.data;
			}).catch(error => {console.log(error)})
    },

    //使用者收到發出的通知後更新通知清單紀錄(容錯機制)
    UpdateNotifylist(){      
      let formData = new FormData();
      console.log("Update=" ,this.notifyList)
      formData.append("notifyList",JSON.stringify(this.notifyList))
			axios.post("../for_system/project/notify_update.php",formData).then(function(res){
				console.log(res)
			}).catch(error => {console.log(error)})
    },

    finishlist(item){ 
      console.log("item=" ,item.ProjectID) 
      let formData = new FormData();
      formData.append("ProjectID", item.ProjectID);
			axios.post("../for_system/project/project_finish.php",formData).then(function(res){
				console.log(res)
			}).catch(error => {console.log(error)})
    },

    //發送到期通知
    pushMessage(){
      var user = this.user[0]['Username']
      var PJname = this.notifyList[0]['PJName']
      if (window.Notification) {
        console.log("支持彈出框")
      } else {
        // 不支持
        console.log("不支持")
        alert("當前瀏覽器不支持彈出消息通知哦!")
      }
      if(window.Notification && Notification.permission !== "denied") {
        console.log("data2=" ,this.notifyList)
        Notification.requestPermission(function(status) {
          if (status === "granted") {
            var notify = new Notification(user, {
              body:'【' + PJname + '】專案即將到期通知！請點擊以查看',
            });            
            //點擊當前消息提示框後，跳轉到當前頁面
            notify.onclick = function() { 
              //打開發出通知的網頁
              window.focus();
              //關閉消息
              notify.close();
              console.log("已點擊")
            }          
          } else { alert("當前瀏覽器不支持消息通知哦！！"); }
        });
      }
      this.UpdateNotifylist()
    },

    // 設置每日下午兩點創建明天通知清單至資料庫的任務
    setScheduledTask (hour, minute) {
      let taskTime = new Date()
      taskTime.setHours(hour)
      taskTime.setMinutes(minute)
      let timeDiff = taskTime.getTime() - (new Date()).getTime()
      console.log('taskTime', timeDiff + ',' + (new Date()).getTime())
      timeDiff = timeDiff > 0 ? timeDiff : (timeDiff + 24 * 60 * 60 * 1000)
      console.log('taskTime', timeDiff)
      setTimeout(this.doTimeTask, timeDiff)
    },    
    doTimeTask () {
      console.log('doTimeTask', (new Date()).getTime())
      this.doSchTask()
      this.timerTask = setInterval(this.doSchTask, 24 * 60 * 60 * 1000)
    },
    doSchTask () {
      // localStorage.removeItem('task')
      this.CreatNotifylist()
      console.log('創建明天通知清單並存至資料庫的任務', (new Date()).getTime())
    },
  },
})